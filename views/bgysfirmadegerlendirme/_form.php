<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Bgysfirmabilgi;

use kartik\rating\StarRating;
use dosamigos\datepicker\DatePicker;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $model app\models\Firmadegerlendirme */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="firmadegerlendirme-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php if ($model->scenario!="update") { ?>

        <?php $sql = 'SELECT firmaadi,id FROM bgys_firma_bilgi  WHERE id IN (SELECT Min(id) FROM bgys_firma_bilgi GROUP BY firmaadi)'; ?>

        <?= $form->field($model, 'firmaid')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(Bgysfirmabilgi::findBySql($sql)->all(),'id','firmaadi'),
        'options' => ['placeholder' => 'Firma Seçiniz',],
        'pluginOptions' => [
            'allowClear' => true
        ],
        ]); 
    ?>                       

   <?php }?>
    
  <?=$form->field($model, 'kriter1')->widget(StarRating::classname(), [
    'pluginOptions' => ['stars' => 10, 'min' => 0,'max' => 10,'step' => 1, 'showCaption' => false,]
]);
?>

<?= $form->field($model, 'kriter2')->widget(StarRating::classname(), [
    'pluginOptions' => ['stars' => 10, 'min' => 0,'max' => 10,'step' => 1, 'showCaption' => false,]
]);?>

<?= $form->field($model, 'kriter3')->widget(StarRating::classname(), [
    'pluginOptions' => ['stars' => 10, 'min' => 0,'max' => 10,'step' => 1, 'showCaption' => false,]
]);?>
<?= $form->field($model, 'kriter4')->widget(StarRating::classname(), [
    'pluginOptions' => ['stars' => 10, 'min' => 0,'max' => 10,'step' => 1, 'showCaption' => false,]
]);?>
<?= $form->field($model, 'kriter5')->widget(StarRating::classname(), [
    'pluginOptions' => ['stars' => 10, 'min' => 0,'max' => 10,'step' => 1, 'showCaption' => false,]
]);?>
<?= $form->field($model, 'kriter6')->widget(StarRating::classname(), [
    'pluginOptions' => ['stars' => 10, 'min' => 0,'max' => 10,'step' => 1, 'showCaption' => false,]
]);?>
<?= $form->field($model, 'kriter7')->widget(StarRating::classname(), [
    'pluginOptions' => ['stars' => 10, 'min' => 0,'max' => 10,'step' => 1, 'showCaption' => false,]
]);?>
<?= $form->field($model, 'kriter8')->widget(StarRating::classname(), [
    'pluginOptions' => ['stars' => 10, 'min' => 0,'max' => 10,'step' => 1, 'showCaption' => false,]
]);?>
<?= $form->field($model, 'kriter9')->widget(StarRating::classname(), [
   'pluginOptions' => ['stars' => 10, 'min' => 0,'max' => 10,'step' => 1, 'showCaption' => false,]
]);?>
<?= $form->field($model, 'kriter10')->widget(StarRating::classname(), [
    'pluginOptions' => ['stars' => 10, 'min' => 0,'max' => 10,'step' => 1, 'showCaption' => false,]
]);?>

 <?= $form->field($model, 'degerlendirilenyil')->widget(
            DatePicker::className(), [
            // inline too, not bad
             //'inline' => true, 
             // modify template for custom rendering
            'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
            'clientOptions' => [
                'autoclose' => true,
                'format' => 'yyyy', 
                'minViewMode'=> "years"
            ],
            'language'=>'tr',
            'options'=>[  
              'placeholder'=>"Yıl"
            ],
            ]); 
        ?>
<?= $form->field($model, 'degerlendirilenhizmet')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'not')->textarea(['maxlength' => true,'rows' => 3]) ?>

    <div class="form-group">
        <?= Html::submitButton('Kaydet', ['class' => 'btn btn-success btn-lg']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
