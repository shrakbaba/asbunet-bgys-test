<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BgysfarkindalikquizSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bgysfarkindalikquiz-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'cevaplayan') ?>

    <?= $form->field($model, 'ip') ?>

    <?= $form->field($model, 'cevaplamatarihi') ?>

    <?= $form->field($model, 'cevaplar') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
