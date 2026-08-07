<?php

namespace app\models;

use yii\db\ActiveRecord;

class Envcihazzimmet extends ActiveRecord
{
    public static function tableName()
    {
        return 'env_cihaz_zimmet';
    }

    public function rules()
    {
        return [
            [['cihaz_id', 'user_id', 'teslim_tarihi'], 'required'],
            [['cihaz_id', 'user_id', 'teslim_eden_id', 'iade_alan_id'], 'integer'],
            [['teslim_tarihi', 'iade_tarihi', 'created_at'], 'safe'],
            [['aciklama'], 'string', 'max' => 500],
            [['cihaz_id'], 'exist', 'targetClass' => Envcihazliste::className(), 'targetAttribute' => ['cihaz_id' => 'id']],
            [['user_id'], 'exist', 'targetClass' => Userdb::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['teslim_eden_id'], 'exist', 'skipOnEmpty' => true, 'targetClass' => Userdb::className(), 'targetAttribute' => ['teslim_eden_id' => 'id']],
            [['iade_alan_id'], 'exist', 'skipOnEmpty' => true, 'targetClass' => Userdb::className(), 'targetAttribute' => ['iade_alan_id' => 'id']],
        ];
    }

    public function getCihaz()
    {
        return $this->hasOne(Envcihazliste::className(), ['id' => 'cihaz_id']);
    }

    public function getUser()
    {
        return $this->hasOne(Userdb::className(), ['id' => 'user_id']);
    }
}
