<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;

$this->title = 'Dış Kaynaklı Dokümanlar';
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
<div class="bgysdiskaynaklidokuman-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::button('Dış Kaynaklı Doküman', ['value' => Url::to(['create']),'class' => 'btn btn-sm btn-success modalButton2' ,'style'=>"margin-bottom:5px;"])  ?>
    </p>

    <?php
    Modal::begin([
        'id'=>'modal',
        'size'=>'modal-lg',
    ]);

        echo "<div id='modalContent'></div>";
    Modal::end();
    ?>
    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'id',
            'dokumanadi',
            'kurum',
            'sorumlu',
            //'link',         
            [
                    'attribute' => 'link',                
                    'format'=>'raw',
                    'value' => function ($model) {   
                        if ($model->link!='')
                           return Html::a('Link', $model->link, ['target'=>'_blank']); else return 'no link';
                    },
            ], 
            //'not',

            ['class' => 'yii\grid\ActionColumn',
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
    });
    $(".modalButton3").click(function() {
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