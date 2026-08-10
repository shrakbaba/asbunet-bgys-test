<?php

use yii\db\Migration;

class m260807_123000_link_devices_to_bgys_assets_and_track_assignments extends Migration
{
    public function safeUp()
    {
        $deviceTable = '{{%env_cihaz_liste}}';
        $assignmentTable = '{{%env_cihaz_zimmet}}';

        $this->addColumn($deviceTable, 'bgys_asset_id', $this->integer()->null()->after('id'));
        $this->addColumn($deviceTable, 'created_by', $this->integer()->null()->after('bgys_asset_id'));
        $this->createIndex('idx-env_cihaz_liste-bgys_asset_id', $deviceTable, 'bgys_asset_id');
        $this->createIndex('idx-env_cihaz_liste-created_by', $deviceTable, 'created_by');
        $this->addForeignKey(
            'fk-env_cihaz_liste-bgys_asset_id',
            $deviceTable,
            'bgys_asset_id',
            '{{%bgys_varlik_envanteri}}',
            'id',
            'SET NULL',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-env_cihaz_liste-created_by',
            $deviceTable,
            'created_by',
            '{{%user}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->createTable($assignmentTable, [
            'id' => $this->primaryKey(),
            'cihaz_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'teslim_tarihi' => $this->dateTime()->notNull(),
            'iade_tarihi' => $this->dateTime()->null(),
            'teslim_eden_id' => $this->integer()->null(),
            'iade_alan_id' => $this->integer()->null(),
            'aciklama' => $this->string(500)->null(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->createIndex('idx-env_cihaz_zimmet-cihaz_id', $assignmentTable, 'cihaz_id');
        $this->createIndex('idx-env_cihaz_zimmet-user_id', $assignmentTable, 'user_id');
        $this->createIndex('idx-env_cihaz_zimmet-iade_tarihi', $assignmentTable, 'iade_tarihi');
        $this->addForeignKey('fk-env_cihaz_zimmet-cihaz_id', $assignmentTable, 'cihaz_id', $deviceTable, 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-env_cihaz_zimmet-user_id', $assignmentTable, 'user_id', '{{%user}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk-env_cihaz_zimmet-teslim_eden_id', $assignmentTable, 'teslim_eden_id', '{{%user}}', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('fk-env_cihaz_zimmet-iade_alan_id', $assignmentTable, 'iade_alan_id', '{{%user}}', 'id', 'SET NULL', 'CASCADE');
    }

    public function safeDown()
    {
        $deviceTable = '{{%env_cihaz_liste}}';
        $assignmentTable = '{{%env_cihaz_zimmet}}';

        $this->dropTable($assignmentTable);
        $this->dropForeignKey('fk-env_cihaz_liste-created_by', $deviceTable);
        $this->dropForeignKey('fk-env_cihaz_liste-bgys_asset_id', $deviceTable);
        $this->dropIndex('idx-env_cihaz_liste-created_by', $deviceTable);
        $this->dropIndex('idx-env_cihaz_liste-bgys_asset_id', $deviceTable);
        $this->dropColumn($deviceTable, 'created_by');
        $this->dropColumn($deviceTable, 'bgys_asset_id');
    }
}
