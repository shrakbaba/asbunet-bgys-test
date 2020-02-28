<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bgys_izlemeolcme".
 *
 * @property int $id
 * @property int $yil
 * @property string $kontrol
 * @property int $hedef_degeri
 * @property int $olcum_sikligi
 * @property string $planlanan_tarihi
 * @property string $olcum_sonucu
 * @property string $kontrol_kriteri
 * @property int $sorumlu
 * @property string $olusturma_tarihi
 *
 * @property User $sorumlu0
 */
class Bgysizlemeolcme extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bgys_izlemeolcme';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['yil', 'kontrol', 'hedef_degeri', 'sorumlu'], 'required'],
            [['yil', 'hedef_degeri', 'olcum_sikligi', 'sorumlu'], 'integer'],
            [['planlanan_tarihi', 'olusturma_tarihi'], 'safe'],
            [['kontrol', 'kontrol_kriteri'], 'string', 'max' => 255],
            //[['olcum_sonucu','metrikler'], 'string', 'max' => 500],
            [['metrikler'], 'string', 'max' => 500],
            [['sorumlu'], 'exist', 'skipOnError' => true, 'targetClass' => \Edvlerblog\Adldap2\model\UserDbLdap::className()::className(), 'targetAttribute' => ['sorumlu' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'yil' => 'Yil',
            'kontrol' => 'Kontrol',
            'hedef_degeri' => 'Hedef Değeri(adet ya da %)',
            'olcum_sikligi' => 'Ölçüm Sıklığı',
            'planlanan_tarihi' => 'İlk Planlanan Tarih',
            //'olcum_sonucu' => 'Ölçüm Sonucu',
            'kontrol_kriteri' => 'Kontrol Kriteri',
            'sorumlu' => 'Sorumlu',
            'olusturma_tarihi' => 'Olusturma Tarihi',
            'metrikler'=>'Mertikler'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSorumlu0()
    {
        //return $this->hasOne(User::className(), ['id' => 'sorumlu']);
        return $this->hasOne(\Edvlerblog\Adldap2\model\UserDbLdap::className(), ['id' => 'sorumlu']);
    }
}
