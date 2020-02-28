<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Bgysdiskaynaklidokuman */

\yii\web\YiiAsset::register($this);
?>
<div class="bgysdiskaynaklidokuman-view">


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'dokumanadi',
            'kurum',
            'sorumlu',
            'link',
            'not',
        ],
    ]) ?>

</div>
