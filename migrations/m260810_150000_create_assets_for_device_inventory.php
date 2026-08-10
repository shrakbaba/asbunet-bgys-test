<?php

use yii\db\Migration;
use yii\db\Query;

class m260810_150000_create_assets_for_device_inventory extends Migration
{
    private const SOFTWARE_OWNER = 'Yazılım Şube Müdürlüğü';

    public function safeUp()
    {
        $assetTable = '{{%bgys_varlik_envanteri}}';
        $deviceTable = '{{%env_cihaz_liste}}';

        $this->addColumn($assetTable, 'source_device_id', $this->integer()->null()->after('created_by'));
        $this->createIndex('uq-bgys_varlik_envanteri-source_device_id', $assetTable, 'source_device_id', true);
        $this->addForeignKey(
            'fk-bgys_varlik_envanteri-source_device_id', $assetTable, 'source_device_id',
            $deviceTable, 'id', 'SET NULL', 'CASCADE'
        );

        $categoryIds = [];
        foreach ((new Query())->select(['id', 'adi'])->from('{{%bgys_kategori}}')->all($this->db) as $category) {
            $categoryIds[$category['adi']] = (int)$category['id'];
        }
        foreach (['Ağ ve Sistemler', 'Fiziksel Mekan', 'Uygulamalar'] as $requiredCategory) {
            if (!isset($categoryIds[$requiredCategory])) {
                throw new RuntimeException('Gerekli BGYS kategorisi bulunamadı: ' . $requiredCategory);
            }
        }

        $corrections = [
            'Kamera Sistemi' => ['system', 'Ağ ve Sistemler'],
            'İklimlendirme Sistemi' => ['physical', 'Fiziksel Mekan'],
            'Antivirus' => ['software', 'Uygulamalar'],
            'IP Telefon ve Santral' => ['system', 'Ağ ve Sistemler'],
        ];
        foreach ($corrections as $assetName => $classification) {
            $assetCount = (new Query())->from($assetTable)->where(['varlik_adi' => $assetName])->count('*', $this->db);
            if ((int)$assetCount !== 1) {
                throw new RuntimeException('Tekil BGYS varlığı bulunamadı: ' . $assetName);
            }
            $this->update($assetTable, [
                'asset_type' => $classification[0], 'kategori' => $categoryIds[$classification[1]],
            ], ['varlik_adi' => $assetName]);
        }

        $applicationCategoryId = (int)$categoryIds['Uygulamalar'];
        $softwareDevices = (new Query())->select(['l.id', 'mo.model'])
            ->from(['l' => $deviceTable])
            ->innerJoin(['t' => '{{%env_cihaz_turu}}'], 't.id=l.cihaz_turu_id')
            ->innerJoin(['mo' => '{{%env_model}}'], 'mo.id=l.model_id')
            ->where(['t.asset_type' => 'software', 'l.bgys_asset_id' => null])
            ->orderBy('l.id')->all($this->db);

        foreach ($softwareDevices as $device) {
            $this->insert($assetTable, [
                'varlik_adi' => trim($device['model']),
                'kategori' => $applicationCategoryId,
                'asset_type' => 'software',
                'varlik_sahibi' => self::SOFTWARE_OWNER,
                'owner_type' => 'unit',
                'owner_unit' => self::SOFTWARE_OWNER,
                'source_device_id' => (int)$device['id'],
                'aciklama' => 'Cihaz/yazılım envanterinden oluşturuldu; sınıflandırma ve risk değerleri doğrulanmalıdır.',
            ]);
            $assetId = (int)$this->db->getLastInsertID();
            $this->update($deviceTable, ['bgys_asset_id' => $assetId], ['id' => (int)$device['id'], 'bgys_asset_id' => null]);
        }

        $deviceAssetMappings = [
            'Ağ Cihazları (Switch, Router, Modem vb.)' => 'Ağ Cihazları (Switch, Firewall, Router, Modem vb.)',
            'Sunucular' => 'Sunucular',
            'Kamera Sistemi' => 'Kamera Sistemi',
            'Yedekleme ve Depolama Cihazları' => 'Yedekleme ve Depolama Cihazları',
            'Firewall' => 'Firewall',
            'IP Telefon ve Santral' => 'IP Telefon ve Santral',
            'İklimlendirme Sistemi' => 'İklimlendirme Sistemi',
            'Storage' => 'Yedekleme ve Depolama Cihazları',
            'Load Balancer' => 'Ağ Cihazları (Switch, Firewall, Router, Modem vb.)',
            'SAN Switch' => 'Ağ Cihazları (Switch, Firewall, Router, Modem vb.)',
        ];
        foreach ($deviceAssetMappings as $deviceTypeName => $assetName) {
            $deviceTypeId = (new Query())->select('id')->from('{{%env_cihaz_turu}}')->where(['cihaz_turu' => $deviceTypeName])->scalar($this->db);
            $assetIds = (new Query())->select('id')->from($assetTable)->where(['varlik_adi' => $assetName])->column($this->db);
            if (!$deviceTypeId || count($assetIds) !== 1) {
                throw new RuntimeException('Cihaz türü/BGYS varlığı eşleştirilemedi: ' . $deviceTypeName . ' -> ' . $assetName);
            }
            $this->update($deviceTable, ['bgys_asset_id' => (int)$assetIds[0]], [
                'cihaz_turu_id' => (int)$deviceTypeId, 'bgys_asset_id' => null,
            ]);
        }

        $missingLinks = (new Query())->from($deviceTable)->where(['bgys_asset_id' => null])->count('*', $this->db);
        if ((int)$missingLinks !== 0) {
            throw new RuntimeException('BGYS varlığına bağlanamayan cihaz kayıtları bulundu.');
        }
    }

    public function safeDown()
    {
        $assetTable = '{{%bgys_varlik_envanteri}}';
        $deviceTable = '{{%env_cihaz_liste}}';
        $this->update($deviceTable, ['bgys_asset_id' => null]);
        $generatedAssetIds = (new Query())->select('id')->from($assetTable)
            ->where(['not', ['source_device_id' => null]])->column($this->db);
        if ($generatedAssetIds) {
            $this->delete($assetTable, ['id' => $generatedAssetIds]);
        }
        $categoryIds = [];
        foreach ((new Query())->select(['id', 'adi'])->from('{{%bgys_kategori}}')->all($this->db) as $category) {
            $categoryIds[$category['adi']] = (int)$category['id'];
        }
        $originalClassifications = [
            'Kamera Sistemi' => ['hardware', 'IoT'],
            'İklimlendirme Sistemi' => ['hardware', 'IoT'],
            'Antivirus' => ['system', 'Ağ ve Sistemler'],
            'IP Telefon ve Santral' => ['process', 'Süreç'],
        ];
        foreach ($originalClassifications as $assetName => $classification) {
            $this->update($assetTable, [
                'asset_type' => $classification[0], 'kategori' => $categoryIds[$classification[1]],
            ], ['varlik_adi' => $assetName]);
        }
        $this->dropForeignKey('fk-bgys_varlik_envanteri-source_device_id', $assetTable);
        $this->dropIndex('uq-bgys_varlik_envanteri-source_device_id', $assetTable);
        $this->dropColumn($assetTable, 'source_device_id');
    }
}
