<?php

use yii\db\Migration;

class m260807_133000_add_creator_to_bgys_supplier extends Migration
{
    public function safeUp()
    {
        $table = '{{%bgys_firma_bilgi}}';
        $this->addColumn($table, 'created_by', $this->integer()->null()->after('id'));
        $this->createIndex('idx-bgys_firma_bilgi-created_by', $table, 'created_by');
        $this->addForeignKey('fk-bgys_firma_bilgi-created_by', $table, 'created_by', '{{%user}}', 'id', 'RESTRICT', 'CASCADE');
    }

    public function safeDown()
    {
        $table = '{{%bgys_firma_bilgi}}';
        $this->dropForeignKey('fk-bgys_firma_bilgi-created_by', $table);
        $this->dropIndex('idx-bgys_firma_bilgi-created_by', $table);
        $this->dropColumn($table, 'created_by');
    }
}
