<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Userbilgi */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="userbilgi-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'ad')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'soyad')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tc')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'telefon')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'adres')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dogumyili')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'kisi_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
