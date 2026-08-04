<?php

namespace app\models;

use Yii;
use app\models\Bgysdiftalep;
/**
 * This is the model class for table "bgys_dif_takip".
 *
 * @property int $id
 * @property int $diftalep_id
 * @property string $sorumlukisi
 * @property string $kokneden
 * @property string $uygulananfaaliyet
 * @property string $tamamlanmatarihi
 * @property string $tarih
 * @property string $sonuc
 * @property int $dif_sorumlusu
 * @property int $onay
 *
 * @property User $difSorumlusu
 * @property BgysDifTalep $diftalep
 */
class Bgysdiftakip extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bgys_dif_takip';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['diftalep_id', 'sorumlukisi', 'dif_sorumlusu'], 'required'],
            [['diftalep_id', 'dif_sorumlusu', 'onay'], 'integer'],
            [['tamamlanmatarihi', 'tarih'], 'safe'],
            [['sorumlukisi', ], 'string', 'max' => 255],
            [['kokneden', 'uygulananfaaliyet', 'sonuc'], 'string', 'max' => 1500],
            //[['dif_sorumlusu'], 'exist', 'skipOnError' => true, 'targetClass' => Userdb::className(), 'targetAttribute' => ['dif_sorumlusu' => 'id']],
            (Yii::$app->params['giristipi']==1) 
            ? [['dif_sorumlusu'], 'exist', 'skipOnError' => true, 'targetClass' =>\Edvlerblog\Adldap2\model\UserDbLdap::className() , 'targetAttribute' => ['dif_sorumlusu' => 'id']] 
            : [['dif_sorumlusu'], 'exist', 'skipOnError' => true, 'targetClass' => Userdb::className(), 'targetAttribute' => ['dif_sorumlusu' => 'id']],
            [['diftalep_id'], 'exist', 'skipOnError' => true, 'targetClass' => Bgysdiftalep::className(), 'targetAttribute' => ['diftalep_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'diftalep_id' => 'Dif Talep ID',
            'sorumlukisi' => 'Sorumlu Kişi',
            'kokneden' => 'Kök Neden',
            'uygulananfaaliyet' => 'Uygulanan Faaliyet',
            'tamamlanmatarihi' => 'Tamamlanma Tarihi',
            'tarih' => 'Tarih',
            'sonuc' => 'Sonuç',
            'dif_sorumlusu' => 'Dif Sorumlusu',
            'onay' => 'Onay'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDifSorumlusu()
    {
        //return $this->hasOne(Userdb::className(), ['id' => 'dif_sorumlusu']);
        return (Yii::$app->params['giristipi']==1) ?
            $this->hasOne(\Edvlerblog\Adldap2\model\UserDbLdap::className(), ['id' => 'dif_sorumlusu'])
        :
            $this->hasOne(Userdb::className(), ['id' => 'dif_sorumlusu']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDiftalep()
    {
        return $this->hasOne(Bgysdiftalep::className(), ['id' => 'diftalep_id']);
    }
}
