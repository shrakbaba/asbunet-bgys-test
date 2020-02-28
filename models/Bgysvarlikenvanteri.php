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
            [['varlik_adi', 'varlik_sahibi','aciklama'], 'string', 'max' => 255],
            [['departman'], 'exist', 'skipOnError' => true, 'targetClass' => Bgysdepartman::className(), 'targetAttribute' => ['departman' => 'id']],
            [['lokasyon'], 'exist', 'skipOnError' => true, 'targetClass' => Bgyslokasyon::className(), 'targetAttribute' => ['lokasyon' => 'id']],
            [['bilgi_sinifi'], 'exist', 'skipOnError' => true, 'targetClass' => Bgysbilgisinifi::className(), 'targetAttribute' => ['bilgi_sinifi' => 'id']],
            [['butunluk'], 'exist', 'skipOnError' => true, 'targetClass' => Bgyssiddettablosu::className(), 'targetAttribute' => ['butunluk' => 'id']],
            [['erisilebilirlik'], 'exist', 'skipOnError' => true, 'targetClass' => Bgyssiddettablosu::className(), 'targetAttribute' => ['erisilebilirlik' => 'id']],
            [['gizlilik'], 'exist', 'skipOnError' => true, 'targetClass' => Bgyssiddettablosu::className(), 'targetAttribute' => ['gizlilik' => 'id']],
            [['kategori'], 'exist', 'skipOnError' => true, 'targetClass' => Bgyskategori::className(), 'targetAttribute' => ['kategori' => 'id']],
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
