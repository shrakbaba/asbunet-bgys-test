<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BgyslogsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bgyslogs-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'controller') ?>

    <?= $form->field($model, 'action') ?>

    <?= $form->field($model, 'userid') ?>

    <?= $form->field($model, 'date') ?>

    <?php // echo $form->field($model, 'not') ?>

    <?php // echo $form->field($model, 'islem') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
