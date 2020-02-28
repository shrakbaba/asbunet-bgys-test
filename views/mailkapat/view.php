<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Mailkapat */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Kapatılacak Mailler', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mailkapat-view">

    <h1><?= Html::encode($this->title) ?></h1>

     <p>
        <?= Html::a('Güncelle', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Sil', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Bu veriyi silmek istediğinizden emin misiniz?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
           // 'id',
            'mailhesabi',
            [
                'attribute' => 'ayrilistarihi',
                'format' => ['date', 'php:d/m/Y']
            ], 
            [
                'attribute'=>'kapatildi',
                'format'=>'raw',
                'filter'=>array(0=>'Hayır',1=>'Evet'),
                'value'=>function ($data)
                    {
                        return $data->kapatildi==0? "Hayır":"Evet"; 
                    }
            ],
        ],
    ]) ?>

</div>
