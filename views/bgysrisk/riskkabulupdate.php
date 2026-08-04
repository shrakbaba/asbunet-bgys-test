<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Bgysriskkabul */
?>
<div class="bgysriskkabul-update">
    <h3>Risk Kabul Güncelle</h3>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'riskid')->textInput() ?>

    <div class="form-group">
        <label class="control-label">Risk</label>
        <div class="form-control" style="height:auto; min-height:34px;"><?= Html::encode(@$model->risk->risk) ?></div>
    </div>

    <?= $form->field($model, 'aciklama')->textArea(['rows' => 3, 'maxlength' => true]) ?>
    <?= $form->field($model, 'kabuleden')->textInput() ?>
    <?= $form->field($model, 'tarih')->textInput(['placeholder' => 'YYYY-AA-GG']) ?>

    <div class="form-group">
        <?= Html::submitButton('Kaydet', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
