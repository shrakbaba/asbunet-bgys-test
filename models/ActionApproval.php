<?php

namespace app\models;

use app\behaviors\AuditLogBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

class ActionApproval extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'action_approvals';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => null,
                'value' => new Expression('NOW()'),
            ],
            [
                'class' => AuditLogBehavior::class,
            ],
        ];
    }

    public function rules()
    {
        return [
            [['action_id', 'reviewer_id', 'approver_id'], 'required'],
            [['action_id', 'reviewer_id', 'approver_id'], 'integer'],
            [['review_note'], 'string'],
            [['approved_at', 'created_at'], 'safe'],
            [['action_id'], 'unique'],
            [['action_id'], 'exist', 'skipOnError' => true, 'targetClass' => Action::className(), 'targetAttribute' => ['action_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'action_id' => 'Aksiyon',
            'reviewer_id' => 'Reviewer',
            'approver_id' => 'Approver',
            'review_note' => 'Gözden Geçirme Notu',
            'approved_at' => 'Onay Zamanı',
            'created_at' => 'Oluşturulma Tarihi',
        ];
    }

    public function getAction()
    {
        return $this->hasOne(Action::className(), ['id' => 'action_id']);
    }
}
