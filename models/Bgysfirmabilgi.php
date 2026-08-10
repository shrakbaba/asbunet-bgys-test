<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "firma_bilgi".
 *
 * @property int $id
 * @property string $firmaadi
 * @property string $yetkilikisi
 * @property string $telefon
 */
class Bgysfirmabilgi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bgys_firma_bilgi';
    }
    public $file;
    

    public function rules()
    {
        return [
            [['firmaadi', 'yetkilikisi', 'telefon','faaliyet_alani'], 'required'],
            [['tedarik_tipi', 'created_by'], 'integer'],
            [['firmaadi', 'yetkilikisi', 'telefon','mail','belge'], 'string', 'max' => 255],
            [['file'],'file','skipOnEmpty'=>true,'extensions'=>'pdf','mimeTypes'=>['application/pdf'],'maxSize' => 1024 * 1024 * 1],  //max 1Mb
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_by' => 'Oluşturan Kullanıcı',
            'firmaadi' => 'Tedarikçi Adı',
            'yetkilikisi' => 'Yetkili Kişi',
            'telefon' => 'Telefon',
            'faaliyet_alani' => 'Faaliyet Alanı',
            'tedarik_tipi' => 'Tedarik Tipi',
            'mail' => 'Mail',
            'belge' => 'Belge',
            'file'=>'Hizmet / Gizlilik Sözleşmesi (PDF)'

        ];
    }
}
