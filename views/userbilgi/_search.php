<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UserbilgiSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="userbilgi-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'ad') ?>

    <?= $form->field($model, 'soyad') ?>

    <?= $form->field($model, 'email') ?>

    <?= $form->field($model, 'tc') ?>

    <?php // echo $form->field($model, 'telefon') ?>

    <?php // echo $form->field($model, 'adres') ?>

    <?php // echo $form->field($model, 'dogumyili') ?>

    <?php // echo $form->field($model, 'kisi_id') ?>

    <?php // echo $form->field($model, 'tarihi') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
