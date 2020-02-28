<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BgysbilgisinifiSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bgysbilgisinifi-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'adi') ?>

    <?= $form->field($model, 'aciklama') ?>

    <?= $form->field($model, 'erisimhaklari') ?>

    <?= $form->field($model, 'saklama') ?>

    <?php // echo $form->field($model, 'iletim') ?>

    <?php // echo $form->field($model, 'imha') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
