<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Bgyskategori */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bgyskategori-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'adi')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Kaydet', ['class' => 'btn btn-success btn-lg']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
