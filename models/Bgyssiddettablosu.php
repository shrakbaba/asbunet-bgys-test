<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bgys_siddet_tablosu".
 *
 * @property int $id
 * @property string $anlam
 * @property string $gizlilik
 * @property string $butunluk
 * @property string $erisilebilirlik
 */
class Bgyssiddettablosu extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bgys_siddet_tablosu';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['anlam'], 'required'],
            [['anlam', 'gizlilik', 'butunluk', 'erisilebilirlik'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'anlam' => 'Anlam',
            'gizlilik' => 'Gizlilik',
            'butunluk' => 'Bütünlük',
            'erisilebilirlik' => 'Erişilebilirlik',
        ];
    }
}
