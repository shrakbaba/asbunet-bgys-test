<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mail_kapat".
 *
 * @property int $id
 * @property string $mailhesabi
 * @property string $ayrilistarihi
 */
class Mailkapat extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mail_kapat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mailhesabi', 'ayrilistarihi','kapatildi'], 'required'],
            [['ayrilistarihi','kapatildi'], 'safe'],
            [['mailhesabi'], 'string', 'max' => 255],
            [['mailhesabi'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mailhesabi' => 'Mail Hesabı',
            'ayrilistarihi' => 'Ayrılış Tarihi',
            'kapatildi' => 'Hesap Kapatıldı mı?'
        ];
    }
}
