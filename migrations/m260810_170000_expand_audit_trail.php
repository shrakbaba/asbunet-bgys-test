<?php

use yii\db\Migration;

class m260810_170000_expand_audit_trail extends Migration
{
    public function safeUp()
    {
        $table = '{{%bgys_logs}}';
        $this->alterColumn($table, 'userid', $this->integer()->null());
        $this->addColumn($table, 'actor', $this->string(255)->null()->after('userid'));
        $this->addColumn($table, 'role', $this->string(255)->null()->after('actor'));
        $this->addColumn($table, 'ip_address', $this->string(45)->null()->after('role'));
        $this->addColumn($table, 'user_agent', $this->string(512)->null()->after('ip_address'));
        $this->addColumn($table, 'correlation_id', $this->string(64)->null()->after('user_agent'));
        $this->addColumn($table, 'result', $this->string(20)->notNull()->defaultValue('success')->after('correlation_id'));
        $this->addColumn($table, 'record_type', $this->string(100)->null()->after('result'));
        $this->addColumn($table, 'record_id', $this->string(100)->null()->after('record_type'));
        $this->addColumn($table, 'old_values', $this->text()->null()->after('record_id'));
        $this->addColumn($table, 'new_values', $this->text()->null()->after('old_values'));

        $this->execute(
            'UPDATE {{%bgys_logs}} l LEFT JOIN {{%user}} u ON u.id=l.userid '
            . 'SET l.actor=u.username WHERE l.actor IS NULL AND u.id IS NOT NULL'
        );
        $this->createIndex('idx-bgys_logs-correlation_id', $table, 'correlation_id');
        $this->createIndex('idx-bgys_logs-result-date', $table, ['result', 'date']);
        $this->createIndex('idx-bgys_logs-record', $table, ['record_type', 'record_id']);
    }

    public function safeDown()
    {
        $table = '{{%bgys_logs}}';
        $this->dropIndex('idx-bgys_logs-record', $table);
        $this->dropIndex('idx-bgys_logs-result-date', $table);
        $this->dropIndex('idx-bgys_logs-correlation_id', $table);
        foreach (['new_values', 'old_values', 'record_id', 'record_type', 'result', 'correlation_id', 'user_agent', 'ip_address', 'role', 'actor'] as $column) {
            $this->dropColumn($table, $column);
        }
        $this->alterColumn($table, 'userid', $this->integer()->notNull());
    }
}
