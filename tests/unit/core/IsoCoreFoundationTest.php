<?php

namespace tests\core;

use app\models\Action;
use app\models\ActionApproval;
use app\models\AuditLog;
use app\models\Evidence;
use Yii;
use yii\console\Application;
use yii\db\Connection;

require_once __DIR__ . '/../../../migrations/m260807_064500_add_iso27001_core_foundation_tables.php';

class IsoCoreFoundationTest extends \Codeception\Test\Unit
{
    /** @var Connection */
    private $db;

    /** @var m260807_064500_add_iso27001_core_foundation_tables */
    private $migration;

    private $migrationRolledBack = false;

    protected function _before()
    {
        $this->ensureApp();

        $this->db = new Connection([
            'dsn' => 'sqlite::memory:',
        ]);
        $this->db->open();

        Yii::$app->set('db', $this->db);

        $this->migration = new \m260807_064500_add_iso27001_core_foundation_tables();
        $this->migration->db = $this->db;
        $this->migration->safeUp();
    }

    protected function _after()
    {
        if (!$this->migrationRolledBack) {
            $this->migration->safeDown();
        }

        $this->db->close();
    }

    public function testMigrationUpDownSanity()
    {
        $this->assertNotNull($this->db->schema->getTableSchema('audit_logs', true));
        $this->assertNotNull($this->db->schema->getTableSchema('actions', true));
        $this->assertNotNull($this->db->schema->getTableSchema('evidences', true));
        $this->assertNotNull($this->db->schema->getTableSchema('action_approvals', true));

        $this->migration->safeDown();
        $this->migrationRolledBack = true;

        $this->assertNull($this->db->schema->getTableSchema('action_approvals', true));
        $this->assertNull($this->db->schema->getTableSchema('evidences', true));
        $this->assertNull($this->db->schema->getTableSchema('actions', true));
        $this->assertNull($this->db->schema->getTableSchema('audit_logs', true));
    }

    public function testActionCloseGuardrails()
    {
        $action = new Action([
            'title' => 'İlk Aksiyon',
            'status' => Action::STATUS_OPEN,
        ]);
        $this->assertTrue($action->save(), 'Aksiyon başlangıç kaydı oluşturulmalı.');

        $action->status = Action::STATUS_CLOSED;
        $this->assertFalse($action->validate(['status']));

        $errors = $action->getErrors('status');
        $this->assertTrue($this->containsText($errors, 'en az bir kanıt'));
        $this->assertTrue($this->containsText($errors, 'reviewer ve approver'));

        $evidence = new Evidence([
            'action_id' => $action->id,
            'evidence_type' => 'dokuman',
        ]);
        $this->assertTrue($evidence->save(), 'Kanıt kaydı oluşturulmalı.');

        $action->status = Action::STATUS_CLOSED;
        $this->assertFalse($action->validate(['status']));
        $this->assertTrue($this->containsText($action->getErrors('status'), 'reviewer ve approver'));

        $approval = new ActionApproval([
            'action_id' => $action->id,
            'reviewer_id' => 10,
            'approver_id' => 11,
        ]);
        $this->assertTrue($approval->save(), 'Onay kaydı oluşturulmalı.');

        $action->status = Action::STATUS_CLOSED;
        $this->assertTrue($action->validate(['status']));
    }

    public function testAuditLogsAreCreatedOnEntityChanges()
    {
        $action = new Action([
            'title' => 'Audit Log Senaryosu',
            'status' => Action::STATUS_OPEN,
        ]);
        $this->assertTrue($action->save());

        $action->title = 'Audit Log Senaryosu Güncel';
        $this->assertTrue($action->save());

        $evidence = new Evidence([
            'action_id' => $action->id,
            'evidence_type' => 'ekran-goruntusu',
        ]);
        $this->assertTrue($evidence->save());
        $this->assertTrue((bool) $evidence->delete());

        $this->assertNotNull(AuditLog::find()->where(['entity_type' => 'actions', 'entity_id' => $action->id, 'action' => 'create'])->one());
        $this->assertNotNull(AuditLog::find()->where(['entity_type' => 'actions', 'entity_id' => $action->id, 'action' => 'update'])->one());
        $this->assertNotNull(AuditLog::find()->where(['entity_type' => 'evidences', 'action' => 'create'])->one());
        $this->assertNotNull(AuditLog::find()->where(['entity_type' => 'evidences', 'action' => 'delete'])->one());
    }

    private function ensureApp()
    {
        if (Yii::$app === null) {
            new Application([
                'id' => 'unit-tests',
                'basePath' => dirname(__DIR__, 3),
                'components' => [],
            ]);
        }
    }

    private function containsText(array $errors, $needle)
    {
        foreach ($errors as $error) {
            if (mb_strpos($error, $needle) !== false) {
                return true;
            }
        }

        return false;
    }
}
