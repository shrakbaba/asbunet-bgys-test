<?php

use yii\db\Migration;

class m260810_110000_classify_device_catalog extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%env_cihaz_turu}}', 'asset_type', $this->string(20)->null()->after('cihaz_turu'));
        $this->createIndex('idx-env_cihaz_turu-asset_type', '{{%env_cihaz_turu}}', 'asset_type');
        $this->createTable('{{%env_cihaz_turu_marka}}', [
            'cihaz_turu_id' => $this->integer()->notNull(), 'marka_id' => $this->integer()->notNull(),
            'PRIMARY KEY(cihaz_turu_id, marka_id)',
        ]);
        $this->createTable('{{%env_cihaz_turu_model}}', [
            'cihaz_turu_id' => $this->integer()->notNull(), 'model_id' => $this->integer()->notNull(),
            'PRIMARY KEY(cihaz_turu_id, model_id)',
        ]);
        $this->addForeignKey('fk-env_tur_marka-tur', '{{%env_cihaz_turu_marka}}', 'cihaz_turu_id', '{{%env_cihaz_turu}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-env_tur_marka-marka', '{{%env_cihaz_turu_marka}}', 'marka_id', '{{%env_marka}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-env_tur_model-tur', '{{%env_cihaz_turu_model}}', 'cihaz_turu_id', '{{%env_cihaz_turu}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-env_tur_model-model', '{{%env_cihaz_turu_model}}', 'model_id', '{{%env_model}}', 'id', 'CASCADE', 'CASCADE');
        $this->execute('INSERT IGNORE INTO {{%env_cihaz_turu_marka}} (cihaz_turu_id, marka_id) SELECT DISTINCT cihaz_turu_id, marka_id FROM {{%env_cihaz_liste}} WHERE marka_id IS NOT NULL');
        $this->execute('INSERT IGNORE INTO {{%env_cihaz_turu_model}} (cihaz_turu_id, model_id) SELECT DISTINCT cihaz_turu_id, model_id FROM {{%env_cihaz_liste}} WHERE model_id IS NOT NULL');
        $this->update('{{%env_cihaz_turu}}', ['asset_type' => 'software'], ['cihaz_turu' => [
            'Antivirus', 'Kritik veri işleyen kurumiçi uygulamalar',
            'Kritik veri İşlemeyen kurumiçi uygulamalar', 'Kritik veri işleyen kurumdışı uygulamalar',
            'Kritik veri İşlemeyen kurumdışı uygulamalar',
            'Enstitü On-Line Başvuru ve Otomatik Değerlendirme Modülü ',
        ]]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%env_cihaz_turu_model}}');
        $this->dropTable('{{%env_cihaz_turu_marka}}');
        $this->dropIndex('idx-env_cihaz_turu-asset_type', '{{%env_cihaz_turu}}');
        $this->dropColumn('{{%env_cihaz_turu}}', 'asset_type');
    }
}
