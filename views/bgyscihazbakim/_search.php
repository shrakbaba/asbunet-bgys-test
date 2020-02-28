<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BgyscihazbakimSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bgyscihazbakim-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'cihazid') ?>

    <?= $form->field($model, 'sorumlu') ?>

    <?= $form->field($model, 'periyod') ?>

    <?= $form->field($model, 'bakimformlari') ?>

    <?php // echo $form->field($model, 'sozlesme') ?>

    <?php // echo $form->field($model, 'kayittarihi') ?>

    <?php // echo $form->field($model, 'guncellemetarihi') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
