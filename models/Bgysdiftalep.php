<?php

namespace app\models;

use Yii;
use app\models\User;
use app\models\Bgysdiftakip;

/**
 * This is the model class for table "bgys_dif_talep".
 *
 * @property int $id
 * @property string $dif_no
 * @property string $talep_tarihi
 * @property string $talep_eden
 * @property string $dif_konusu
 * @property int $durum
 * @property string $planlanan_tarih
 */
class Bgysdiftalep extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bgys_dif_talep';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
             [['olusturan_kisi', 'dif_no', 'talep_eden', 'dif_konusu'], 'required'],
                   [['olusturan_kisi', 'durum', 'sorumlu', 'dif_no'], 'integer'],
                   [['talep_tarihi', 'planlanan_tarih'], 'safe'],
                   [['talep_eden'], 'string', 'max' => 255],
                   [['dif_konusu'], 'string', 'max' => 1500],
                   //[['risk_iliskisi'], 'string', 'max' => 1000],
                   [['dif_no'], 'unique'],
            //[['olusturan_kisi'], 'exist', 'skipOnError' => true, 'targetClass' => Userdb::className(), 'targetAttribute' => ['olusturan_kisi' => 'id']],
            //[['sorumlu'], 'exist', 'skipOnError' => true, 'targetClass' => Userdb::className(), 'targetAttribute' => ['sorumlu' => 'id']],
            (Yii::$app->params['giristipi']==1) 
            ? [['sorumlu'], 'exist', 'skipOnError' => true, 'targetClass' =>\Edvlerblog\Adldap2\model\UserDbLdap::className() , 'targetAttribute' => ['sorumlu' => 'id']] 
            : [['sorumlu'], 'exist', 'skipOnError' => true, 'targetClass' => Userdb::className(), 'targetAttribute' => ['sorumlu' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dif_no' => 'Dif No',
            'talep_tarihi' => 'Talep Tarihi',
            'talep_eden' => 'Talep Eden',
            'dif_konusu' => 'Dif Konusu',
            'risk_iliskisi' => 'İlişkili olduğu riskler',
            'durum' => 'Durum',
            'planlanan_tarih' => 'Planlanan Tarih',
            'olusturan_kisi'=>'Dif Kaydını Oluşturan',
            'sorumlu' => 'Dif Sorumlusu',
        ];
    }

    public function getBgysDifTakips() 
           { 
               return $this->hasMany(Bgysdiftakip::className(), ['diftalep_id' => 'id']); 
           } 

    public function getDifsorumlusu()
    {
        //return $this->hasOne(Userdb::className(), ['id' => 'olusturan_kisi']);
        return (Yii::$app->params['giristipi']==1) ?
            $this->hasOne(\Edvlerblog\Adldap2\model\UserDbLdap::className(), ['id' => 'olusturan_kisi'])
        :
            $this->hasOne(Userdb::className(), ['id' => 'olusturan_kisi']);
    }      

    public function getSorumlu0()
    {  
        //return $this->hasOne(Userdb::className(), ['id' => 'sorumlu']);
        return (Yii::$app->params['giristipi']==1) ?
            $this->hasOne(\Edvlerblog\Adldap2\model\UserDbLdap::className(), ['id' => 'sorumlu'])
        :
            $this->hasOne(Userdb::className(), ['id' => 'sorumlu']);
    }
}
