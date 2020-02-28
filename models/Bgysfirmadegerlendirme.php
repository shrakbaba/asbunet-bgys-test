<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "firma_degerlendirme".
 *
 * @property int $id
 * @property string $firmaid
 * @property string $degerlendiren
 * @property int $kriter1
 * @property int $kriter2
 * @property int $kriter3
 */
class Bgysfirmadegerlendirme extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bgys_firma_degerlendirme';
    }

    public $scenario;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['firmaid', 'degerlendiren', 'kriter1', 'kriter2', 'kriter3', 'kriter4', 'kriter5', 'kriter6', 'kriter7', 'kriter8', 'kriter9', 'kriter10','toplam','onay'], 'required'],
            [['firmaid', 'degerlendiren', 'kriter1', 'kriter2', 'kriter3', 'kriter4', 'kriter5', 'kriter6', 'kriter7', 'kriter8', 'kriter9', 'kriter10','toplam','onay'], 'integer'],
     [['not', 'degerlendirilenhizmet'], 'string', 'max' => 255],
                   [['degerlendirilenyil'], 'string', 'max' => 4],
                [['degerlendirmetarihi', 'guncellemetarihi'], 'safe'],
            [['firmaid'], 'exist', 'skipOnError' => true, 'targetClass' => Bgysfirmabilgi::className(), 'targetAttribute' => ['firmaid' => 'id']],
            //[['degerlendiren'], 'exist', 'skipOnError' => true, 'targetClass' => Userdb::className(), 'targetAttribute' => ['degerlendiren' => 'id']],
            (Yii::$app->params['giristipi']==1) 
            ? [['degerlendiren'], 'exist', 'skipOnError' => true, 'targetClass' =>\Edvlerblog\Adldap2\model\UserDbLdap::className() , 'targetAttribute' => ['degerlendiren' => 'id']] 
            : [['degerlendiren'], 'exist', 'skipOnError' => true, 'targetClass' => Userdb::className(), 'targetAttribute' => ['degerlendiren' => 'id']],
            ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'firmaid' => 'Firma',
            'degerlendiren' => 'Değerlendiren Personel',
            'kriter1' => 'ISO 27001 e sahip mi?',
            'kriter2' => 'Hizmet zamanında sağlanıyor mu?',
            'kriter3' => 'Ürün/Hizmet doğru ve eksiksiz teslim ediliyor mu?',
            'kriter4' => 'Servis kalitesinden memnun musunuz?',
            'kriter5' => 'Süreçte oluşan sorunlara kısa sürede ve etkin bir şekilde çözüm üretiliyor mu?',
            'kriter6' => 'Kadrosu işe göre yeterli ve yetkin mi?',
            'kriter7' => 'İş kapsamı dışında oluşabilecek yeni şartlara uyum sağlıyor mu?',
            'kriter8' => 'Kurum personeli ile iletişimi iyi mi',
            'kriter9' => 'Kurum süreçlerini iyileştirici önerilerde bulunuyor mu?',
            'kriter10' => 'Kurumdan gelen tüm şikayetleri değerlendiriyor mu?',
            'toplam' => 'Puanı',
            'onay' => 'Temsilci Onayı',
            'not'=> 'Not',
            'degerlendirilenhizmet'=>'Değerlendirilen Hizmet',
            'degerlendirilenyil'=>'Değerlendirilen Yıl',
            'degerlendirmetarihi'=> 'Değerlendirme Tarihi',
            'guncellemetarihi'=>'Guncelleme Tarihi',
        ];
    }

    public function getUser()
    {
        //return $this->hasOne(Userdb::className(), ['id' => 'degerlendiren']);
        return (Yii::$app->params['giristipi']==1) ?
            $this->hasOne(\Edvlerblog\Adldap2\model\UserDbLdap::className(), ['id' => 'degerlendiren'])
        :
            $this->hasOne(Userdb::className(), ['id' => 'degerlendiren']);
    }

    public function getFirmabilgi()
    {
        //return $this->hasOne(User::className(), ['id' => 'user_id']);
        return $this->hasOne(Bgysfirmabilgi::className(), ['id' => 'firmaid']);
    }
}
