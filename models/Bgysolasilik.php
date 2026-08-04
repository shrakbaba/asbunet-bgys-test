<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bgys_olasilik".
 *
 * @property int $id
 * @property string $deger
 * @property string $basamak
 */
class Bgysolasilik extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bgys_olasilik';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['deger'], 'required'],
            [['deger', 'basamak'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'deger' => 'Değer',
            'basamak' => 'Basamak',
        ];
    }
}
