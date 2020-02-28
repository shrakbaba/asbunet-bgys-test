<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\OlaykayitSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="olaykayit-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'userid') ?>

    <?= $form->field($model, 'konu') ?>

    <?= $form->field($model, 'olaytarihi') ?>

    <?= $form->field($model, 'mudahaleeden') ?>

    <?php // echo $form->field($model, 'yapilanmudahale') ?>

    <?php // echo $form->field($model, 'mudahaletarihi') ?>

    <?php // echo $form->field($model, 'sonuc') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
