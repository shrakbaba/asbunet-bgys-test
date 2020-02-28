<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bgys_izlemesonucu".
 *
 * @property int $id
 * @property int $izlemeid
 * @property string $olcumsonucu
 * @property string $hedeforani
 * @property string $olusturma_tarihi
 *
 * @property BgysIzlemeolcme $izleme
 */
class Bgysizlemesonucu extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bgys_izlemesonucu';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['izlemeid', 'olcumsonucu', 'hedeforani'], 'required'],
            [['izlemeid','hedeforani'], 'integer'],
            [['olusturma_tarihi'], 'safe'],
            [['olcumsonucu'], 'string', 'max' => 500],
            [['izlemeid'], 'exist', 'skipOnError' => true, 'targetClass' => Bgysizlemeolcme::className(), 'targetAttribute' => ['izlemeid' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'izlemeid' => 'Kontrol',
            'olcumsonucu' => 'Ölçüm Sonucu',
            'hedeforani' => 'Hedefi Yakalama Oranı',
            'olusturma_tarihi' => 'Oluşturma Tarihi',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIzleme()
    {
        return $this->hasOne(Bgysizlemeolcme::className(), ['id' => 'izlemeid']);
    }
}
