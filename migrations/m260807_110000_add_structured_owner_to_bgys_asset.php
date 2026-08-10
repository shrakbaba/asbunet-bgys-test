<?php

use yii\db\Migration;

class m260807_110000_add_structured_owner_to_bgys_asset extends Migration
{
    public function safeUp()
    {
        $table = '{{%bgys_varlik_envanteri}}';

        $this->addColumn($table, 'owner_type', $this->string(10)->null()->after('varlik_sahibi'));
        $this->addColumn($table, 'owner_unit', $this->string(100)->null()->after('owner_type'));
        $this->addColumn($table, 'owner_user_id', $this->integer()->null()->after('owner_unit'));
        $this->addColumn($table, 'created_by', $this->integer()->null()->after('owner_user_id'));

        $this->createIndex('idx-bgys_varlik_envanteri-owner_user_id', $table, 'owner_user_id');
        $this->createIndex('idx-bgys_varlik_envanteri-created_by', $table, 'created_by');
        $this->addForeignKey(
            'fk-bgys_varlik_envanteri-owner_user_id',
            $table,
            'owner_user_id',
            '{{%user}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-bgys_varlik_envanteri-created_by',
            $table,
            'created_by',
            '{{%user}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $table = '{{%bgys_varlik_envanteri}}';

        $this->dropForeignKey('fk-bgys_varlik_envanteri-created_by', $table);
        $this->dropForeignKey('fk-bgys_varlik_envanteri-owner_user_id', $table);
        $this->dropIndex('idx-bgys_varlik_envanteri-created_by', $table);
        $this->dropIndex('idx-bgys_varlik_envanteri-owner_user_id', $table);
        $this->dropColumn($table, 'created_by');
        $this->dropColumn($table, 'owner_user_id');
        $this->dropColumn($table, 'owner_unit');
        $this->dropColumn($table, 'owner_type');
    }
}
