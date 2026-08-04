<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BgysfarkindalikquizSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Farkındalık Eğitim Sonuçları';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bgysfarkindalikquiz-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php
    Modal::begin([
        'id'=>'modal',
        'size'=>'modal-lg',
    ]);

        echo "<div id='modalContent'></div>";
    Modal::end();
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
                'label' => 'Eğitim',
                'value' => function ($model) {
                    return $model->egitim ? $model->egitim->baslik : '(Veri Yok)';
                },
            ],
            [
                'attribute' => 'cevaplayan',
                'label' => 'Ad Soyad',
                'value' => function ($model) {
                    return $model->cevaplayanAdSoyad;
                },
            ],
            //'ip',
            'puan',
            //'cevaplamatarihi',
            [
                'attribute' => 'cevaplamatarihi',
                'format' => ['date', 'php:d/m/Y H:i:s']
            ],
            //'cevaplar',

            ['class' => 'yii\grid\ActionColumn',
                'header'=>'İşlemler',

                'template' =>'{view} {delete} ',

                'buttons' => [
                    'view' => function($url, $model) {
                        return Html::button('<span class="glyphicon glyphicon-eye-open"></span>', [
                            'value' => Url::to(['view', 'id' => $model->id]),
                            'class' => 'bgysfarkindalikquiz-modal btn btn-success btn-xs',
                            'title' => 'Görüntüle',
                        ]);
                    },
                    'delete' => function($url, $model) {
                        return                             
                            Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                'class' => 'btn btn-danger btn-xs',
                                'title' => 'Sil',
                                'data' => [
                                    'confirm' => 'Bu kayıdı silmek istediğinizden emin misiniz?',
                                    'method' => 'post',
                                ],
                            ]);
                    }, 
                ],

            ],
        ],
    ]); ?>
    
</div>
<?php
$this->registerJs(<<<JS
$(document).off('click.bgysfarkindalikquizModal', '.bgysfarkindalikquiz-modal').on('click.bgysfarkindalikquizModal', '.bgysfarkindalikquiz-modal', function(e) {
    e.preventDefault();
    $('#modalContent').html('<div class="text-center" style="padding:20px;">Yükleniyor...</div>');
    $('#modal').modal('show')
        .find('#modalContent')
        .load($(this).attr('value'));
});
JS
);
?>
