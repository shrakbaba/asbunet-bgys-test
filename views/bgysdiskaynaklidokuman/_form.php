<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Bgysdiskaynaklidokuman */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bgysdiskaynaklidokuman-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'dokumanadi')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'kurum')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sorumlu')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'not')->textArea(['rows'=>3,'maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Kaydet', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
