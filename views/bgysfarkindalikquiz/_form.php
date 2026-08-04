<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\checkbox\CheckboxX;

/* @var $this yii\web\View */
/* @var $model app\models\Bgysfarkindalikquiz */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bgysfarkindalikquiz-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'egitim_id')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'cevaplayan')->hiddenInput()->label(false) ?>
    <div class="form-group">
        <?= Html::label('Ad Soyad', null, ['class' => 'control-label']) ?>
        <?= Html::textInput('cevaplayan_ad_soyad', $model->cevaplayanAdSoyad, [
            'class' => 'form-control',
            'readonly' => true,
        ]) ?>
    </div>
    <hr>

    <?php foreach ($model->quizSorulari as $index => $soru) { ?>
        <?= $form->field($model, 'soru' . ($index + 1))->radioList($soru['secenekler'])->label($soru['soru']); ?>
    <?php } ?>

    <div class="form-group">
        <?= Html::submitButton('Kaydet', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
