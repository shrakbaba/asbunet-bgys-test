<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Bgysolasilik */

?>
<div class="bgysolasilik-view">


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'deger',
            'basamak',
        ],
    ]) ?>

</div>
