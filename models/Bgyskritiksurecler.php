<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bgys_kritiksurecler".
 *
 * @property int $id
 * @property string $surec
 * @property int $keks
 * @property int $kevk
 * @property string $etkisi
 * @property string $ilkaksiyon
 * @property string $yedeklilik
 * @property string $ulasilacaklar
 * @property string $ekleme_tarihi
 */
class Bgyskritiksurecler extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bgys_kritiksurecler';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['surec'], 'required'],
            [['keks', 'kevk'], 'integer'],
            [['ekleme_tarihi'], 'safe'],
            [['surec'], 'string', 'max' => 255],
            [['etkisi', 'ilkaksiyon', 'yedeklilik'], 'string', 'max' => 500],
            [['ulasilacaklar'], 'string', 'max' => 1000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'surec' => 'Süreç',
            'keks' => 'Keks (Kabul Edilebilir Kesinti Süresi) Saat',
            'kevk' => 'Kevk (Kabul Edilebilir Veri Kaybı) Saat',
            'etkisi' => 'Etkisi',
            'ilkaksiyon' => 'İlk Aksiyon',
            'yedeklilik' => 'Yedeklilik Durumu',
            'ulasilacaklar' => 'Ulaşılacak Kişi ya da Birimler',
            'ekleme_tarihi' => 'Ekleme Tarihi',
        ];
    }
}
