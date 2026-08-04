<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bgys_departman".
 *
 * @property int $id
 * @property string $departman
 */
class Bgysdepartman extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bgys_departman';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['departman'], 'required'],
            [['departman'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'departman' => 'Departman',
        ];
    }
}
