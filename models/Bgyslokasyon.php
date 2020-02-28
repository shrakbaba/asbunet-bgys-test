<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bgys_lokasyon".
 *
 * @property int $id
 * @property string $lokasyon
 *
 * @property BgysVarlikEnvanteri[] $bgysVarlikEnvanteris
 */
class Bgyslokasyon extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bgys_lokasyon';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lokasyon'], 'required'],
            [['lokasyon'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'lokasyon' => 'Lokasyon',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBgysVarlikEnvanteris()
    {
        return $this->hasMany(BgysVarlikEnvanteri::className(), ['lokasyon' => 'id']);
    }
}
