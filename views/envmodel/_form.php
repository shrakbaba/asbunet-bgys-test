<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use yii\helpers\ArrayHelper;
use app\models\Envmarka;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $model app\models\Envmodel */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="envmodel-form">

    <?php $form = ActiveForm::begin(); ?>
   
          <?= $form->field($model, 'marka_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(Envmarka::find()->all(),'id','marka'),
        'options' => ['placeholder' => 'Marka Seçiniz',],
        'pluginOptions' => [
            'allowClear' => true
        ],
        ]); 
    ?>

    <?= $form->field($model, 'model')->textInput(['maxlength' => true]) ?>


    <div class="form-group">
        <?= Html::submitButton('Kaydet', ['class' => 'btn btn-success btn-lg']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
