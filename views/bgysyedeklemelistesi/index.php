<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\Userbilgi;
/* @var $this yii\web\View */
/* @var $searchModel app\models\BgysyedeklemelistesiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Yedekleme Listesi';
$this->params['breadcrumbs'][] = $this->title;
?><style type="text/css">
   
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
.rowData{
    font-size: 12px;
}
td {
    padding-top: 2px !important;
    padding-bottom: 2px !important;    
    vertical-align: middle !important;
}
</style>
<div class="bgysyedeklemelistesi-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <p>
        <?php 
          echo Html::button('Ekle', ['value' => Url::to(['create']),'class' => 'btn btn-lg btn-success modalButton2' ,'style'=>"margin-bottom:5px;"]);  
          //echo Html::a('Create Bgysfarkindalikquiz', ['create'], ['class' => 'btn btn-success']);
        ?>
    </p>
    <?php
   // $a=22222;
    Modal::begin([
        //'header'=>"<h2>Özel İlgi Grubu veya Otorite Ekle</h2>",
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
                        if ($model->periyodu==1) return ['class'=>'success rowData'];
                            elseif ($model->periyodu==2) return ['class'=>'warning rowData'];
                                elseif ($model->periyodu==3) return ['class'=>'danger rowData'];
                                    elseif ($model->periyodu==4) return ['class'=>' rowData'];
                                        elseif ($model->periyodu==5) return ['class'=>'info rowData'];
                                                    
        
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn',            
                'headerOptions' => ['style' => 'width:2%'],
            ],
            [
                'attribute' => 'yedekalinacak',
                'headerOptions' => ['style' => 'width:20%'],
            ],
            [
                'attribute'=>'yedeklemesekli',
                'headerOptions' => ['style' => 'width:10%'],
                'format'=>'raw',                
                'filter'=>array(1 =>"Full-Incremental", 2 =>"Differantial", 3 =>"Full"),
                'value'=>function ($data)
                    {
                        return 
                            $data->yedeklemesekli==1 ? "Full-Incremental"
                            : ( $data->yedeklemesekli==2 ?  "Differantial" 
                                : ( $data->yedeklemesekli==3 ? "Full"
                                    :  ""                                    
                                ) 
                            )
                        ; 
                    }
            ],
            [
                'attribute' => 'yedekleme_yontemi',
                'headerOptions' => ['style' => 'width:15%'],
            ],
            [
                'attribute'=>'periyodu',
                'headerOptions' => ['style' => 'width:10%'],
                'format'=>'raw',                
                'filter'=>array(1 =>"2 Saatlik", 2 =>"Günlük", 3 =>"Haftalık", 4 =>"Aylık", 5 =>"Tek yedek"),
                'value'=>function ($data)
                    {
                        return 
                            $data->periyodu==1 ? "2 Saatlik"
                                : ( $data->periyodu==2 ? "Günlük"
                                    :( $data->periyodu==3 ? "Haftalık" 
                                            :( $data->periyodu==4 ? "Aylık" 
                                                :( $data->periyodu==5 ? "Tek yedek" 
                                                    : ""
                                                )
                                            )
                                        )
                                    )
                        ; 
                    }
            ],            
            [
                'attribute' => 'yedeklemeyeri',
                'headerOptions' => ['style' => 'width:15%'],
            ],
            [
                'attribute' => 'yedeklemezamani',
                'headerOptions' => ['style' => 'width:10%'],
                'value'=>function ($data)
                    {
                        return 
                            $data->yedeklemezamani==1 ? "08:00-17:00"
                            : ( $data->yedeklemezamani==2 ?  "Haftaiçi günler" 
                                : ( $data->yedeklemezamani==3 ? "Haftanın her günü"
                                    :( $data->yedeklemezamani==4 ? "Her ayın ilk günü" 
                                        :( $data->yedeklemezamani==5 ? "Her çarşamba" 
                                            :( $data->yedeklemezamani==6 ? "Tek yedek" 
                                                :""
                                            )
                                        )
                                    )
                                ) 
                            )
                        ; 
                    }
            ],
            [
                'attribute' => 'sorumlu',
                'headerOptions' => ['style' => 'width:10%'],
                'value'=>function ($data)
                    {
                        return Userbilgi::findOne(['kisi_id'=>$data->sorumlu])->ad.' '.@Userbilgi::findOne(['kisi_id'=>$data->sorumlu])->soyad.' / '.@$data->sorumlu0->username ;
                    },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
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
    <?php Pjax::end(); ?>
</div>

<?php $this->registerJs(
'function init_click_handlers(){
    $(".modalButton5").click(function() {
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
