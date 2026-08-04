<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bgys_bilgi_sinifi".
 *
 * @property int $id
 * @property string $adi
 * @property string $aciklama
 * @property string $erisimhaklari
 * @property string $saklama
 * @property string $iletim
 * @property string $imha
 */
class Bgysbilgisinifi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bgys_bilgi_sinifi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['adi'], 'required'],
            [['adi'], 'string', 'max' => 255],
            [['aciklama', 'erisimhaklari', 'iletim'], 'string', 'max' => 300],
            [['saklama'], 'string', 'max' => 350],
            [['imha'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'adi' => 'Adı',
            'aciklama' => 'Açıklama',
            'erisimhaklari' => 'Erişim Hakları',
            'saklama' => 'Saklama',
            'iletim' => 'İletim',
            'imha' => 'İmha',
        ];
    }
}
