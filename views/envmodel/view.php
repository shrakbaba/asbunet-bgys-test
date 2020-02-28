<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Envmodel */
?>
<div class="envmodel-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'marka.marka',
            //'marka_id',
            'model',
        ],
    ]) ?>

</div>
