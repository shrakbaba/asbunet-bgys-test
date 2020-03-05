<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;

use app\models\Userbilgi;
use yii\models\Envcihazturu;
/* @var $this yii\web\View */
/* @var $searchModel app\models\EnvcihazlisteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cihaz Listesi';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
.rowData{
    font-size: 12px;
}
td {
    padding-top: 2px !important;
    padding-bottom: 2px !important;    
    vertical-align: middle !important;
}
</style>
<div class="row">
    <div style="float:right;padding-bottom: 25px;padding-top: 35px;"> 
        <span class="alert alert-danger small">
            <1ay
        </span>
        <span class="alert alert-info small">
            1ay< <3ay
        </span>
        <span class="alert alert-success small">
            3ay< <6ay
        </span>
    </div>
</div>
<div class="row">
<div class="envcihazliste-index">

   
    <?php Pjax::begin(['id' => 'some-id', 'timeout' => false]); ?>
    <?php
        Modal::begin([
            'id'=>'modal',
            'size'=>'modal-lg',
        ]);

            echo "<div id='modalContent'></div>";
        Modal::end();

        $gridColumns = [
            [
                'class' => 'kartik\grid\SerialColumn',
                'contentOptions' => ['class' => 'kartik-sheet-style'],  
                'width' => '2%',
                'header' => '',
                'headerOptions' => ['class' => 'kartik-sheet-style']
            ],
            [
                'attribute'=>'cihaz_turu_id',
                'format'=>'raw',
                'value'=>'cihazTuru.cihaz_turu',
                'width' => '10%',
            ],
                //'cihaz_turu_id',
            [
                'attribute'=>'marka_id',
                'format'=>'raw',
                'value'=>'marka.marka',
                'width' => '10%',
            ],
                //'marka_id',
            [
                'attribute'=>'model_id',
                'format'=>'raw',
                'value'=>'model.model',
                'width' => '20%',
            ],
            [
                'attribute'=>'konum',
                'format'=>'raw',
                'width' => '10%',
            ],
            [
                'attribute'=>'adet',
                'format'=>'raw',
                'width' => '5%',
            ],
            //'konum',
            //'adet',
            [
                'attribute' => 'garanti_bitis',
                'format' => ['date', 'php:d/m/Y'],
                'filter'=>false,
                'width' => '5%',
            ], 
            [
                    'attribute' => 'link',                
                    'format'=>'raw',
                    'width' => '5%',
                    'value' => function ($model) {   
                        if ($model->link!='')
                           return Html::a('Link', $model->link, ['target'=>'_blank']); else return 'no link';
                    },
            ], 
            [
                'attribute'=>'zimmet',
                'format'=>'raw',
                'width' => '10%',
                'value'=>function ($model) {  
                           //return @$model->zimmet0->ad." ".@$model->zimmet0->soyad;
                           return Yii::$app->params['giristipi']==1 ? @Userbilgi::findOne(['kisi_id'=>$model->zimmet])->ad.' '.@Userbilgi::findOne(['kisi_id'=>$model->zimmet])->soyad.' / '.@$model->zimmet0->username : @$model->zimmet0->ad." ".@$model->zimmet0->soyad." ".@$model->zimmet0->username; 
                    },
            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'header'=>'İşlemler',
                'width' => '10%',
                'template' => '{view}{update}{delete} ',                
                'buttons' => [                                       
                    'view' => function ($url,$model) {
                        return  ( 
                            Html::button('<span class="glyphicon glyphicon-eye-open">', ['value' => Url::to(['view','id'=>$model->id]),'class' => 'modalButton4 btn btn-success' ,'title'=>"İncele"])                      
                            );
                         },          
                    'update' => function($url, $model) {   //hertürlü
                        return 
                               // Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['title' => Yii::t('app', 'Update')])
                                Html::button('<span class="glyphicon glyphicon-pencil">', ['value' => Url::to(['update','id'=>$model->id]),'class' => 'modalButton3 btn btn-warning' ,'title'=>"Güncelle"]) ;
                            
                    },
                    'delete' => function($url, $model) {   //onaylanmamışsa ve kesin başvuru yapmamışsa
                        return  Html::a('<span class="glyphicon glyphicon-trash"></span>', 
                                                        ['delete', 'id'=>$model->id] ,
                                                        [   'class' => 'btn btn-danger',
                                                            'data-pjax' => '0',
                                                            'title'=>"Sil",
                                                            'data' => [
                                                                'confirm' => 'Bu kaydın dosyasını silmek istediğinizden emin misiniz?',
                                                                'method' => 'post',
                                                            ]
                                                        ]) ;
                            
                    } 
                ]     
            ]
        ];
        $butonlar=[
            [
                'content' => Html::a('Özet', ['dashboard'], ['class' => 'btn btn-info btn-lg',"style"=>"float:right;" ]) , 
                'options' => ['class' => 'btn-group']
            ],
            [
                'content' => Html::button('Cihaz Ekle', ['value' => Url::to(['envcihazliste/create']),'class' => 'btn btn-success btn-lg modalButton2']) , 
                'options' => ['class' => 'btn-group']
            ],
            [
                'content' => Html::button("Modeller",['class'=>'btn btn-lg btn-secondary',
                                'onclick'=>"window.location.href = '" . \Yii::$app->urlManager->createUrl(['/envmodel/index']) . "';"
                            ]), 
                'options' => ['class' => 'btn-group']
            ],
            [
                'content' =>Html::button("Markalar",['class'=>'btn btn-lg btn-warning',
                                'onclick'=>"window.location.href = '" . \Yii::$app->urlManager->createUrl(['/envmarka/index']) . "';"
                            ]), 
                'options' => ['class' => 'btn-group']
            ],
            [
                'content' => Html::button("Cihaz Türleri",['class'=>'btn btn-lg btn-danger',
                                'onclick'=>"window.location.href = '" . \Yii::$app->urlManager->createUrl(['/envcihazturu/index']) . "';"
                            ]), 
                'options' => ['class' => 'btn-group']
            ],
            //'{export}',
            //'{toggleData}',
        ];
    ?> 
       <?php 
       echo GridView::widget([
            'id' => 'kv-grid-demo',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
            'headerRowOptions' => ['class' => 'kartik-sheet-style'],
            'filterRowOptions' => ['class' => 'kartik-sheet-style'],
            'rowOptions'=>function($model){
                   if ($model->garanti_bitis < date('Y-m-d',strtotime("+1 months")))  {
                      return ['class'=>'danger'];
                  }else if ($model->garanti_bitis < date('Y-m-d',strtotime("+3 months")))  {
                      return ['class'=>'info'];
                  }else if ($model->garanti_bitis < date('Y-m-d',strtotime("+6 months")))  {
                      return ['class'=>'success'];
                  }
              },
            //'pjax' => true, // pjax is set to always true for this demo
            //'toggleDataContainer' => ['class' => 'btn-group mr-2'],
            // set export properties
            /*'export' => [
                'fontAwesome' => true,
            ],*/

            //'exportConfig' => [ GridView::EXCEL=>true, GridView::PDF=>true],
            'bordered' => true,
            'striped' => true,
            //'condensed' => $condensed,
            //'responsive' => $responsive,
            'hover' => true,
            //'showPageSummary' => true,
            'panel' => [
                    'heading' => $this->title, 
                    'type' => GridView::TYPE_PRIMARY,             
            ], 
            'toolbar'=> $butonlar,
            'columns' => $gridColumns, // check the configuration for grid columns by clicking button above
            'persistResize' => false,
            'toggleDataOptions' => ['minCount' => 10],
        ]);

        ?>
</div></div>

<?php $this->registerJs(
'function init_click_handlers(){
       $(".modalButton2").click(function() {
        //alert(fID);
            $.get(
                "create",
                function (data)
                {
                    $("#modal").find(".modal-body").html(data);
                    $(".modal-body").html(data);
                    $("#modal").modal("show");              
                }    
            );    
    }); $(".modalButton3").click(function() {
        var fID = $(this).closest("tr").data("key");
        //alert(fID);
            $.get(
                "update",
                {  id: fID   },
                function (data)
                {
                    $("#modal").find(".modal-body").html(data);
                    $(".modal-body").html(data);
                    $("#modal").modal("show");              
                }    
            );    
    });
    $(".modalButton4").click(function() {
        var fID = $(this).closest("tr").data("key");
        //alert(fID);
            $.get(
                "view",
                {  id: fID   },
                function (data)
                {
                    $("#modal").find(".modal-body").html(data);
                    $(".modal-body").html(data);
                    $("#modal").modal("show");              
                }    
            );    
    });
};

init_click_handlers(); //first run
$("#some_pjax_id").on("pjax:success", function() {
  init_click_handlers(); //reactivate links in grid after pjax update
});

');?>

    <?php Pjax::end(); ?>
<style type="text/css">
    td a span {
        color: #f9fafc !important;
    }
    .panel-primary > .panel-heading {
        background-color: #772043 !important;
        border-color: #772043 !important;
    }
    .panel-primary {
        border-color: #772043 !important;
        margin-top: 10px;
    }
    tr th a{
        color: #3c8dbc !important;
    }
   
</style>