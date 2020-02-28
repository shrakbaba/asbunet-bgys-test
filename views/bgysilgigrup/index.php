<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BgysilgigrupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Özel İlgi Grupları veya Otoriteler';
$this->params['breadcrumbs'][] = $this->title;
?>

<style type="text/css">
.rowData{
    font-size: 12px;
}
td {
    padding-top: 2px !important;
    padding-bottom: 2px !important;    
    vertical-align: middle !important;
}
</style>
<div class="bgysilgigrup-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <p>
        <?= Html::button('Ekle', ['value' => Url::to(['create']),'class' => 'btn btn-sm btn-success modalButton2' ,'style'=>"margin-bottom:5px;"])  ?>
    </p>

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
        'rowOptions'=>function($model){
            return ['class'=>'rowData'];                                         
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn',
                'headerOptions' => ['style' => 'width:2%'],
            ],

            //'id',
            //'grupadi',
            [
                'attribute' => 'grupadi',
                'headerOptions' => ['style' => 'width:15%'],
            ],
            //'iletisimbirimi',
            //'telefon',
            [
                'attribute' => 'telefon',
                'headerOptions' => ['style' => 'width:10%'],
            ],
            //'grup_web',
            [
                'attribute' => 'grup_web',
                'headerOptions' => ['style' => 'width:25%'],
            ],
            //'ilgi_konusu',
            [
                'attribute' => 'ilgi_konusu',
                'headerOptions' => ['style' => 'width:35%'],
            ],
            //'iletisimegecme_durumu',
            //'etkilenecek_surecler',
            //'ekleme_tarihi',            

            ['class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['style' => 'width:8%'],
                'template' => '{view}{update}{delete}' ,  
                'buttons' => [                                      
                    'view' => function ($url,$model) {
                        return  ( 
                            // Html::a('<span class="glyphicon glyphicon-eye-open">', ['view','id'=>$model->id], ['class' => 'btn btn-success','title'=>"İncele"] )

                            Html::button('<span class="glyphicon glyphicon-eye-open">', ['value' => Url::to(['view','id'=>$model->id]),'class' => 'modalButton4 btn btn-success' ,'title'=>"İncele"])                      
                            );
                         },
                    'update' => function ($url,$model) {
                        return  ( 
                            Html::button('<span class="glyphicon glyphicon-pencil">', ['value' => Url::to(['update','id'=>$model->id]),'class' => 'modalButton3 btn btn-warning' ,'title'=>"Güncelle"])                         
                            );
                         },
                    'delete' => function ($url,$model) {
                        return  (  
                            Html::a('<span class="glyphicon glyphicon-trash"></span>', 
                                                ['delete', 'id'=>$model->id] ,
                                                [   'class' => 'btn btn-danger',
                                                    'data-pjax' => '0',
                                                    'title'=>"Sil",
                                                    'data' => [
                                                        'confirm' => 'Bu kaydın dosyasını silmek istediğinizden emin misiniz?',
                                                        'method' => 'post',
                                                    ]
                                                ])                     
                            );
                         },
                ]
            ],
        ],
    ]); ?>
</div>
