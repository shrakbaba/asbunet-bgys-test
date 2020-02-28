<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;
use dosamigos\datepicker\DateRangePicker;

use kartik\file\FileInput;
/* @var $this yii\web\View */
/* @var $model app\models\Olaykayit */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="olaykayit-form">

    <?php $form = ActiveForm::begin(); ?>

    <!--<?php // $form->field($model, 'userid')->textInput() ?>-->

    <?= $form->field($model, 'konu')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'olaytarihi')->textInput()->label('Olay Tarihi ==> Müdahale Tarihi')->widget(DateRangePicker::className(), [
        'attributeTo' => 'mudahaletarihi', 
        'form' => $form, // best for correct client validation
        'language' => 'tr',
        'size' => 'lg',
        'clientOptions' => [
            'autoclose' => true,
            'format' => 'dd/mm/yyyy' 
        ]
    ]) ;?>

    <?= $form->field($model, 'mudahaleeden')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'yapilanmudahale')->textInput(['maxlength' => true])->textarea(['rows' => '3']) ?>
    
    <?= $form->field($model, 'sonuc')->textInput(['maxlength' => true])->textarea(['rows' => '3']) ?>
    <?= $form->field($model, 'onlem')->textInput(['maxlength' => true])->textarea(['rows' => '3']) ?>

    <?php if($model->belge) {
        ?> <a class ="btn btn-info" style="float: right" href="pdfsil?i=<?php echo $model->id; ?>"> PDF Belgesini Sil</a>
    <?php } ?>

     <?= $form->field($model, 'file')->widget(FileInput::classname(), [
                               'pluginOptions'=>
                                    $model->belge ? [
                                        'initialPreview'=>[
                                            "/uploads/bgys/".md5("olay")."/".$model->belge,
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
   
    <div class="form-group">
        <?= Html::submitButton('Kaydet', ['class' => 'btn btn-success btn-lg']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
