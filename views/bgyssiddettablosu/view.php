<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Bgyssiddettablosu */
?>
<div class="bgyssiddettablosu-view">


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
           // 'id',
            'anlam',
            'gizlilik',
            'butunluk',
            'erisilebilirlik',
        ],
    ]) ?>

</div>
