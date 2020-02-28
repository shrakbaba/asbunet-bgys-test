<?php

namespace app\models;

use Yii;

class Userbilgi extends \yii\db\ActiveRecord
{
    
    public static function tableName()
    {
        return 'user_bilgi';
    }

    public function rules()
    {
        return [
            [['ad', 'soyad', 'email', 'kisi_id'], 'required'],
            [['kisi_id'], 'integer'],
            [['ad', 'soyad', 'email', 'adres'], 'string', 'max' => 255],
            [['tc'], 'string', 'max' => 11],
            [['telefon'], 'string', 'max' => 15],
            [['dogumyili'], 'string', 'max' => 4],
            [['email'], 'unique'],
            //[['tc'], 'unique'],
            //[['telefon'], 'unique'],
            [['kisi_id'], 'exist', 'skipOnError' => true, 'targetClass' => \Edvlerblog\Adldap2\model\UserDbLdap::className()::className(), 'targetAttribute' => ['kisi_id' => 'id']],
            /*(Yii::$app->params['giristipi']==1) 
            ? [['kisi_id'], 'exist', 'skipOnError' => true, 'targetClass' =>\Edvlerblog\Adldap2\model\UserDbLdap::className() , 'targetAttribute' => ['kisi_id' => 'id']] 
            : [['kisi_id'], 'exist', 'skipOnError' => true, 'targetClass' => Userdb::className(), 'targetAttribute' => ['kisi_id' => 'id']],*/
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ad' => 'Ad',
            'soyad' => 'Soyad',
            'email' => 'Email',
            'tc' => 'Tc',
            'telefon' => 'Telefon',
            'adres' => 'Adres',
            'dogumyili' => 'Dogumyili',
            'kisi_id' => 'Kisi ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKisi()
    {
        //return $this->hasOne(\Edvlerblog\Adldap2\model\UserDbLdap::className()::className(), ['id' => 'kisi_id']);
        return (Yii::$app->params['giristipi']==1) ?
            $this->hasOne(\Edvlerblog\Adldap2\model\UserDbLdap::className(), ['id' => 'kisi_id'])
        :
            $this->hasOne(Userdb::className(), ['id' => 'kisi_id']);

    }
}
