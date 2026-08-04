<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "auth_assignment".
 *
 * @property string $item_name
 * @property string $user_id
 * @property int $created_at
 *
 * @property Authitem $itemName
 */
class Authassignment extends \yii\db\ActiveRecord
{
    public static function aktifRolListesi()
    {
        return [
            'BGYS_Ekip_Lideri' => 'BGYS_Ekip_Lideri',
            'BGYS_Ekip_Uyesi' => 'BGYS_Ekip_Uyesi',
            'BGYS_Yonetim_Temsilcisi' => 'BGYS_Yonetim_Temsilcisi',
            'BGYS_Super_Admin' => 'BGYS_Super_Admin',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auth_assignment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['item_name', 'user_id'], 'required'],
            [['created_at'], 'integer'],
            [['item_name', 'user_id'], 'string', 'max' => 64],
            [['item_name'], 'in', 'range' => array_keys(self::aktifRolListesi())],
            [['item_name', 'user_id'], 'unique', 'targetAttribute' => ['item_name', 'user_id']],
            [['item_name'], 'exist', 'skipOnError' => true, 'targetClass' => Authitem::className(), 'targetAttribute' => ['item_name' => 'name']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'item_name' => 'Roller',
            'user_id' => 'Kullanıcı',
            'created_at' => 'Oluşturma',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemName()
    {
        return $this->hasOne(Authitem::className(), ['name' => 'item_name']);
    }

    public function getUser()
    {
        //return $this->hasOne(User::className(), ['id' => 'user_id']);
        return  (Yii::$app->params['giristipi']==1) ? $this->hasOne(\Edvlerblog\Adldap2\model\UserDbLdap::className(), ['id' => 'user_id']) :$this->hasOne(Userdb::className(), ['id' => 'user_id']);

         
    }
}
