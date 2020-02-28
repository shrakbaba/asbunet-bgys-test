<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Bgysbilgisinifi */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bgysbilgisinifi-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'adi')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'aciklama')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'erisimhaklari')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'saklama')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'iletim')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'imha')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Kaydet', ['class' => 'btn btn-success btn-lg']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
