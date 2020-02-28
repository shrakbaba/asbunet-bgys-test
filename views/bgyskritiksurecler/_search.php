<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BgyskritiksureclerSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bgyskritiksurecler-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'surec') ?>

    <?= $form->field($model, 'keks') ?>

    <?= $form->field($model, 'kevk') ?>

    <?= $form->field($model, 'etkisi') ?>

    <?php // echo $form->field($model, 'ilkaksiyon') ?>

    <?php // echo $form->field($model, 'yedeklilik') ?>

    <?php // echo $form->field($model, 'ulasilacaklar') ?>

    <?php // echo $form->field($model, 'ekleme_tarihi') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
