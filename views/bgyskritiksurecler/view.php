<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Bgyskritiksurecler */

/*$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Bgyskritiksureclers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;*/
\yii\web\YiiAsset::register($this);
?>
<div class="bgyskritiksurecler-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'surec',
            'keks',
            'kevk',
            'etkisi',
            'ilkaksiyon',
            'yedeklilik',
            'ulasilacaklar',
            'ekleme_tarihi',
        ],
    ]) ?>

</div>
