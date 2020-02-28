<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Bgyseylemmatrisi */
?>
<div class="bgyseylemmatrisi-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'altdeger',
            'ustdeger',
            'eylem',
            'aciklama',
        ],
    ]) ?>

</div>
