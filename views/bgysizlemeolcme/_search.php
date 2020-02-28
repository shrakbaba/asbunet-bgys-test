<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BgysizlemeolcmeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bgysizlemeolcme-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'yil') ?>

    <?= $form->field($model, 'kontrol') ?>

    <?= $form->field($model, 'hedef_degeri') ?>

    <?= $form->field($model, 'olcum_sikligi') ?>

    <?php // echo $form->field($model, 'planlanan_tarihi') ?>

    <?php // echo $form->field($model, 'olcum_sonucu') ?>

    <?php // echo $form->field($model, 'kontrol_kriteri') ?>

    <?php // echo $form->field($model, 'sorumlu') ?>

    <?php // echo $form->field($model, 'olusturma_tarihi') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
