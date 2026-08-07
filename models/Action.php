<?php

namespace app\models;

use app\behaviors\AuditLogBehavior;
use app\services\ActionWorkflowService;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

class Action extends \yii\db\ActiveRecord
{
    const STATUS_OPEN = 0;
    const STATUS_CLOSED = 1;

    public static function tableName()
    {
        return 'actions';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
            [
                'class' => AuditLogBehavior::class,
            ],
        ];
    }

    public function rules()
    {
        return [
            [['title'], 'required'],
            [['description'], 'string'],
            [['status', 'owner_id', 'created_by', 'updated_by'], 'integer'],
            [['status'], 'default', 'value' => self::STATUS_OPEN],
            [['status'], 'in', 'range' => [self::STATUS_OPEN, self::STATUS_CLOSED]],
            [['due_date', 'created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['status'], 'validateCloseGuardrails'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Başlık',
            'description' => 'Açıklama',
            'status' => 'Durum',
            'owner_id' => 'Sorumlu',
            'due_date' => 'Termin Tarihi',
            'created_at' => 'Oluşturulma Tarihi',
            'updated_at' => 'Güncellenme Tarihi',
            'created_by' => 'Oluşturan',
            'updated_by' => 'Güncelleyen',
        ];
    }

    public function validateCloseGuardrails($attribute)
    {
        $isClosing = (int) $this->$attribute === self::STATUS_CLOSED
            && ($this->getIsNewRecord() || (int) $this->getOldAttribute($attribute) !== self::STATUS_CLOSED);

        if (!$isClosing) {
            return;
        }

        $service = new ActionWorkflowService();
        foreach ($service->validateClose($this) as $error) {
            $this->addError($attribute, $error);
        }
    }

    public function getEvidences()
    {
        return $this->hasMany(Evidence::className(), ['action_id' => 'id']);
    }

    public function getActionApprovals()
    {
        return $this->hasMany(ActionApproval::className(), ['action_id' => 'id']);
    }
}
