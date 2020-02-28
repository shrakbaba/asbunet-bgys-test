<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Authitem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="authitem-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->dropDownList(array(1=>'Rol',2=>'İzin'), ['prompt' => 'Nesne Tipi'])  ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

  <!--  <?= $form->field($model, 'rule_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'data')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>
-->
    <div class="form-group">
        <?= Html::submitButton('Kaydet', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
