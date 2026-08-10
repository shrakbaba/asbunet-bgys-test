<?php

use yii\db\Migration;
use yii\db\Query;

class m260810_090000_add_asset_type_to_bgys_assets extends Migration
{
    public function safeUp()
    {
        $table = '{{%bgys_varlik_envanteri}}';
        $this->addColumn($table, 'asset_type', $this->string(20)->null()->after('kategori'));

        $mapping = [
            'hardware' => ['IoT', 'Taşınabilir Cihaz ve Ortamlar'],
            'software' => ['Uygulamalar'],
            'system' => ['Ağ ve Sistemler'],
            'process' => ['Süreç'],
            'document' => ['Belge'],
            'physical' => ['Fiziksel Mekan'],
        ];

        foreach ($mapping as $type => $categoryNames) {
            $categoryIds = (new Query())
                ->select('id')
                ->from('{{%bgys_kategori}}')
                ->where(['adi' => $categoryNames])
                ->column($this->db);
            if ($categoryIds) {
                $this->update($table, ['asset_type' => $type], ['kategori' => $categoryIds]);
            }
        }

        $unmappedCount = (new Query())
            ->from($table)
            ->where(['asset_type' => null])
            ->count('*', $this->db);
        if ((int)$unmappedCount !== 0) {
            throw new RuntimeException('Türü belirlenemeyen varlık kayıtları bulundu.');
        }

        $this->alterColumn($table, 'asset_type', $this->string(20)->notNull()->after('kategori'));
        $this->createIndex('idx-bgys_varlik_envanteri-asset_type', $table, 'asset_type');
    }

    public function safeDown()
    {
        $table = '{{%bgys_varlik_envanteri}}';
        $this->dropIndex('idx-bgys_varlik_envanteri-asset_type', $table);
        $this->dropColumn($table, 'asset_type');
    }
}
