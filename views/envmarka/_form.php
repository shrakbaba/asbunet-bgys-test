<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Envcihazturu;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Envmarka */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="envmarka-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'marka')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cihaz_turu_ids')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(Envcihazturu::find()->orderBy('cihaz_turu')->all(), 'id', 'cihaz_turu'),
        'options' => ['multiple' => true, 'placeholder' => 'Bu markanın kullanılacağı cihaz türlerini seçin'],
        'pluginOptions' => ['allowClear' => true],
    ])->label('Cihaz Türleri') ?>

    <div class="form-group">
        <?= Html::submitButton('Kaydet', ['class' => 'btn btn-success btn-lg']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
