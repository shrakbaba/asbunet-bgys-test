<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\FirmadegerlendirmeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="firmadegerlendirme-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'firmaid') ?>

    <?= $form->field($model, 'degerlendiren') ?>

    <?= $form->field($model, 'kriter1') ?>

    <?= $form->field($model, 'kriter2') ?>

    <?php // echo $form->field($model, 'kriter3') ?>

    <div class="form-group">
        <?= Html::submitButton('Filtrele', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Temizle', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
