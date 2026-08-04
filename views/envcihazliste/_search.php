<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\EnvcihazlisteSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="envcihazliste-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'cihaz_turu_id') ?>

    <?= $form->field($model, 'marka_id') ?>

    <?= $form->field($model, 'model_id') ?>

    <?= $form->field($model, 'adet') ?>

    <?php // echo $form->field($model, 'alim_tarihi') ?>

    <?php // echo $form->field($model, 'garanti_bitis') ?>

    <div class="form-group">
        <?= Html::submitButton('Ara', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Temizle', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
