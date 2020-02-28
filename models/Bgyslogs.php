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

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['controller', 'action', 'userid'], 'required'],
           // [['userid'], 'integer'],
            //[['userid'], 'exist', 'skipOnError' => true, 'targetClass' => Userdb::className(), 'targetAttribute' => ['userid' => 'id']],
            (Yii::$app->params['giristipi']==1) 
            ? [['userid'], 'exist', 'skipOnError' => true, 'targetClass' =>\Edvlerblog\Adldap2\model\UserDbLdap::className() , 'targetAttribute' => ['userid' => 'id']] 
            : [['userid'], 'exist', 'skipOnError' => true, 'targetClass' => Userdb::className(), 'targetAttribute' => ['userid' => 'id']],
            [['date'], 'safe'],
            [['controller', 'action', 'not', 'islem'], 'string', 'max' => 255],
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
