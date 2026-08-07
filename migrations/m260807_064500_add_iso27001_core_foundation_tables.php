<?php

use yii\db\Migration;

class m260807_064500_add_iso27001_core_foundation_tables extends Migration
{
    public function safeUp()
    {
        $this->createAuditLogsTable();
        $this->createActionsTable();
        $this->createEvidencesTable();
        $this->createActionApprovalsTable();
    }

    public function safeDown()
    {
        $this->dropActionApprovalsTable();
        $this->dropEvidencesTable();
        $this->dropActionsTable();
        $this->dropAuditLogsTable();
    }

    private function createAuditLogsTable()
    {
        $table = '{{%audit_logs}}';
        if ($this->tableExists($table)) {
            return;
        }

        $this->createTable($table, [
            'id' => $this->primaryKey(),
            'entity_type' => $this->string(128)->notNull(),
            'entity_id' => $this->integer()->null(),
            'action' => $this->string(16)->notNull(),
            'old_value' => $this->text()->null(),
            'new_value' => $this->text()->null(),
            'changed_by' => $this->integer()->null(),
            'changed_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'ip' => $this->string(45)->null(),
            'user_agent' => $this->string(255)->null(),
        ]);

        $this->createIndex('idx-audit_logs-entity', $table, ['entity_type', 'entity_id']);
        $this->createIndex('idx-audit_logs-changed_at', $table, 'changed_at');
    }

    private function createActionsTable()
    {
        $table = '{{%actions}}';
        if ($this->tableExists($table)) {
            return;
        }

        $this->createTable($table, [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'description' => $this->text()->null(),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
            'owner_id' => $this->integer()->null(),
            'due_date' => $this->date()->null(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->null()->defaultExpression('CURRENT_TIMESTAMP'),
            'created_by' => $this->integer()->null(),
            'updated_by' => $this->integer()->null(),
        ]);

        $this->createIndex('idx-actions-status', $table, 'status');
        $this->createIndex('idx-actions-owner_id', $table, 'owner_id');
    }

    private function createEvidencesTable()
    {
        $table = '{{%evidences}}';
        if ($this->tableExists($table)) {
            return;
        }

        $this->createTable($table, [
            'id' => $this->primaryKey(),
            'action_id' => $this->integer()->notNull(),
            'evidence_type' => $this->string(100)->notNull(),
            'file_path' => $this->string(255)->null(),
            'note' => $this->text()->null(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'created_by' => $this->integer()->null(),
        ]);

        $this->createIndex('idx-evidences-action_id', $table, 'action_id');
        $this->addForeignKey(
            'fk-evidences-action_id',
            $table,
            'action_id',
            '{{%actions}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
    }

    private function createActionApprovalsTable()
    {
        $table = '{{%action_approvals}}';
        if ($this->tableExists($table)) {
            return;
        }

        $this->createTable($table, [
            'id' => $this->primaryKey(),
            'action_id' => $this->integer()->notNull(),
            'reviewer_id' => $this->integer()->notNull(),
            'approver_id' => $this->integer()->notNull(),
            'review_note' => $this->text()->null(),
            'approved_at' => $this->timestamp()->null(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->createIndex('idx-action_approvals-action_id', $table, 'action_id');
        $this->createIndex('ux-action_approvals-action_id', $table, 'action_id', true);
        $this->addForeignKey(
            'fk-action_approvals-action_id',
            $table,
            'action_id',
            '{{%actions}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
    }

    private function dropActionApprovalsTable()
    {
        $table = '{{%action_approvals}}';
        if (!$this->tableExists($table)) {
            return;
        }

        $this->dropForeignKey('fk-action_approvals-action_id', $table);
        $this->dropTable($table);
    }

    private function dropEvidencesTable()
    {
        $table = '{{%evidences}}';
        if (!$this->tableExists($table)) {
            return;
        }

        $this->dropForeignKey('fk-evidences-action_id', $table);
        $this->dropTable($table);
    }

    private function dropActionsTable()
    {
        $table = '{{%actions}}';
        if (!$this->tableExists($table)) {
            return;
        }

        $this->dropTable($table);
    }

    private function dropAuditLogsTable()
    {
        $table = '{{%audit_logs}}';
        if ($this->tableExists($table)) {
            $this->dropTable($table);
        }
    }

    private function tableExists($table)
    {
        return $this->db->schema->getTableSchema($this->db->schema->getRawTableName($table), true) !== null;
    }
}
