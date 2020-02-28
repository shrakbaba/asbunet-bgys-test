<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BgysilgigrupSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bgysilgigrup-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'grupadi') ?>

    <?= $form->field($model, 'iletisimbirimi') ?>

    <?= $form->field($model, 'telefon') ?>

    <?= $form->field($model, 'grup_web') ?>

    <?php // echo $form->field($model, 'ilgi_konusu') ?>

    <?php // echo $form->field($model, 'iletisimegecme_durumu') ?>

    <?php // echo $form->field($model, 'etkilenecek_surecler') ?>

    <?php // echo $form->field($model, 'ekleme_tarihi') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
