<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "env_cihaz_turu".
 *
 * @property int $id
 * @property string $cihaz_turu
 *
 * @property EnvCihazListe[] $envCihazListes
 */
class Envcihazturu extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'env_cihaz_turu';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cihaz_turu'], 'required'],
            [['cihaz_turu'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cihaz_turu' => 'Cihaz Türü',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEnvCihazListes()
    {
        return $this->hasMany(Envcihazliste::className(), ['cihaz_turu_id' => 'id']);
    }
}
