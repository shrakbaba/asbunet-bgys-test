<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bgys_cihaz_bakim".
 *
 * @property int $id
 * @property int $cihazid
 * @property int $sorumlu
 * @property string $periyod
 * @property string $bakimformlari
 * @property string $sozlesme
 * @property string $kayittarihi
 * @property string $guncellemetarihi
 *
 * @property UserDb $sorumlu0
 * @property EnvCihazListe $cihaz
 */
class Bgyscihazbakim extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bgys_cihaz_bakim';
    }

    public $file;
    public $bakimlar;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cihazid', 'sorumlu', 'periyod'], 'required'],
            [['cihazid', 'sorumlu'], 'integer'],
            [['kayittarihi', 'guncellemetarihi','bakimtarihi'], 'safe'],
            [['periyod', 'bakimformlari', 'sozlesme'], 'string', 'max' => 255],
            (Yii::$app->params['giristipi']==1) 
            ? [['sorumlu'], 'exist', 'skipOnError' => true, 'targetClass' =>\Edvlerblog\Adldap2\model\UserDbLdap::className() , 'targetAttribute' => ['sorumlu' => 'id']] 
            : [['sorumlu'], 'exist', 'skipOnError' => true, 'targetClass' => Userdb::className(), 'targetAttribute' => ['sorumlu' => 'id']],

            [['cihazid'], 'exist', 'skipOnError' => true, 'targetClass' => Envcihazliste::className(), 'targetAttribute' => ['cihazid' => 'id']],
            [['file'],'file','skipOnEmpty'=>true,'extensions'=>'pdf','maxSize' => 1024 * 1024 * 1],  //max 1Mb
            [['bakimlar'],'file','skipOnEmpty'=>true,'extensions'=>'pdf','maxSize' => 1024 * 1024 * 10,'maxFiles'=>10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cihazid' => 'Cihaz',
            'sorumlu' => 'Sorumlu',
            'periyod' => 'Bakım Periyodu',
            'bakimformlari' => 'Bakim Formları',
            'sozlesme' => 'Sözleşme',
            'kayittarihi' => 'Kayit Tarihi',
            'guncellemetarihi' => 'Güncelleme Tarihi',
            'bakimtarihi'=>'Son yapılan bakım tarihi',
            'file'=>'Cihaz Sözleşmesi',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSorumlu0()
    {
        return (Yii::$app->params['giristipi']==1) ?
            $this->hasOne(\Edvlerblog\Adldap2\model\UserDbLdap::className(), ['id' => 'sorumlu'])
        :
            $this->hasOne(Userdb::className(), ['id' => 'sorumlu']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCihaz()
    {
        return $this->hasOne(Envcihazliste::className(), ['id' => 'cihazid']);
    }
}
