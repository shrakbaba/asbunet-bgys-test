<?php

use yii\db\Migration;

class m260810_140000_add_software_inventory_fields extends Migration
{
    public function safeUp()
    {
        $table = '{{%env_cihaz_liste}}';
        $this->addColumn($table, 'license_type', $this->string(30)->null()->after('is_legacy'));
        $this->addColumn($table, 'license_quantity', $this->integer()->null()->after('license_type'));
        $this->addColumn($table, 'license_start_date', $this->date()->null()->after('license_quantity'));
        $this->addColumn($table, 'license_end_date', $this->date()->null()->after('license_start_date'));
        $this->addColumn($table, 'hosting_environment', $this->string(30)->null()->after('license_end_date'));
        $this->addColumn($table, 'hosting_detail', $this->string(255)->null()->after('hosting_environment'));
        $this->addColumn($table, 'supplier_name', $this->string(255)->null()->after('hosting_detail'));
        $this->addColumn($table, 'lifecycle_status', $this->string(30)->null()->after('supplier_name'));
        $this->createIndex('idx-env_cihaz_liste-license_end_date', $table, 'license_end_date');
        $this->createIndex('idx-env_cihaz_liste-lifecycle_status', $table, 'lifecycle_status');
    }

    public function safeDown()
    {
        $table = '{{%env_cihaz_liste}}';
        $this->dropIndex('idx-env_cihaz_liste-lifecycle_status', $table);
        $this->dropIndex('idx-env_cihaz_liste-license_end_date', $table);
        foreach (['lifecycle_status', 'supplier_name', 'hosting_detail', 'hosting_environment',
            'license_end_date', 'license_start_date', 'license_quantity', 'license_type'] as $column) {
            $this->dropColumn($table, $column);
        }
    }
}
