<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

use yii\helpers\ArrayHelper;
use app\models\Envmarka;
use app\models\Envmodel;
use app\models\Envcihazturu;
use app\models\Userdb;
use app\models\Userbilgi;
use app\models\Bgysvarlikenvanteri;
use app\models\Bgyskategori;

use dosamigos\datepicker\DatePicker;
use dosamigos\datepicker\DateRangePicker;

use kartik\file\FileInput;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $model app\models\Envcihazliste */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="envcihazliste-form">

    <?php $form = ActiveForm::begin(['layout' => 'horizontal','options'=>['enctype'=>'multipart/form-data']]); ?>

         <?= $form->field($model, 'cihaz_turu_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(Envcihazturu::find()->all(),'id','cihaz_turu'),
        'options' => ['placeholder' => 'Tür Seçiniz',],
        'pluginOptions' => [
            'allowClear' => true
        ],
        ]); 
    ?>

                   <?= $form->field($model, 'marka_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(Envmarka::find()->all(),'id','marka'),
        'options' => ['placeholder' => 'Marka Seçiniz','onchange'=>'
                 $.post( "/envmodel/lists?id='.'"+$(this).val(), function( data ) {
                  $( "select#envcihazliste-model_id" ).html( data );
                });'],
        'pluginOptions' => [
            'allowClear' => true
        ],
        ]); 
    ?>

          <?= $form->field($model, 'model_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(Envmodel::find()->all(),'id','model'),
        'options' => ['placeholder' => 'Model Seçiniz',],
        'pluginOptions' => [
            'allowClear' => true
        ],
        ]); 
    ?>
   
    <?= $form->field($model, 'adet')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bgys_asset_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(
            Bgysvarlikenvanteri::find()
                ->where([
                    'kategori' => Bgyskategori::find()
                        ->select('id')
                        ->where(['adi' => \app\models\Envcihazliste::deviceAssetCategoryNames()]),
                ])
                ->orderBy(['varlik_adi' => SORT_ASC])
                ->all(),
            'id',
            function ($asset) {
                return $asset->varlik_adi . ($asset->varlik_sahibi ? ' / ' . $asset->varlik_sahibi : '');
            }
        ),
        'options' => ['placeholder' => 'İlişkili BGYS varlığını seçin'],
        'pluginOptions' => ['allowClear' => true],
    ])->hint('Donanımın bilgi güvenliği sınıflandırması ve riskleri bu BGYS varlığı üzerinden yönetilir.') ?>

   
    <?= $form->field($model, 'alim_tarihi')->textInput()->label('Garanti Süresi')->widget(DateRangePicker::className(), [
        'attributeTo' => 'garanti_bitis', 
        'form' => $form, // best for correct client validation
        'language' => 'tr',
        'size' => 'lg',
        'clientOptions' => [
            'autoclose' => true,
            'format' => 'dd/mm/yyyy' 
        ]
    ]) ;?>


    <?= $form->field($model, 'konum')->textInput(['maxlength' => true])->textarea(['rows' => '2'])->hint('Çoklu girdileri ; ile ayırınız.') ?>

        
    <?= $form->field($model, 'key')->textInput(['maxlength' => true])->textarea(['rows' => '2'])->hint('Çoklu girdileri ; ile ayırınız.')  ?>
        
    <?= $form->field($model, 'service_tag')->textInput(['maxlength' => true])->textarea(['rows' => '2'])->hint('Çoklu girdileri ; ile ayırınız.')  ?>

    <?= $form->field($model, 'ozet')->textInput(['maxlength' => true])->textarea(['rows' => '2']) ?>

    <?= $form->field($model, 'link')->textInput(['maxlength' => true])->textarea(['rows' => '1']) ?>
    
    <?php if($model->dosya) {
        echo Html::a('PDF Belgesini Sil', ['pdfsil', 'i' => $model->id], [
            'class' => 'btn btn-info',
            'style' => 'float: right',
            'data' => [
                'confirm' => 'Bu kaydın dosyasını silmek istediğinizden emin misiniz?',
                'method' => 'post',
            ],
        ]);
    } ?>

     <?= $form->field($model, 'file')->widget(FileInput::classname(), [
                               'pluginOptions'=>
                                    $model->dosya ? [
                                        'initialPreview'=>[
                                            \yii\helpers\Url::to(['/envcihazliste/download', 'id' => $model->id]),
                                        ],
                                        'initialPreviewFileType' => 'pdf',
                                        'initialPreviewAsData'=>true,
                                        'allowedFileExtensions'=>['pdf'],
                                        'showUpload' => false 
                                    ]
                                    :[
                                        'allowedFileExtensions'=>['pdf'],
                                        'showUpload' => false
                                    ] ,
                          ]);   ?>      
        
    <?php /* $form->field($model, 'garanti_bitis')->widget(
        DatePicker::className(), [
        // inline too, not bad
         'inline' => true, 
         // modify template for custom rendering
        'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
        'clientOptions' => [
            'autoclose' => true,
            'format' => 'dd-mm-yyyy'
        ]
    ]);  */ ?>

  
    <?= $form->field($model, 'zimmet')->widget(Select2::classname(), [
        'data' => Yii::$app->params['giristipi']==1 ? 
        ArrayHelper::map(\Edvlerblog\Adldap2\model\UserDbLdap::find()->all(),'id',function($model) {
            //return $model['ad'].' '.$model['soyad'].' / '.$model['username'];
            return @Userbilgi::findOne(['kisi_id'=>$model['id']])->ad.' '.@Userbilgi::findOne(['kisi_id'=>$model['id']])->soyad.' / '.$model['username'];
            }) 
        :ArrayHelper::map(Userdb::find()->all(),'id',function($model) {                            
            return $model['ad'].' '.$model['soyad'].' / '.$model['username'];
            }) ,
        'options' => ['placeholder' => 'Zimmet Yapılacak',],
        'pluginOptions' => [
            'allowClear' => true
        ],
        ]); 
    ?>  

        <?= Html::submitButton('Kaydet', ['class' => 'btn btn-success btn-lg']) ?>
  
    <?php ActiveForm::end(); ?>

</div>
