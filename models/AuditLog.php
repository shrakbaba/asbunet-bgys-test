<?php

namespace app\models;

use Yii;

class AuditLog extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'audit_logs';
    }

    public function rules()
    {
        return [
            [['entity_type', 'action'], 'required'],
            [['entity_id', 'changed_by'], 'integer'],
            [['old_value', 'new_value'], 'string'],
            [['changed_at'], 'safe'],
            [['entity_type'], 'string', 'max' => 128],
            [['action'], 'string', 'max' => 32],
            [['ip'], 'string', 'max' => 45],
            [['user_agent'], 'string', 'max' => 255],
        ];
    }

    public static function record($model, $action, $oldValue = null, $newValue = null)
    {
        $audit = new self();
        $audit->entity_type = $model->tableName();
        $audit->entity_id = $model->getPrimaryKey();
        $audit->action = (string) $action;
        $audit->old_value = $oldValue !== null ? json_encode($oldValue, JSON_UNESCAPED_UNICODE) : null;
        $audit->new_value = $newValue !== null ? json_encode($newValue, JSON_UNESCAPED_UNICODE) : null;
        $audit->changed_by = self::resolveUserId();
        $audit->ip = self::resolveUserIp();
        $audit->user_agent = self::resolveUserAgent();

        if (!$audit->save(false)) {
            Yii::error('Audit log kaydı oluşturulamadı.', __METHOD__);
        }
    }

    private static function resolveUserId()
    {
        if (!Yii::$app->has('user')) {
            return null;
        }

        $identity = Yii::$app->user->identity;
        return $identity ? (int) $identity->id : null;
    }

    private static function resolveUserIp()
    {
        if (!Yii::$app->has('request') || Yii::$app->request->isConsoleRequest) {
            return null;
        }

        return (string) Yii::$app->request->userIP;
    }

    private static function resolveUserAgent()
    {
        if (!Yii::$app->has('request') || Yii::$app->request->isConsoleRequest) {
            return null;
        }

        return (string) Yii::$app->request->userAgent;
    }
}
