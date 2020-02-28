<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Bgysbilgisinifi */
?>
<div class="bgysbilgisinifi-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'adi',
            'aciklama',
            'erisimhaklari',
            'saklama',
            'iletim',
            'imha',
        ],
    ]) ?>

</div>
