<?php

use yii\db\Migration;

class m260810_130000_mark_legacy_devices extends Migration
{
    public function safeUp()
    {
        $table = '{{%env_cihaz_liste}}';
        $this->addColumn($table, 'is_legacy', $this->boolean()->notNull()->defaultValue(false)->after('created_by'));
        $this->update($table, ['is_legacy' => true], ['created_by' => null]);
        $this->createIndex('idx-env_cihaz_liste-is_legacy', $table, 'is_legacy');
    }

    public function safeDown()
    {
        $table = '{{%env_cihaz_liste}}';
        $this->dropIndex('idx-env_cihaz_liste-is_legacy', $table);
        $this->dropColumn($table, 'is_legacy');
    }
}
