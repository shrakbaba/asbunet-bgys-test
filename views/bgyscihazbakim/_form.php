<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use yii\helpers\ArrayHelper;
use app\models\Userdb;
use app\models\Envcihazliste;
use app\models\Userbilgi;
use kartik\file\FileInput;
use kartik\select2\Select2;
use dosamigos\datepicker\DatePicker;
/* @var $this yii\web\View */
/* @var $model app\models\Bgyscihazbakim */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bgyscihazbakim-form">

    <?php $form = ActiveForm::begin(); ?>

      <?= $form->field($model, 'cihazid')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(Envcihazliste::find()->all(),'id',function($model) {
                            return $model->cihazTuru->cihaz_turu."/".$model->marka->marka."/".$model->model->model;
                            }),
        'options' => ['placeholder' => 'Cihaz',],
        'pluginOptions' => [
            'allowClear' => true
        ],
        ]); 
    ?>    

      <?= $form->field($model, 'sorumlu')->widget(Select2::classname(), [
        'data' => Yii::$app->params['giristipi']==1 ? 
        ArrayHelper::map(\Edvlerblog\Adldap2\model\UserDbLdap::find()->all(),'id',function($model) {
            //return $model['ad'].' '.$model['soyad'].' / '.$model['username'];
            return @Userbilgi::findOne(['kisi_id'=>$model['id']])->ad.' '.@Userbilgi::findOne(['kisi_id'=>$model['id']])->soyad.' / '.$model['username'];
            }) 
        :ArrayHelper::map(Userdb::find()->all(),'id',function($model) {                            
            return $model['ad'].' '.$model['soyad'].' / '.$model['username'];
            }) ,
        'options' => ['placeholder' => 'Bakım Sorumlusu',],
        'pluginOptions' => [
            'allowClear' => true
        ],
        ]); 
    ?>   
        <?= $form->field($model, 'bakimtarihi')->widget(
            DatePicker::className(), [
            // inline too, not bad
             //'inline' => true, 
             // modify template for custom rendering
            'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
            'clientOptions' => [
                'autoclose' => true,
                'format' => 'dd/mm/yyyy', 
                'minViewMode'=> "days"
            ],
            'language'=>'tr',
            'options'=>[  
              'placeholder'=>"Gün/Ay/Yıl"
            ],
            ]); 
        ?>
        
       <?= $form->field($model, 'periyod')->widget(Select2::classname(), [
        'data' => array("1ay" =>"1 Ay" ,"3ay"=>"3 Ay","6ay"=>'6 Ay','12ay'=>'12 Ay'),
        'options' => ['placeholder' => 'Bakım Periyodu',],
        'pluginOptions' => [
            'allowClear' => true
        ],
        ]); 
    ?>                      

     <?= $form->field($model, 'file')->widget(FileInput::classname(), [
                               'pluginOptions'=>
                                    $model->sozlesme ? [
                                        'initialPreview'=>[
                                            "/uploads/bgys/".md5("bakim")."/".$model->sozlesme,
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

        <?= $form->field($model, 'bakimlar[]')->widget(FileInput::classname(), [
                               'pluginOptions'=>[
                                        'allowedFileExtensions'=>['pdf'],
                                        'showUpload' => false
                                    ] ,
                                    'options' => [
                                        'accept' => 'pdf/*',
                                        'multiple'=>true,
                                    ]
                          ]);   ?>                   

    <div class="form-group">
        <?= Html::submitButton('Kaydet', ['class' => 'btn btn-success btn-lg']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
