<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bgys_varlik_envanteri".
 *
 * @property int $id
 * @property int $departman
 * @property string $varlik_adi
 * @property int $bilgi_sinifi
 * @property int $lokasyon
 * @property int $kategori
 * @property string $varlik_sahibi
 * @property int $gizlilik
 * @property int $butunluk
 * @property int $erisilebilirlik
 * @property int $varlik_degeri
 *
 * @property BgysDepartman $departman0
 * @property BgysLokasyon $lokasyon0
 * @property BgysBilgiSinifi $bilgiSinifi
 * @property BgysSiddetTablosu $butunluk0
 * @property BgysSiddetTablosu $erisilebilirlik0
 * @property BgysSiddetTablosu $gizlilik0
 * @property BgysKategori $kategori0
 */
class Bgysvarlikenvanteri extends \yii\db\ActiveRecord
{
    public const OWNER_TYPE_UNIT = 'unit';
    public const OWNER_TYPE_USER = 'user';
    public const OWNER_SYSTEM_NETWORK_SUPPORT = 'Sistem, Ağ ve Teknik Destek Şube Müdürlüğü';
    public const OWNER_SOFTWARE = 'Yazılım Şube Müdürlüğü';
    public const OWNER_ADMINISTRATIVE_AFFAIRS = 'İdari İşler Şube Müdürlüğü';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bgys_varlik_envanteri';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['departman', 'bilgi_sinifi', 'lokasyon', 'kategori', 'gizlilik', 'butunluk', 'erisilebilirlik', 'varlik_degeri'], 'integer'],
            [['owner_user_id', 'created_by'], 'integer'],
            [['varlik_adi', 'varlik_sahibi','aciklama'], 'string', 'max' => 255],
            [['owner_type'], 'in', 'range' => [self::OWNER_TYPE_UNIT, self::OWNER_TYPE_USER]],
            [['owner_unit'], 'in', 'range' => array_keys(self::unitOptions()), 'skipOnEmpty' => true],
            [['owner_user_id'], 'exist', 'skipOnEmpty' => true, 'targetClass' => Userbilgi::className(), 'targetAttribute' => ['owner_user_id' => 'kisi_id']],
            [['owner_type'], 'validateOwnerSelection'],
            [['departman'], 'exist', 'skipOnError' => true, 'targetClass' => Bgysdepartman::className(), 'targetAttribute' => ['departman' => 'id']],
            [['lokasyon'], 'exist', 'skipOnError' => true, 'targetClass' => Bgyslokasyon::className(), 'targetAttribute' => ['lokasyon' => 'id']],
            [['bilgi_sinifi'], 'exist', 'skipOnError' => true, 'targetClass' => Bgysbilgisinifi::className(), 'targetAttribute' => ['bilgi_sinifi' => 'id']],
            [['butunluk'], 'exist', 'skipOnError' => true, 'targetClass' => Bgyssiddettablosu::className(), 'targetAttribute' => ['butunluk' => 'id']],
            [['erisilebilirlik'], 'exist', 'skipOnError' => true, 'targetClass' => Bgyssiddettablosu::className(), 'targetAttribute' => ['erisilebilirlik' => 'id']],
            [['gizlilik'], 'exist', 'skipOnError' => true, 'targetClass' => Bgyssiddettablosu::className(), 'targetAttribute' => ['gizlilik' => 'id']],
            [['kategori'], 'exist', 'skipOnError' => true, 'targetClass' => Bgyskategori::className(), 'targetAttribute' => ['kategori' => 'id']],
        ];
    }

    public function validateOwnerSelection($attribute)
    {
        if (!$this->owner_type) {
            if ($this->isNewRecord) {
                $this->addError($attribute, 'Varlık sahibi türü seçilmelidir.');
            }
            return;
        }

        if ($this->owner_type === self::OWNER_TYPE_UNIT) {
            if (!isset(self::unitOptions()[$this->owner_unit])) {
                $this->addError('owner_unit', 'Şube müdürlüğü seçilmelidir.');
                return;
            }
            $this->owner_user_id = null;
            $this->varlik_sahibi = $this->owner_unit;
            return;
        }

        if (!$this->owner_user_id) {
            $this->addError('owner_user_id', 'Kullanıcı seçilmelidir.');
            return;
        }

        $this->owner_unit = null;
        $userInfo = Userbilgi::find()->where(['kisi_id' => (int)$this->owner_user_id])->one();
        if ($userInfo !== null) {
            $this->varlik_sahibi = trim($userInfo->ad . ' ' . $userInfo->soyad);
        }
    }

    public static function ownerTypeOptions()
    {
        return [
            self::OWNER_TYPE_UNIT => 'Şube Müdürlüğü',
            self::OWNER_TYPE_USER => 'Kullanıcı',
        ];
    }

    public static function unitOptions()
    {
        return [
            self::OWNER_SYSTEM_NETWORK_SUPPORT => self::OWNER_SYSTEM_NETWORK_SUPPORT,
            self::OWNER_SOFTWARE => self::OWNER_SOFTWARE,
            self::OWNER_ADMINISTRATIVE_AFFAIRS => self::OWNER_ADMINISTRATIVE_AFFAIRS,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'departman' => 'Departman',
            'varlik_adi' => 'Varlik Adı',
            'bilgi_sinifi' => 'Bilgi Sınıfı',
            'lokasyon' => 'Lokasyon',
            'kategori' => 'Kategori',
            'varlik_sahibi' => 'Varlık Sorumlusu',
            'owner_type' => 'Varlık Sahibi Türü',
            'owner_unit' => 'Şube Müdürlüğü',
            'owner_user_id' => 'Kullanıcı',
            'created_by' => 'Oluşturan Kullanıcı',
            'gizlilik' => 'Gizlilik',
            'butunluk' => 'Bütünlük',
            'erisilebilirlik' => 'Erişilebilirlik',
            'varlik_degeri' => 'Varlık Değeri',
            'aciklama' => 'Açıklama',
        ];
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
    public function getLokasyon0()
    {
        return $this->hasOne(Bgyslokasyon::className(), ['id' => 'lokasyon']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBilgiSinifi()
    {
        return $this->hasOne(Bgysbilgisinifi::className(), ['id' => 'bilgi_sinifi']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getButunluk0()
    {
        return $this->hasOne(Bgyssiddettablosu::className(), ['id' => 'butunluk']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getErisilebilirlik0()
    {
        return $this->hasOne(Bgyssiddettablosu::className(), ['id' => 'erisilebilirlik']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGizlilik0()
    {
        return $this->hasOne(Bgyssiddettablosu::className(), ['id' => 'gizlilik']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKategori0()
    {
        return $this->hasOne(Bgyskategori::className(), ['id' => 'kategori']);
    }
}
