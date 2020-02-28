<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Bgyssiddettablosu */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bgyssiddettablosu-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'anlam')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'gizlilik')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'butunluk')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'erisilebilirlik')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Kaydet', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
