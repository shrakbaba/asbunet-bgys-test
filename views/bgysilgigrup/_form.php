<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Bgysilgigrup */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bgysilgigrup-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'grupadi')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'iletisimbirimi')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'telefon')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'grup_web')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ilgi_konusu')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'iletisimegecme_durumu')->textArea(['rows'=>3,'maxlength' => true]) ?>

    <?= $form->field($model, 'etkilenecek_surecler')->textArea(['rows'=>3,'maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Kaydet', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
