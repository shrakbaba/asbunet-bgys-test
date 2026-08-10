<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "env_model".
 *
 * @property int $id
 * @property string $model
 * @property int $marka_id
 *
 * @property EnvCihazListe[] $envCihazListes
 * @property EnvMarka $marka
 */
class Envmodel extends \yii\db\ActiveRecord
{
    public $cihaz_turu_ids = [];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'env_model';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['model', 'marka_id'], 'required'],
            [['marka_id'], 'integer'],
            [['model'], 'string', 'max' => 255],
            [['marka_id'], 'exist', 'skipOnError' => true, 'targetClass' => Envmarka::className(), 'targetAttribute' => ['marka_id' => 'id']],
            [['cihaz_turu_ids'], 'each', 'rule' => ['integer']],
            [['cihaz_turu_ids'], 'validateDeviceTypeBrands'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'model' => 'Model',
            'marka_id' => 'Marka',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEnvCihazListes()
    {
        return $this->hasMany(Envcihazliste::className(), ['model_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMarka()
    {
        return $this->hasOne(Envmarka::className(), ['id' => 'marka_id']);
    }

    public function getCihazTurleri()
    {
        return $this->hasMany(Envcihazturu::className(), ['id' => 'cihaz_turu_id'])
            ->viaTable('env_cihaz_turu_model', ['model_id' => 'id']);
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->cihaz_turu_ids = $this->getCihazTurleri()->select('env_cihaz_turu.id')->column();
    }

    public function validateDeviceTypeBrands($attribute)
    {
        if (!$this->marka_id || !$this->cihaz_turu_ids) {
            return;
        }
        $linkedTypeIds = (new \yii\db\Query())->select('cihaz_turu_id')->from('env_cihaz_turu_marka')
            ->where(['marka_id' => (int)$this->marka_id])->column();
        if (array_diff(array_map('intval', (array)$this->cihaz_turu_ids), array_map('intval', $linkedTypeIds))) {
            $this->addError($attribute, 'Model yalnız markanın bağlı olduğu cihaz türleriyle ilişkilendirilebilir.');
        }
    }

}
