<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\Expression;   

/**
 * This is the model class for table "bgys_risk".
 *
 * @property int $id
 * @property int $varlik
 * @property int $departman
 * @property string $risk
 * @property string $risk_nedeni
 * @property int $risk_sorumlusu
 * @property int $olasilik_onceki
 * @property int $gizlilik_onceki
 * @property int $butunluk_onceki
 * @property int $erisilebilirlik_onceki
 * @property int $riskdegeri_onceki
 * @property int $olasilik_sonraki
 * @property int $gizlilik_sonraki
 * @property int $butunluk_sonraki
 * @property int $erisilebilirlik_sonraki
 * @property int $riskdegeri_sonraki
 * @property string $yuksek_riskin_sebebi
 *
 * @property BgysSiddetTablosu $butunlukOnceki
 * @property BgysSiddetTablosu $butunlukSonraki
 * @property BgysDepartman $departman0
 * @property BgysSiddetTablosu $erisilebilirlikOnceki
 * @property BgysSiddetTablosu $erisilebilirlikSonraki
 * @property BgysSiddetTablosu $gizlilikOnceki
 * @property BgysSiddetTablosu $gizlilikSonraki
 * @property UserDb $riskSorumlusu
 * @property BgysVarlikEnvanteri $varlik0
 */
class Bgysrisk extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bgys_risk';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['varlik', 'departman', 'risk','olasilik_onceki', 'gizlilik_onceki', 'butunluk_onceki', 'erisilebilirlik_onceki'], 'required'],
            [['id','varlik', 'departman', 'risk_sorumlusu', 'olasilik_onceki', 'gizlilik_onceki', 'butunluk_onceki', 'erisilebilirlik_onceki', 'riskdegeri_onceki', 'olasilik_sonraki', 'gizlilik_sonraki', 'butunluk_sonraki', 'erisilebilirlik_sonraki', 'riskdegeri_sonraki','ozetdurum'], 'integer'],
            [['risk', 'risk_nedeni', 'yuksek_riskin_sebebi'], 'string', 'max' => 255],
            [['butunluk_onceki'], 'exist', 'skipOnError' => true, 'targetClass' => Bgyssiddettablosu::className(), 'targetAttribute' => ['butunluk_onceki' => 'id']],
            [['butunluk_sonraki'], 'exist', 'skipOnError' => true, 'targetClass' => Bgyssiddettablosu::className(), 'targetAttribute' => ['butunluk_sonraki' => 'id']],
            [['departman'], 'exist', 'skipOnError' => true, 'targetClass' => Bgysdepartman::className(), 'targetAttribute' => ['departman' => 'id']],
            [['erisilebilirlik_onceki'], 'exist', 'skipOnError' => true, 'targetClass' => Bgyssiddettablosu::className(), 'targetAttribute' => ['erisilebilirlik_onceki' => 'id']],
            [['erisilebilirlik_sonraki'], 'exist', 'skipOnError' => true, 'targetClass' => Bgyssiddettablosu::className(), 'targetAttribute' => ['erisilebilirlik_sonraki' => 'id']],
            [['gizlilik_onceki'], 'exist', 'skipOnError' => true, 'targetClass' => Bgyssiddettablosu::className(), 'targetAttribute' => ['gizlilik_onceki' => 'id']],
            [['gizlilik_sonraki'], 'exist', 'skipOnError' => true, 'targetClass' => Bgyssiddettablosu::className(), 'targetAttribute' => ['gizlilik_sonraki' => 'id']],
           // [['risk_sorumlusu'], 'exist', 'skipOnError' => true, 'targetClass' => Userdb::className(), 'targetAttribute' => ['risk_sorumlusu' => 'id']],
            (Yii::$app->params['giristipi']==1) 
            ? [['risk_sorumlusu'], 'exist', 'skipOnError' => true, 'targetClass' =>\Edvlerblog\Adldap2\model\UserDbLdap::className() , 'targetAttribute' => ['risk_sorumlusu' => 'id']] 
            : [['risk_sorumlusu'], 'exist', 'skipOnError' => true, 'targetClass' => Userdb::className(), 'targetAttribute' => ['risk_sorumlusu' => 'id']],
            [['varlik'], 'exist', 'skipOnError' => true, 'targetClass' => Bgysvarlikenvanteri::className(), 'targetAttribute' => ['varlik' => 'id']],
            [['olasilik_onceki'], 'exist', 'skipOnError' => true, 'targetClass' => Bgysolasilik::className(), 'targetAttribute' => ['olasilik_onceki' => 'id']],
            [['olasilik_sonraki'], 'exist', 'skipOnError' => true, 'targetClass' => Bgysolasilik::className(), 'targetAttribute' => ['olasilik_sonraki' => 'id']],
             [['updated_at'], 'safe'],
            [['pasif_aciklama'], 'safe'],
            [['updated_by'], 'integer'],
            [['pasif_aciklama'], 'string'],
            
        ];
    }

    
    public function behaviors()
    {
        return [
            // Sadece updated_at için TimestampBehavior
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => null,          
                'updatedAtAttribute' => 'updated_at', 
                'value' => new Expression('NOW()'),    
            // Güncelleyen kullanıcıyı otomatik yazmak için
            ],
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => null,
                'updatedByAttribute' => 'updated_by',
            ],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Risk No',
            'varlik' => 'Süreç / Varlik',
            'departman' => 'Departman',
            'risk' => 'Risk',
            'risk_nedeni' => 'Risk Nedeni',
            'risk_sorumlusu' => 'Risk Sorumlusu',
            'olasilik_onceki' => 'Önceki Olasılık',
            'gizlilik_onceki' => 'Önceki Gizlilik Şiddeti',
            'butunluk_onceki' => 'Önceki Bütünlük Şiddeti',
            'erisilebilirlik_onceki' => 'Önceki Erişilebilirlik Şiddeti',
            'riskdegeri_onceki' => 'Önceki Risk Değeri',
            'olasilik_sonraki' => 'Sonraki Olasılık',
            'gizlilik_sonraki' => 'Sonraki Gizlilik Şiddeti',
            'butunluk_sonraki' => 'Sonraki Bütünlük Şiddeti',
            'erisilebilirlik_sonraki' => 'Sonraki Erişilebilirlik Şiddeti',
            'riskdegeri_sonraki' => 'Sonraki Risk Değeri',
            'yuksek_riskin_sebebi' => 'Yüksek Riskin Sebebi',
            'ozetdurum'=>'Riskin Durumu',
            'updated_at' => 'Güncellenme Tarihi',
            'updated_by' => 'Güncelleyen Kullanıcı',
             'pasif_aciklama'  => 'Pasife Alma Açıklaması', 
        ];
    }

    
    public static function iliskiler(){

        $iliskilendirilmisler= Bgysdiftalep::find()->where(['!=','risk_iliskisi',""])->all();

        foreach ($iliskilendirilmisler as $key => $value) {
            $iliskiler[$value->dif_no]=json_decode($value->risk_iliskisi); //dif no suna göre ilişkili riskler           
        }    

        return json_encode($iliskiler);
    }
    

    public function getUpdatedByUser()
    {
        // Eğer LDAP aktif ise
        if (Yii::$app->params['giristipi'] == 1) {
            return $this->hasOne(\app\models\Userbilgi::className(), ['kisi_id' => 'updated_by']);
        }

        // Normal kullanıcı
        return $this->hasOne(\app\models\Userdb::className(), ['id' => 'updated_by']);
}






    public function getOlasilikOnceki()
    {
        return $this->hasOne(Bgysolasilik::className(), ['id' => 'olasilik_onceki']);
    }

    public function getOlasilikSonraki()
    {
        return $this->hasOne(Bgysolasilik::className(), ['id' => 'olasilik_sonraki']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getButunlukOnceki()
    {
        return $this->hasOne(Bgyssiddettablosu::className(), ['id' => 'butunluk_onceki']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getButunlukSonraki()
    {
        return $this->hasOne(Bgyssiddettablosu::className(), ['id' => 'butunluk_sonraki']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartman0()
    {
        return $this->hasOne(Bgysdepartman::className(), ['id' => 'departman']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getErisilebilirlikOnceki()
    {
        return $this->hasOne(Bgyssiddettablosu::className(), ['id' => 'erisilebilirlik_onceki']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getErisilebilirlikSonraki()
    {
        return $this->hasOne(Bgyssiddettablosu::className(), ['id' => 'erisilebilirlik_sonraki']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGizlilikOnceki()
    {
        return $this->hasOne(Bgyssiddettablosu::className(), ['id' => 'gizlilik_onceki']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGizlilikSonraki()
    {
        return $this->hasOne(Bgyssiddettablosu::className(), ['id' => 'gizlilik_sonraki']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRiskSorumlusu()
    {
        //return $this->hasOne(Userdb::className(), ['id' => 'risk_sorumlusu']);
        return (Yii::$app->params['giristipi']==1) ?
            $this->hasOne(\Edvlerblog\Adldap2\model\UserDbLdap::className(), ['id' => 'risk_sorumlusu'])
        :
            $this->hasOne(Userdb::className(), ['id' => 'risk_sorumlusu']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVarlik0()
    {
        return $this->hasOne(Bgysvarlikenvanteri::className(), ['id' => 'varlik']);
    }

    public function getRiskKabulleri()
    {
        return $this->hasMany(Bgysriskkabul::className(), ['riskid' => 'id']);
    }

}
