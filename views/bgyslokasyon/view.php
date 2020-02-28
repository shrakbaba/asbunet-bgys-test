<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Bgyslokasyon */
?>
<div class="bgyslokasyon-view">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'lokasyon',
        ],
    ]) ?>

</div>
