<?php

use yii\db\Migration;

class m260810_171000_index_login_audit_events extends Migration
{
    public function safeUp()
    {
        $table = '{{%bgys_logs}}';
        $this->createIndex('idx-bgys_logs-actor-result-date', $table, ['actor', 'result', 'date']);
        $this->createIndex('idx-bgys_logs-ip-result-date', $table, ['ip_address', 'result', 'date']);
    }

    public function safeDown()
    {
        $table = '{{%bgys_logs}}';
        $this->dropIndex('idx-bgys_logs-ip-result-date', $table);
        $this->dropIndex('idx-bgys_logs-actor-result-date', $table);
    }
}
