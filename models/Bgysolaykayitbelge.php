<?php

namespace app\models;

use Yii;

class Bgysolaykayitbelge extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'bgys_olay_kayit_belge';
    }

    public function rules()
    {
        return [
            [['olay_id', 'dosya', 'orijinal_ad'], 'required'],
            [['olay_id', 'created_by'], 'integer'],
            [['created_at'], 'safe'],
            [['dosya', 'orijinal_ad'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'olay_id' => 'Olay',
            'dosya' => 'Dosya',
            'orijinal_ad' => 'Belge Adı',
            'created_at' => 'Eklenme Tarihi',
            'created_by' => 'Ekleyen',
        ];
    }

    public function getOlay()
    {
        return $this->hasOne(Bgysolaykayit::className(), ['id' => 'olay_id']);
    }
}
