<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bgys_logs".
 *
 * @property int $id
 * @property string $controller
 * @property string $action
 * @property int $userid
 * @property string $date
 * @property string $not
 * @property string $islem
 */
class Bgyslogs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bgys_logs';
    }

    public function beforeSave($insert)
    {
        return $insert && parent::beforeSave($insert);
    }

    public function beforeDelete()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['controller', 'action'], 'required'],
            [['userid'], 'integer'],
           // [['userid'], 'integer'],
            //[['userid'], 'exist', 'skipOnError' => true, 'targetClass' => Userdb::className(), 'targetAttribute' => ['userid' => 'id']],
            (Yii::$app->params['giristipi']==1) 
            ? [['userid'], 'exist', 'skipOnError' => true, 'targetClass' =>\Edvlerblog\Adldap2\model\UserDbLdap::className() , 'targetAttribute' => ['userid' => 'id']] 
            : [['userid'], 'exist', 'skipOnError' => true, 'targetClass' => Userdb::className(), 'targetAttribute' => ['userid' => 'id']],
            [['date', 'old_values', 'new_values'], 'safe'],
            [['controller', 'action', 'not', 'islem', 'actor', 'role'], 'string', 'max' => 255],
            [['ip_address'], 'string', 'max' => 45],
            [['user_agent'], 'string', 'max' => 512],
            [['correlation_id'], 'string', 'max' => 64],
            [['result'], 'in', 'range' => ['success', 'failure']],
            [['record_type', 'record_id'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'controller' => 'Controller',
            'action' => 'Action',
            'userid' => 'Hareket Sahibi',
            'date' => 'Tarih',
            'not' => 'Not',
            'islem' => 'Islem',
            'actor' => 'Kullanıcı',
            'role' => 'Rol',
            'ip_address' => 'IP Adresi',
            'user_agent' => 'User-Agent',
            'correlation_id' => 'İlişki Kimliği',
            'result' => 'Sonuç',
            'record_type' => 'Kayıt Türü',
            'record_id' => 'Kayıt ID',
            'old_values' => 'Önceki Değerler',
            'new_values' => 'Yeni Değerler',
        ];
    }

    public function getLogyapan()
    {
        //return $this->hasOne(Userdb::className(), ['id' => 'userid']);
        return (Yii::$app->params['giristipi']==1) ?
            $this->hasOne(\Edvlerblog\Adldap2\model\UserDbLdap::className(), ['id' => 'userid'])
        :
            $this->hasOne(Userdb::className(), ['id' => 'userid']);
    }
}
