<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Bgysilgigrup */

/*$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Özel İlgi Grupları veya Otoriteler', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;*/
\yii\web\YiiAsset::register($this);
?>
<div class="bgysilgigrup-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'grupadi',
            'iletisimbirimi',
            'telefon',
            'grup_web',
            'ilgi_konusu',
            'iletisimegecme_durumu',
            'etkilenecek_surecler',
            'ekleme_tarihi',
        ],
    ]) ?>

</div>
