<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\checkbox\CheckboxX;

/* @var $this yii\web\View */
/* @var $model app\models\Bgysfarkindalikquiz */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bgysfarkindalikquiz-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'cevaplayan')->textInput(['maxlength' => true]) ?>
    <hr>
    
	<?= $form->field($model, 'soru1')->radioList($model->soru1data); ?>

	<?= $form->field($model, 'soru2')->radioList($model->soru2data); ?>

	<?= $form->field($model, 'soru3')->radioList($model->soru3data); ?>

	<?= $form->field($model, 'soru4')->radioList($model->soru4data); ?>

	<?= $form->field($model, 'soru5')->radioList($model->soru5data); ?>

	<?= $form->field($model, 'soru6')->radioList($model->soru6data); ?>

	<?= $form->field($model, 'soru7')->radioList($model->soru7data); ?>

	<?= $form->field($model, 'soru8')->radioList($model->soru8data); ?>

	<?= $form->field($model, 'soru9')->radioList($model->soru9data); ?>

	<?= $form->field($model, 'soru10')->radioList($model->soru10data); ?>

	<?= $form->field($model, 'soru11')->radioList($model->soru11data); ?>



    <div class="form-group">
        <?= Html::submitButton('Kaydet', ['class' => 'btn btn-success btn-lg']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
