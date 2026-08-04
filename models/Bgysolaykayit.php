<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "olay_kayit".
 *
 * @property int $id
 * @property int $userid
 * @property string $konu
 * @property string $olaytarihi
 * @property string $mudahaleeden
 * @property string $yapilanmudahale
 * @property string $mudahaletarihi
 * @property string $sonuc
 */
class Bgysolaykayit extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bgys_olay_kayit';
    }
  public $file;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userid'], 'required'],
            [['userid'], 'integer'],
            [['olaytarihi', 'mudahaletarihi'], 'safe'],
            [['konu', 'mudahaleeden', 'belge'], 'string', 'max' => 255],
            [['yapilanmudahale', 'sonuc', 'onlem'], 'string', 'max' => 1500],
            [['file'],'file','skipOnEmpty'=>true,'extensions'=>'pdf','mimeTypes'=>['application/pdf'],'maxSize' => 1024 * 1024 * 1,'maxFiles' => 10,'tooBig' => '"{file}" dosyası çok büyük. Boyutu 1 MB değerinden büyük olamaz.'],  //max 1 MB
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userid' => 'Kayıt Eden',
            'konu' => 'Konu',
            'olaytarihi' => 'Olay Tarihi',
            'mudahaleeden' => 'Müdahale Eden',
            'yapilanmudahale' => 'Yapılan Mudahale',
            'mudahaletarihi' => 'Müdahale Tarihi',
            'sonuc' => 'Sonuç',
            'onlem'=>'Olay önlemek için alınan aksiyon',
            'belge'=>'Olayla ilgili dokuman',
            'file' =>'Olayla ilgili dokuman (PDF)',
        ];
    }

    public function getUser()
    {
        //return $this->hasOne(User::className(), ['id' => 'user_id']);
        //return $this->hasOne(Userdb::className(), ['id' => 'userid']);
        return (Yii::$app->params['giristipi']==1) ?
            $this->hasOne(\Edvlerblog\Adldap2\model\UserDbLdap::className(), ['id' => 'userid'])
        :
            $this->hasOne(Userdb::className(), ['id' => 'userid']);
    }

    public function getBelgeler()
    {
        return $this->hasMany(Bgysolaykayitbelge::className(), ['olay_id' => 'id']);
    }
}
