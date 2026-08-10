<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Envcihazturu */

?>
<div class="envcihazturu-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
           // 'id',
            'cihaz_turu',
            [
                'attribute' => 'asset_type',
                'value' => \app\models\Bgysvarlikenvanteri::assetTypeOptions()[$model->asset_type] ?? 'Sınıflandırılmamış',
            ],
        ],
    ]) ?>

</div>
