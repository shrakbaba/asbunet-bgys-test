<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BgyseylemmatrisiSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bgyseylemmatrisi-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'altdeger') ?>

    <?= $form->field($model, 'ustdeger') ?>

    <?= $form->field($model, 'eylem') ?>

    <?= $form->field($model, 'aciklama') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
