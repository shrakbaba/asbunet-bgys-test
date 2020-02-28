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

    <?= $form->field($model, 'soru1')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'soru2')->widget(Select2::classname(), [
	    'data' => $model->soru2data,
	    'options' => ['placeholder' => 'Select a state ...',
	        'options' => [
	            3 => ['disabled' => true],
	            4 => ['disabled' => true],
	        ]],
	    'pluginOptions' => [
	        'allowClear' => true
	    ],
		]); 
	?>

	<?= $form->field($model, 'soru3[]')->checkboxList($model->soru3data); ?>


	<?= $form->field($model, 'soru4')->radioList($model->soru4data); ?>


    <div class="form-group">
        <?= Html::submitButton('Kaydet', ['class' => 'btn btn-success btn-lg']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
