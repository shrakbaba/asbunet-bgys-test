<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Bgyseylemmatrisi */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bgyseylemmatrisi-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'altdeger')->textInput() ?>

    <?= $form->field($model, 'ustdeger')->textInput() ?>

    <?= $form->field($model, 'eylem')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'aciklama')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Kaydet', ['class' => 'btn btn-success btn-lg']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
