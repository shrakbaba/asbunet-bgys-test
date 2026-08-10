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
    public $cihaz_turu_ids = [];
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
            [['cihaz_turu_ids'], 'each', 'rule' => ['integer']],
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

    public function getCihazTurleri()
    {
        return $this->hasMany(Envcihazturu::className(), ['id' => 'cihaz_turu_id'])
            ->viaTable('env_cihaz_turu_marka', ['marka_id' => 'id']);
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->cihaz_turu_ids = $this->getCihazTurleri()->select('env_cihaz_turu.id')->column();
    }
}
