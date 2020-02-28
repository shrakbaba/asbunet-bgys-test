<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Bgysdepartman */

?>
<div class="bgysdepartman-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'departman',
        ],
    ]) ?>

</div>
