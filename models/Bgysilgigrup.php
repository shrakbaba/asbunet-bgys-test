<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bgys_ilgigrup".
 *
 * @property int $id
 * @property string $grupadi
 * @property string $iletisimbirimi
 * @property string $telefon
 * @property string $grup_web
 * @property string $ilgi_konusu
 * @property string $iletisimegecme_durumu
 * @property string $etkilenecek_surecler
 * @property string $ekleme_tarihi
 */
class Bgysilgigrup extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bgys_ilgigrup';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['grupadi'], 'required'],
            [['ekleme_tarihi'], 'safe'],
            [['grupadi', 'iletisimbirimi', 'telefon', 'grup_web', 'ilgi_konusu'], 'string', 'max' => 255],
            [['iletisimegecme_durumu', 'etkilenecek_surecler'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'grupadi' => 'İlgi Grubu veya Otorite Adı',
            'iletisimbirimi' => 'İletişim Kuracak Birim',
            'telefon' => 'Grup ya da Otorite Telefonu',
            'grup_web' => 'Grup ya da Otorite  Web',
            'ilgi_konusu' => 'İlgi Konusu',
            'iletisimegecme_durumu' => 'İletişime Geçilecek Durum',
            'etkilenecek_surecler' => 'Etkilenecek Süreçler',
            'ekleme_tarihi' => 'Ekleme Tarihi',
        ];
    }
}
