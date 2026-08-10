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
            [['adet'], 'required', 'when' => function ($model) {
                return !$model->isSoftwareType();
            }, 'whenClient' => "function () { return false; }"],
            [['license_type', 'hosting_environment', 'lifecycle_status'], 'required', 'when' => function ($model) {
                return $model->isNewRecord && $model->isSoftwareType();
            }, 'whenClient' => "function () { return false; }"],
            [['cihaz_turu_id', 'marka_id', 'model_id','duyuru6','duyuru3','duyuru1','adet','zimmet','bgys_asset_id','created_by'], 'integer'],
            [['is_legacy'], 'boolean'],
            [['license_quantity'], 'integer', 'min' => 1],
            [['license_start_date', 'license_end_date'], 'validateIsoDate'],
            [['license_type'], 'in', 'range' => array_keys(self::licenseTypeOptions())],
            [['hosting_environment'], 'in', 'range' => array_keys(self::hostingEnvironmentOptions())],
            [['lifecycle_status'], 'in', 'range' => array_keys(self::lifecycleStatusOptions())],
            [['hosting_detail', 'supplier_name'], 'string', 'max' => 255],
            [['license_end_date'], 'validateLicenseDates'],
            [['bgys_asset_id'], 'required', 'when' => function ($model) {
                return $model->isNewRecord;
            }, 'whenClient' => "function () { return false; }"],
            [['alim_tarihi','garanti_bitis','file'], 'safe'],
            [['konum','key','service_tag','dosya','link','ozet'], 'string', 'max' => 255],
            [['cihaz_turu_id'], 'exist', 'skipOnError' => true, 'targetClass' => Envcihazturu::className(), 'targetAttribute' => ['cihaz_turu_id' => 'id']],
            [['marka_id'], 'exist', 'skipOnError' => true, 'targetClass' => Envmarka::className(), 'targetAttribute' => ['marka_id' => 'id']],
            [['model_id'], 'exist', 'skipOnError' => true, 'targetClass' => Envmodel::className(), 'targetAttribute' => ['model_id' => 'id']],
            [['model_id'], 'validateCatalogRelations'],
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
            'is_legacy' => 'Aktarılan Eski Kayıt',
            'license_type' => 'Lisans Türü',
            'license_quantity' => 'Lisans / Kullanıcı Adedi',
            'license_start_date' => 'Lisans Başlangıç Tarihi',
            'license_end_date' => 'Lisans Bitiş Tarihi',
            'hosting_environment' => 'Barındırma Ortamı',
            'hosting_detail' => 'Barındırma Detayı',
            'supplier_name' => 'Tedarikçi',
            'lifecycle_status' => 'Yaşam Döngüsü Durumu',
            'cihaz_turu_id' => 'Cihaz Türü',
            'marka_id' => 'Marka / Üretici',
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
        $deviceType = Envcihazturu::findOne((int)$this->cihaz_turu_id);
        if ($deviceType === null || !$deviceType->asset_type) {
            $this->addError('cihaz_turu_id', 'Cihaz türünün BGYS varlık sınıfı önce Cihaz Türleri ekranından belirlenmelidir.');
            return;
        }
        if ($asset === null || $asset->asset_type !== $deviceType->asset_type) {
            $this->addError($attribute, 'BGYS varlığı, seçilen cihaz türünün varlık sınıfıyla uyumlu olmalıdır.');
        }
    }

    public function validateCatalogRelations($attribute)
    {
        if (!$this->cihaz_turu_id || !$this->marka_id || !$this->model_id) {
            return;
        }
        $brandLinked = (new \yii\db\Query())->from('env_cihaz_turu_marka')->where([
            'cihaz_turu_id' => (int)$this->cihaz_turu_id, 'marka_id' => (int)$this->marka_id,
        ])->exists();
        $modelLinked = (new \yii\db\Query())->from('env_cihaz_turu_model')->where([
            'cihaz_turu_id' => (int)$this->cihaz_turu_id, 'model_id' => (int)$this->model_id,
        ])->exists();
        $model = Envmodel::findOne((int)$this->model_id);
        if (!$brandLinked || !$modelLinked || $model === null || (int)$model->marka_id !== (int)$this->marka_id) {
            $this->addError($attribute, 'Cihaz türü, marka ve model seçimi birbiriyle uyumlu olmalıdır.');
        }
    }

    public function getZimmetHistory()
    {
        return $this->hasMany(Envcihazzimmet::className(), ['cihaz_id' => 'id'])
            ->orderBy(['teslim_tarihi' => SORT_DESC, 'id' => SORT_DESC]);
    }

    public static function licenseTypeOptions()
    {
        return [
            'named_user' => 'Kullanıcı Bazlı', 'concurrent' => 'Eş Zamanlı Kullanıcı',
            'device' => 'Cihaz Bazlı', 'site' => 'Kurumsal / Site', 'subscription' => 'Abonelik',
            'perpetual' => 'Süresiz', 'open_source' => 'Açık Kaynak', 'other' => 'Diğer',
        ];
    }

    public static function hostingEnvironmentOptions()
    {
        return [
            'on_premise' => 'Kurum İçi', 'cloud' => 'Bulut', 'hybrid' => 'Hibrit',
            'saas' => 'Hizmet Olarak Yazılım (SaaS)', 'not_applicable' => 'Uygulanamaz',
        ];
    }

    public static function lifecycleStatusOptions()
    {
        return [
            'active' => 'Aktif', 'renewal_due' => 'Yenileme Bekliyor',
            'expired' => 'Süresi Dolmuş', 'retired' => 'Kullanım Dışı',
        ];
    }

    public function isSoftwareType()
    {
        $deviceType = $this->cihazTuru ?: Envcihazturu::findOne((int)$this->cihaz_turu_id);
        return $deviceType !== null && $deviceType->asset_type === Bgysvarlikenvanteri::TYPE_SOFTWARE;
    }

    public function validateLicenseDates($attribute)
    {
        if ($this->license_start_date && $this->license_end_date
            && $this->license_end_date < $this->license_start_date) {
            $this->addError($attribute, 'Lisans bitiş tarihi başlangıç tarihinden önce olamaz.');
        }
    }

    public function validateIsoDate($attribute)
    {
        if (!$this->$attribute) {
            return;
        }
        $parts = explode('-', $this->$attribute);
        if (count($parts) !== 3 || !ctype_digit(implode('', $parts))
            || !checkdate((int)$parts[1], (int)$parts[2], (int)$parts[0])) {
            $this->addError($attribute, 'Tarih YYYY-AA-GG biçiminde ve geçerli olmalıdır.');
        }
    }
}
