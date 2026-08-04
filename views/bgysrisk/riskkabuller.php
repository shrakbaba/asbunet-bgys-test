<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BgysriskkabulSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Risk Kabuller';
$this->params['breadcrumbs'][] = ['label' => 'Riskler', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .bgysriskkabul-index .grid-view th,
    .bgysriskkabul-index .grid-view th a {
        color: #dd4b39 !important;
    }
    .bgysriskkabul-index .grid-view td:last-child,
    .bgysriskkabul-index .grid-view th:last-child {
        width: 96px;
        min-width: 96px;
        white-space: nowrap;
        text-align: center;
    }
    .bgysriskkabul-index .grid-view td:last-child .btn {
        margin-right: 2px;
    }
</style>

<div class="bgysriskkabul-index">

    <h3><?= Html::encode($this->title) ?></h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php
    Modal::begin([
        'id' => 'risk-kabul-modal',
        'size' => 'modal-lg',
    ]);

    echo "<div id='risk-kabul-modal-content'></div>";
    Modal::end();
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
                'attribute'=>'riskid',
                'label'=>'Risk No',
                'headerOptions' => ['style' => 'width:110px'],
                'contentOptions' => ['style' => 'width:110px'],
            ],
            [
                'attribute'=>'risk_adi',
                'label'=>'Risk',
                'format'=>'raw',
                'headerOptions' => ['style' => 'width:35%'],
                'value'=>function ($data)
                    {
                        return @$data->risk->risk;
                    }
            ], 
            'aciklama',
            //'kabuleden',
            [
                'attribute'=>'kabuleden_adi',
                'label'=>'Kabul Eden',
                'format'=>'raw',
                'headerOptions' => ['style' => 'width:12%'],
                'value'=>function ($data)
                    {
                        return @$data->kabuleden0->username;
                    }
            ], 
            //'tarih',
            [
                'attribute'=>'tarih',
                'format' => ['date', 'php:d/m/Y'],
                'filter'=>false,
                'headerOptions' => ['style' => 'width:90px'],
                'contentOptions' => ['style' => 'width:90px'],
            ],

            ['class' => 'yii\grid\ActionColumn',
                'header'=>'İşlemler',
                'headerOptions' => ['style' => 'width:96px; white-space:nowrap; text-align:center;'],
                'contentOptions' => ['style' => 'width:96px; white-space:nowrap; text-align:center;'],

                'template' =>'{view}{update}{delete}',

                'buttons' => [   
                    'view' => function($url, $model) {
                        return Html::button('<span class="glyphicon glyphicon-eye-open"></span>', [
                            'value' => Url::to(['riskkabulview', 'id' => $model->id]),
                            'class' => 'risk-kabul-modal-button btn btn-success btn-xs',
                            'title' => 'Görüntüle',
                        ]);
                    },
                    'update' => function($url, $model) {
                        if (!Yii::$app->user->can('BGYS_Yonetim_Temsilcisi')) {
                            return '';
                        }
                        return Html::button('<span class="glyphicon glyphicon-pencil"></span>', [
                            'value' => Url::to(['riskkabulupdate', 'id' => $model->id]),
                            'class' => 'risk-kabul-modal-button btn btn-warning btn-xs',
                            'title' => 'Güncelle',
                        ]);
                    },
                    'delete' => function($url, $model) {   //onaylanmamışsa ve kesin başvuru yapmamışsa
                        if (!Yii::$app->user->can('BGYS_Yonetim_Temsilcisi')) {
                            return '';
                        }
                        return 
                            Html::a('<span class="glyphicon glyphicon-trash"></span>', ['riskkabuldelete', 'id'=>$model->id],[
                                'class'=>'btn btn-danger btn-xs',
                                'title' => 'Sil',
                                'data-bgys-confirm-title' => '<span class="glyphicon glyphicon-warning-sign"></span> Silme Onayı',
                                'data-bgys-confirm-description' => 'Bu işlem geri alınamaz.',
                                'data-bgys-confirm-ok' => 'Evet, Sil',
                                'data-bgys-confirm-class' => 'btn-danger',
                                'data' => [
                                    'confirm' => 'Bu risk kabul kaydını silmek istediğinizden emin misiniz?',
                                    'method' => 'post',
                                ],
                            ] );
                    },  
                ],

            ],
        ],
	    ]); ?>
</div>

<?php
$this->registerJs(<<<JS
(function () {
    var adres = new URL(window.location.href);
    var eskiParametre = 'BgysriskkabulSearch[kabuleden]';
    if (adres.searchParams.has(eskiParametre)) {
        adres.searchParams.delete(eskiParametre);
        window.location.replace(adres.pathname + (adres.search ? adres.search : ''));
    }
})();
$(document).off('click.riskKabulModal', '.risk-kabul-modal-button').on('click.riskKabulModal', '.risk-kabul-modal-button', function(e) {
    e.preventDefault();
    $('#risk-kabul-modal-content').html('<div class="text-center" style="padding:20px;">Yükleniyor...</div>');
    $('#risk-kabul-modal').modal('show')
        .find('#risk-kabul-modal-content')
        .load($(this).attr('value'));
});
JS
);
?>
