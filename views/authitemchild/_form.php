<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use yii\helpers\ArrayHelper;
use app\models\Authitem;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $model app\models\Authitemchild */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="authitemchild-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'parent')->widget(Select2::classname(), [
	    'data' => ArrayHelper::map(Authitem::find()->where(['type' => 1])->all(),'name','name'),
	    'options' => ['placeholder' => 'Ebeveyn Rol Seçiniz',],
	    'pluginOptions' => [
	        'allowClear' => true
	    ],
		]); 
	?>

	<?= $form->field($model, 'child')->widget(Select2::classname(), [
	    'data' => ArrayHelper::map(Authitem::find()->where(['type' => 1])->all(),'name','name'),
	    'options' => ['placeholder' => 'Alt Rol Seçiniz',],
	    'pluginOptions' => [
	        'allowClear' => true
	    ],
		]); 
	?>

    <div class="form-group">
        <?= Html::submitButton('Kaydet', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
