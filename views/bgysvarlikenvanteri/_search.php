<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BgysvarlikenvanteriSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bgysvarlikenvanteri-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'departman') ?>

    <?= $form->field($model, 'varlik_adi') ?>

    <?= $form->field($model, 'bilgi_sinifi') ?>

    <?= $form->field($model, 'lokasyon') ?>

    <?php // echo $form->field($model, 'kategori') ?>

    <?php // echo $form->field($model, 'varlik_sahibi') ?>

    <?php // echo $form->field($model, 'gizlilik') ?>

    <?php // echo $form->field($model, 'butunluk') ?>

    <?php // echo $form->field($model, 'erisilebilirlik') ?>

    <?php // echo $form->field($model, 'varlik_degeri') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
