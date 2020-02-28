<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BgysdiftalepSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bgysdiftalep-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'dif_no') ?>

    <?= $form->field($model, 'talep_tarihi') ?>

    <?= $form->field($model, 'talep_eden') ?>

    <?= $form->field($model, 'dif_konusu') ?>

    <?php // echo $form->field($model, 'durum') ?>

    <?php // echo $form->field($model, 'planlanan_tarih') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
