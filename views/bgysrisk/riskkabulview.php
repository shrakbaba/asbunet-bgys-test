<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Bgysriskkabul */
?>
<div class="bgysriskkabul-view">
    <h3>Risk Kabul Bilgisi</h3>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'riskid',
                'label' => 'Risk No',
            ],
            [
                'label' => 'Risk',
                'value' => @$model->risk->risk,
            ],
            'aciklama',
            [
                'attribute' => 'kabuleden',
                'value' => @$model->kabuleden0->username,
            ],
            [
                'attribute' => 'tarih',
                'format' => ['date', 'php:d/m/Y'],
            ],
        ],
    ]) ?>

    <div class="form-group">
        <?= Html::button('Tamam', ['class' => 'btn btn-default', 'data-dismiss' => 'modal']) ?>
    </div>
</div>
