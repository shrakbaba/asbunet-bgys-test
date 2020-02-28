<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BgysdiskaynaklidokumanSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bgysdiskaynaklidokuman-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'dokumanadi') ?>

    <?= $form->field($model, 'kurum') ?>

    <?= $form->field($model, 'sorumlu') ?>

    <?= $form->field($model, 'link') ?>

    <?php // echo $form->field($model, 'not') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
