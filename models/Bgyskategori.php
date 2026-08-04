<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bgys_kategori".
 *
 * @property int $id
 * @property string $adi
 */
class Bgyskategori extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bgys_kategori';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['adi'], 'required'],
            [['adi'], 'string', 'max' => 255],
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
        ];
    }
}
