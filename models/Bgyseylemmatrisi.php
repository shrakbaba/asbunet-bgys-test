<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bgys_eylem_matrisi".
 *
 * @property int $id
 * @property int $altdeger
 * @property int $ustdeger
 * @property string $eylem
 * @property string $aciklama
 */
class Bgyseylemmatrisi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bgys_eylem_matrisi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['altdeger', 'ustdeger'], 'required'],
            [['altdeger', 'ustdeger'], 'integer'],
            [['eylem', 'aciklama'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'altdeger' => 'Alt Değer',
            'ustdeger' => 'Üst Değer',
            'eylem' => 'Eylem',
            'aciklama' => 'Açıklama',
        ];
    }
}
