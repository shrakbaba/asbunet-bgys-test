<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\YenihostbildirSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="yenihostbildir-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'vm_name') ?>

    <?= $form->field($model, 'zabbix') ?>

    <?= $form->field($model, 'kaspersky') ?>

    <?= $form->field($model, 'ipmanage') ?>

    <?php // echo $form->field($model, 'paloalto') ?>

    <?php // echo $form->field($model, 'tarihi') ?>

    <?php // echo $form->field($model, 'json') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
