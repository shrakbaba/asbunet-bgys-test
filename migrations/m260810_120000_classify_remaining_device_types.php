<?php

use yii\db\Migration;
use yii\db\Query;

class m260810_120000_classify_remaining_device_types extends Migration
{
    private function classifications()
    {
        return [
            'hardware' => [
                'Kullanıcı Bilgisayarları', 'Akıllı tahta', 'Projeksiyon', 'Yazıcılar',
                'Taşınabilir Medya (harddisk, bellek vb.)', 'Mobil Cihazlar (Telefon)', 'Elektronik İmza',
            ],
            'system' => [
                'Ağ Cihazları (Switch, Router, Modem vb.)', 'Sunucular', 'Kamera Sistemi',
                'Yedekleme ve Depolama Cihazları', 'Firewall', 'IP Telefon ve Santral',
                'Giriş Kontrol Sistemi (Parmak, Kart Okuyucular vb.)',
                'Ortam İzleme ve Algılama Sistemi', 'Kartlı Geçiş Sistemi', 'Storage',
                'Load Balancer', 'SAN Switch',
            ],
            'physical' => [
                'Sarf Malzeme', 'Kabin ve Ekipmanları', 'Jeneratör', 'UPS',
                'İklimlendirme Sistemi', 'Yangın Söndürme Sistemi',
            ],
        ];
    }

    public function safeUp()
    {
        $table = '{{%env_cihaz_turu}}';
        foreach ($this->classifications() as $assetType => $names) {
            $this->update($table, ['asset_type' => $assetType], ['cihaz_turu' => $names, 'asset_type' => null]);
        }

        $unclassified = (new Query())->from($table)->where(['asset_type' => null])->count('*', $this->db);
        if ((int)$unclassified !== 0) {
            throw new RuntimeException('Sınıflandırılmamış cihaz türleri bulundu; işlem geri alındı.');
        }
        $this->alterColumn($table, 'asset_type', $this->string(20)->notNull()->after('cihaz_turu'));
    }

    public function safeDown()
    {
        $table = '{{%env_cihaz_turu}}';
        $this->alterColumn($table, 'asset_type', $this->string(20)->null()->after('cihaz_turu'));
        foreach ($this->classifications() as $names) {
            $this->update($table, ['asset_type' => null], ['cihaz_turu' => $names]);
        }
    }
}
