<?php

use yii\helpers\Html;
use app\models\BgysizlemeolcmeSearch;
use yii\helpers\Url;
use yii\helpers\bgys;
use kartik\grid\GridView;
use app\models\Bgysrisk;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use app\models\Userbilgi;
/* @var $this yii\web\View */
/* @var $searchModel app\models\BgysizlemeolcmeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="bgysizlemeolcme-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<style type="text/css">
    td a span {
    color: #f9fafc !important;
}

.panel-primary > .panel-heading {
    background-color: #8a3939;
    border-color: #8a3939;
}
.panel-primary {
    border-color: #8a3939;
}

a{
    color: #8a3939;
}
a:-webkit-any-link {
    text-decoration: none;
    color: #8a3939;
}

</style>
   <?php Pjax::begin(); ?>
    <?php 
    //echo $yil;exit;
        //$yil=2019;
        $searchModel = new BgysizlemeolcmeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['yil' => $yil]);

        $gridColumns=[
            [
                'class' => 'kartik\grid\SerialColumn',
                'contentOptions' => ['class' => 'kartik-sheet-style'],  
                'width' => '2%',
                'header' => '',
                'headerOptions' => ['class' => 'kartik-sheet-style']
            ],
            [
                'attribute'=>'kontrol',
                'format'=>'raw',     
                'width' => '20%'                
            ],
            [
                'attribute'=>'hedef_degeri',
                'format'=>'raw',     
                'width' => '10%'                
            ],
            [
                'attribute'=>'olcum_sikligi',
                'format'=>'raw',     
                'width' => '5%',             
                'value'=>function ($data)
                    {
                        return 
                            $data->olcum_sikligi==1 
                            ? "Yılda 1"
                            : ( $data->olcum_sikligi==2 
                                ?  "6 Ayda bir" 
                                : ( $data->olcum_sikligi==3 
                                    ? "3 Ayda 1"
                                    :( $data->olcum_sikligi==4 
                                        ? "Ayda 1" 
                                        : ""
                                    )
                                ) 
                            )
                        ; 
                    }
            ],
            [
                'attribute'=>'planlanan_tarihi',   
                'width' => '5%',  
                'format' => ['date', 'php:d/m/Y'], 
            ],
            [
                'attribute'=>'kontrol_kriteri',   
                'width' => '25%',  
            ],
            //'planlanan_tarihi',
            //'olcum_sonucu',
            //'kontrol_kriteri',
            [
                'attribute'=>'sorumlu',
                'value'=>function ($data)
                    {
                        return @Userbilgi::findOne(['kisi_id'=>$data->sorumlu])->ad.' '.@Userbilgi::findOne(['kisi_id'=>$data->sorumlu])->soyad.' / '.@$data->sorumlu0->username ;
                    },
                'label'=>'Sorumlu', 
                'width' => '15%',  
            ],
            //'olusturma_tarihi',                    
            [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'İşlemler',
                'contentOptions' => ['class' => 'bgys-grid-actions'],
                'template' => '{view}{update}{delete}{kayitgir}' ,  
                'buttons' => [                                      
                    'view' => function ($url,$model) {
                        return  ( 
                            // Html::a('<span class="glyphicon glyphicon-eye-open">', ['view','id'=>$model->id], ['class' => 'btn btn-success','title'=>"İncele"] )

                            Html::button('<span class="glyphicon glyphicon-eye-open">', ['value' => Url::to(['view','id'=>$model->id]),'class' => 'modalButton4 btn btn-success btn-xs' ,'title'=>"İncele"])                      
                            );
                         },
                    'update' => function ($url,$model) {
                        return  ( 
                            Html::button('<span class="glyphicon glyphicon-pencil">', ['value' => Url::to(['update','id'=>$model->id]),'class' => 'modalButton3 btn btn-warning btn-xs' ,'title'=>"Güncelle"])                         
                            );
                         },
                    'delete' => function ($url,$model) {
                        return  (  
                            Html::a('<span class="glyphicon glyphicon-trash"></span>', 
                                                ['delete', 'id'=>$model->id] ,
                                                [   'class' => 'btn btn-danger btn-xs',
                                                    'data-pjax' => '0',
                                                    'title'=>"Sil",
                                                    'data' => [
                                                        'confirm' => 'Bu kaydın dosyasını silmek istediğinizden emin misiniz?',
                                                        'method' => 'post',
                                                    ]
                                                ])                     
                            );
                         },
                    'kayitgir' => function ($url,$model) {
                        return  (  
                            ((bgys::olcmesorumlumu($model->id) or Yii::$app->user->can('bilgiislem_admin')) and !bgys::islemgirilmemis($model->id)) 
                            ?
                                Html::a('<span class="glyphicon glyphicon-thumbs-up"></span>', 
                                    ['kayitgir', 'id'=>$model->id] ,
                                    [   'class' => 'btn btn-info btn-xs',
                                        'data-pjax' => '0',
                                        'title'=>"İşlem Gir",
                                        'data' => [
                                            'confirm' => 'Bu kayıt için sonuç girmek istediğinizden emin misiniz?',
                                            'method' => 'post',
                                        ]
                                    ]) 
                            :""                    
                            );
                         }
                ]
            ],
        ];
    ?>


    <?php echo GridView::widget([
        'id' => 'kv-grid-demo',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'resizableColumns'=>true,
        'columns' => $gridColumns,
        'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
        'headerRowOptions' => ['class' => 'kartik-sheet-style'],
        'filterRowOptions' => ['class' => 'kartik-sheet-style'],   
        //'pjaxSettings' => ['options' => ['enablePushState' => false, 'id' => 'gv-maintain-id']], 
        'pjaxSettings'=>[
            'neverTimeout'=>true,
            //'beforeGrid'=>'Branches Data',
            //'afterGrid'=>'My fancy content after.',
            'enablePushState' => false,
        ],
        'pjax' => true, // pjax is set to always true for this demo
        // set your toolbar
        //'exportConfig' => [ GridView::EXCEL=>true, GridView::PDF=>true],
        'bordered' => true,
        'striped' => true,
        //'condensed' => $condensed,
        //'responsive' => $responsive,
        'hover' => true,
        //'showPageSummary' => true,
        'panel' => [
            'heading' => 'Analiz Listesi', 
            'type' => GridView::TYPE_PRIMARY, 
            
        ], 
        'persistResize' => false,
        'toggleDataOptions' => ['minCount' => 10],
        'toolbar' =>  [
            //'{export}',
            //'{toggleData}',
        ],
    ]);  ?>
    <?php Pjax::end(); ?>
</div>


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
    $(".mdBtnKayitGir").click(function() {
        var fID = $(this).closest("tr").data("key");
        //alert(fID);
            $.get(
                "kayitgir",
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
