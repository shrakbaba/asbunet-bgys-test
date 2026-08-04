<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "env_marka".
 *
 * @property int $id
 * @property string $marka
 *
 * @property EnvCihazListe[] $envCihazListes
 * @property EnvModel[] $envModels
 */
class Envmarka extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'env_marka';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['marka'], 'required'],
            [['marka'], 'string', 'max' => 255],
            [['marka'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'marka' => 'Marka',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEnvCihazListes()
    {
        return $this->hasMany(Envcihazliste::className(), ['marka_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEnvModels()
    {
        return $this->hasMany(Envmodel::className(), ['marka_id' => 'id']);
    }
}
