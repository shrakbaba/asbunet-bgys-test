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

}
