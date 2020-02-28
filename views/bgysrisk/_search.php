<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BgysriskSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bgysrisk-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'varlik') ?>

    <?= $form->field($model, 'departman') ?>

    <?= $form->field($model, 'risk') ?>

    <?= $form->field($model, 'risk_nedeni') ?>

    <?php // echo $form->field($model, 'risk_sorumlusu') ?>

    <?php // echo $form->field($model, 'olasilik_onceki') ?>

    <?php // echo $form->field($model, 'gizlilik') ?>

    <?php // echo $form->field($model, 'butunluk') ?>

    <?php // echo $form->field($model, 'erisilebilirlik') ?>

    <?php // echo $form->field($model, 'olasilik_sonraki') ?>

    <?php // echo $form->field($model, 'yuksek_riskin_sebebi') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
