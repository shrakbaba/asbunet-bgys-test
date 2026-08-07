<?php

namespace app\models;

use app\behaviors\AuditLogBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

class Evidence extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'evidences';
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
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => null,
            ],
            [
                'class' => AuditLogBehavior::class,
            ],
        ];
    }

    public function rules()
    {
        return [
            [['action_id', 'evidence_type'], 'required'],
            [['action_id', 'created_by'], 'integer'],
            [['note'], 'string'],
            [['created_at'], 'safe'],
            [['evidence_type'], 'string', 'max' => 100],
            [['file_path'], 'string', 'max' => 255],
            [['action_id'], 'exist', 'skipOnError' => true, 'targetClass' => Action::className(), 'targetAttribute' => ['action_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'action_id' => 'Aksiyon',
            'evidence_type' => 'Kanıt Tipi',
            'file_path' => 'Dosya Yolu',
            'note' => 'Not',
            'created_at' => 'Eklenme Tarihi',
            'created_by' => 'Ekleyen',
        ];
    }

    public function getAction()
    {
        return $this->hasOne(Action::className(), ['id' => 'action_id']);
    }
}
