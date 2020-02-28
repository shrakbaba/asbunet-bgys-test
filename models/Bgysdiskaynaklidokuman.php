<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bgys_diskaynaklidokuman".
 *
 * @property int $id
 * @property string $dokumanadi
 * @property string $kurum
 * @property string $sorumlu
 * @property string $link
 * @property string $not
 */
class Bgysdiskaynaklidokuman extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bgys_diskaynaklidokuman';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dokumanadi'], 'required'],
            [['dokumanadi', 'kurum', 'sorumlu', 'link'], 'string', 'max' => 255],
            [['not'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dokumanadi' => 'Doküman Adi',
            'kurum' => 'Yayınlayan Kurum',
            'sorumlu' => 'Sorumlu',
            'link' => 'Link',
            'not' => 'Not',
        ];
    }
}
