<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bgys_risk_kabul".
 *
 * @property int $id
 * @property int $riskid
 * @property string $aciklama
 * @property int $kabuleden
 * @property string $tarih
 *
 * @property UserDb $kabuleden0
 * @property BgysRisk $risk
 */
class Bgysriskkabul extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bgys_risk_kabul';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['riskid'], 'required'],
            [['riskid', 'kabuleden'], 'integer'],
            [['tarih'], 'safe'],
            [['aciklama'], 'string', 'max' => 255],
            //[['kabuleden'], 'exist', 'skipOnError' => true, 'targetClass' => Userdb::className(), 'targetAttribute' => ['kabuleden' => 'id']],
            (Yii::$app->params['giristipi']==1) 
            ? [['kabuleden'], 'exist', 'skipOnError' => true, 'targetClass' =>\Edvlerblog\Adldap2\model\UserDbLdap::className() , 'targetAttribute' => ['kabuleden' => 'id']] 
            : [['kabuleden'], 'exist', 'skipOnError' => true, 'targetClass' => Userdb::className(), 'targetAttribute' => ['kabuleden' => 'id']],
            [['riskid'], 'exist', 'skipOnError' => true, 'targetClass' => Bgysrisk::className(), 'targetAttribute' => ['riskid' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'riskid' => 'Risk',
            'aciklama' => 'Açıklama',
            'kabuleden' => 'Kabul Eden',
            'tarih' => 'Tarih',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKabuleden0()
    {
        //return $this->hasOne(Userdb::className(), ['id' => 'kabuleden']);
        return (Yii::$app->params['giristipi']==1) ?
            $this->hasOne(\Edvlerblog\Adldap2\model\UserDbLdap::className(), ['id' => 'kabuleden'])
        :
            $this->hasOne(Userdb::className(), ['id' => 'kabuleden']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRisk()
    {
        return $this->hasOne(Bgysrisk::className(), ['id' => 'riskid']);
    }
}
