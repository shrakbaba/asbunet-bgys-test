<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\EnvmarkaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="envmarka-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'marka') ?>

    <div class="form-group">
        <?= Html::submitButton('Ara', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Temizle', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
