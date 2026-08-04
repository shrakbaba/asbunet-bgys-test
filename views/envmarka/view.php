<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Envmarka */

?>
<div class="envmarka-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
           // 'id',
            'marka',
        ],
    ]) ?>

</div>
