<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bgys_yedekleme_listesi".
 *
 * @property int $id
 * @property string $yedekalinacak
 * @property int $sorumlu
 * @property int $yedeklemesekli
 * @property string $yedekleme_yontemi
 * @property int $periyodu
 * @property string $yedeklemeyeri
 * @property string $yedeklemezamani
 * @property string $olusturma_tarihi
 *
 * @property Userdb $sorumlu0
 */
class Bgysyedeklemelistesi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bgys_yedekleme_listesi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['yedekalinacak', 'sorumlu', 'yedeklemesekli'], 'required'],
            [['sorumlu', 'yedeklemesekli', 'periyodu', 'yedeklemezamani'], 'integer'],
            [['olusturma_tarihi'], 'safe'],
            [['yedekalinacak', 'yedekleme_yontemi', 'yedeklemeyeri'], 'string', 'max' => 255],
            [['sorumlu'], 'exist', 'skipOnError' => true, 'targetClass' => \Edvlerblog\Adldap2\model\UserDbLdap::className(), 'targetAttribute' => ['sorumlu' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'yedekalinacak' => 'Yedeği Alınacak Varlık',
            'sorumlu' => 'Sorumlu',
            'yedeklemesekli' => 'Yedekleme Şekli',
            'yedekleme_yontemi' => 'Yedekleme Yöntemi',
            'periyodu' => 'Yedekleme Periyodu',
            'yedeklemeyeri' => 'Yedekleme Yeri',
            'yedeklemezamani' => 'Yedekleme Zamani',
            'olusturma_tarihi' => 'Olusturma Tarihi',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSorumlu0()
    {
        return $this->hasOne(\Edvlerblog\Adldap2\model\UserDbLdap::className(), ['id' => 'sorumlu']);
    }
}
