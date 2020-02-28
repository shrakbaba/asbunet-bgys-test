<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BgysyedeklemelistesiSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bgysyedeklemelistesi-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'yedekalinacak') ?>

    <?= $form->field($model, 'sorumlu') ?>

    <?= $form->field($model, 'yedeklemesekli') ?>

    <?= $form->field($model, 'yedekleme_yontemi') ?>

    <?php // echo $form->field($model, 'periyodu') ?>

    <?php // echo $form->field($model, 'yedeklemeyeri') ?>

    <?php // echo $form->field($model, 'yedeklemezamani') ?>

    <?php // echo $form->field($model, 'olusturma_tarihi') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
