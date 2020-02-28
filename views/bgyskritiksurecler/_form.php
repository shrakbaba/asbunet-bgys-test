<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Bgyskritiksurecler */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bgyskritiksurecler-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'surec')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'keks')->textInput() ?>

    <?= $form->field($model, 'kevk')->textInput() ?>

    <?= $form->field($model, 'etkisi')->textArea(['rows'=>5,'maxlength' => true]) ?>

    <?= $form->field($model, 'ilkaksiyon')->textArea(['rows'=>5,'maxlength' => true]) ?>

    <?= $form->field($model, 'yedeklilik')->textArea(['rows'=>5,'maxlength' => true]) ?>

    <?= $form->field($model, 'ulasilacaklar')->textArea(['rows'=>5,'maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
