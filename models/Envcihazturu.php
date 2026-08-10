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
            [['cihaz_turu', 'asset_type'], 'required'],
            [['cihaz_turu'], 'string', 'max' => 255],
            [['asset_type'], 'in', 'range' => array_keys(Bgysvarlikenvanteri::assetTypeOptions())],
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
            'asset_type' => 'BGYS Varlık Türü',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEnvCihazListes()
    {
        return $this->hasMany(Envcihazliste::className(), ['cihaz_turu_id' => 'id']);
    }

    public function getMarkalar()
    {
        return $this->hasMany(Envmarka::className(), ['id' => 'marka_id'])
            ->viaTable('env_cihaz_turu_marka', ['cihaz_turu_id' => 'id']);
    }

    public function getModeller()
    {
        return $this->hasMany(Envmodel::className(), ['id' => 'model_id'])
            ->viaTable('env_cihaz_turu_model', ['cihaz_turu_id' => 'id']);
    }
}
