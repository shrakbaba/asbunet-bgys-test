<?php

namespace app\models;

use Yii;

use yii\web\UploadedFile;
/**
 * This is the model class for table "env_cihaz_liste".
 *
 * @property int $id
 * @property int $cihaz_turu_id
 * @property int $marka_id
 * @property int $model_id
 * @property string $adet
 * @property string $alim_tarihi
 * @property string $garanti_bitis
 *
 * @property EnvCihazTuru $cihazTuru
 * @property EnvMarka $marka
 * @property EnvModel $model
 */
class Envcihazliste extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'env_cihaz_liste';
    }
    public $file;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cihaz_turu_id', 'marka_id', 'model_id','alim_tarihi','garanti_bitis'], 'required'],
            [['cihaz_turu_id', 'marka_id', 'model_id','duyuru6','duyuru3','duyuru1','adet','zimmet','bgys_asset_id','created_by'], 'integer'],
            [['alim_tarihi','garanti_bitis','file'], 'safe'],
            [['konum','key','service_tag','dosya','link','ozet'], 'string', 'max' => 255],
            [['cihaz_turu_id'], 'exist', 'skipOnError' => true, 'targetClass' => Envcihazturu::className(), 'targetAttribute' => ['cihaz_turu_id' => 'id']],
            [['marka_id'], 'exist', 'skipOnError' => true, 'targetClass' => Envmarka::className(), 'targetAttribute' => ['marka_id' => 'id']],
            [['model_id'], 'exist', 'skipOnError' => true, 'targetClass' => Envmodel::className(), 'targetAttribute' => ['model_id' => 'id']],
            [['bgys_asset_id'], 'exist', 'skipOnEmpty' => true, 'targetClass' => Bgysvarlikenvanteri::className(), 'targetAttribute' => ['bgys_asset_id' => 'id']],
            [['bgys_asset_id'], 'validateBgysAssetCategory'],
            //[['zimmet'], 'exist', 'skipOnError' => true, 'targetClass' => Userdb::className(), 'targetAttribute' => ['zimmet' => 'id']],
            (Yii::$app->params['giristipi']==1) 
            ? [['zimmet'], 'exist', 'skipOnError' => true, 'targetClass' =>\Edvlerblog\Adldap2\model\UserDbLdap::className() , 'targetAttribute' => ['zimmet' => 'id']]
            : [['zimmet'], 'exist', 'skipOnError' => true, 'targetClass' => Userdb::className(), 'targetAttribute' => ['zimmet' => 'id']],
            [['file'],'file','skipOnEmpty'=>true,'extensions'=>'pdf','mimeTypes'=>['application/pdf'],'maxSize' => 1024 * 1024 * 1],  //max 1Mb
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bgys_asset_id' => 'BGYS Varlığı',
            'created_by' => 'Oluşturan Kullanıcı',
            'cihaz_turu_id' => 'Cihaz Türü',
            'marka_id' => 'Marka',
            'model_id' => 'Model',
            'adet' => 'Adet',
            'alim_tarihi' => 'Alım Tarihi',
            'garanti_bitis' => 'Garanti Bitişi',
            'duyuru6'=>'6 Aylık Duyuru',
            'duyuru3'=>'3 Aylık Duyuru',
            'duyuru1'=>'1 Aylık Duyuru',
            'key'=>'Ürün Anahtarı',
            'service_tag'=>'Servis Numarası',
            'konum'=>'Konumu',
            'link'=>'Cihaz Linki',
            'ozet'=>'Özet Bilgi',
            'file'=>'Alım Belgesi (pdf)',
            'zimmet' => 'Zimmetli Kullanıcı',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCihazTuru()
    {
        return $this->hasOne(Envcihazturu::className(), ['id' => 'cihaz_turu_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMarka()
    {
        return $this->hasOne(Envmarka::className(), ['id' => 'marka_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModel()
    {
        return $this->hasOne(Envmodel::className(), ['id' => 'model_id']);
    }

    public function getZimmet0()
    {
        //return $this->hasOne(Userdb::className(), ['id' => 'zimmet']);
        return (Yii::$app->params['giristipi']==1) ?
            $this->hasOne(\Edvlerblog\Adldap2\model\UserDbLdap::className(), ['id' => 'zimmet'])
        :
            $this->hasOne(Userdb::className(), ['id' => 'zimmet']);

    }

    public function getBgysAsset()
    {
        return $this->hasOne(Bgysvarlikenvanteri::className(), ['id' => 'bgys_asset_id']);
    }

    public function validateBgysAssetCategory($attribute)
    {
        if (!$this->$attribute) {
            return;
        }

        $asset = Bgysvarlikenvanteri::findOne((int)$this->$attribute);
        if ($asset === null || $asset->asset_type !== Bgysvarlikenvanteri::TYPE_HARDWARE) {
            $this->addError($attribute, 'Cihaz yalnız donanım niteliğindeki bir BGYS varlığına bağlanabilir.');
        }
    }

    public function getZimmetHistory()
    {
        return $this->hasMany(Envcihazzimmet::className(), ['cihaz_id' => 'id'])
            ->orderBy(['teslim_tarihi' => SORT_DESC, 'id' => SORT_DESC]);
    }
}
