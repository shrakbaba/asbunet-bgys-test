<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Bgysrisk */
?>

<div class="bgysrisk-pasif-form">

    <h3>Risk Pasif Yapma Açıklaması</h3>
    <p><strong>Risk No:</strong> <?= Html::encode($model->id) ?></p>
    <p><strong>Risk:</strong> <?= Html::encode($model->risk) ?></p>

    <?php $form = ActiveForm::begin(); ?>

    <!-- Eğer tablonda pasif_aciklama kolonu varsa -->
    <div class="form-group">
        <label>Pasif Yapma Açıklaması</label>
        <textarea name="pasif_aciklama" class="form-control" rows="4"></textarea>
    </div>

    <div class="form-group" style="margin-top: 15px;">
        <?= Html::submitButton('Pasif Yap', ['class' => 'btn btn-danger']) ?>
        <?= Html::button('Vazgeç', [
            'class' => 'btn btn-default',
            'data-bs-dismiss' => 'modal',
            'data-dismiss' => 'modal', // bootstrap 3/4 için
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
