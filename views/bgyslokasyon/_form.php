<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Bgyslokasyon */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bgyslokasyon-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'lokasyon')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Kaydet', ['class' => 'btn btn-success btn-lg']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
