<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use app\models\Userbilgi;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\Bgysrisk;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BgysriskSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Riskler';
$this->params['breadcrumbs'][] = $this->title;
?>
<style type="text/css">
    .rowData{
        font-size: 10px;
    }
</style>

<div class="bgysrisk-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <p>
        <?php // Html::button('Risk Ekle', ['value' => Url::to(['/bgysrisk/create']),'class' => 'btn btn-success btn-lg','id'=>'modalButton']) ?>
        <?php // Html::a('Risk Kabuller', ['riskkabuller'], ['class' => 'btn btn-warning']) ?>
        <?php // echo Html::a('Dif Ekle', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php 

    $gridColumns = [
        [
            'class' => 'kartik\grid\SerialColumn',
            'contentOptions' => ['class' => 'kartik-sheet-style'],  
            //'width' => '2%',
            'header' => '',
            'headerOptions' => ['class' => 'kartik-sheet-style']
        ],
        [
            'class' => 'kartik\grid\ExpandRowColumn',
            //'width' => '2%',
            'value' => function ($model, $key, $index, $column) {
                return GridView::ROW_COLLAPSED;
            },
            'detail' => function ($model, $key, $index, $column) {
                $iliskiler=Bgysrisk::iliskiler();
                return Yii::$app->controller->renderPartial('_expand-row-details', ['model' => $model,'iliskiler' => $iliskiler]);
            },
            'headerOptions' => ['class' => 'kartik-sheet-style'], 
            'expandOneOnly' => true
        ],
        'id',                
        [
            'attribute'=>'varlik',
            'format'=>'raw',
            'value'=>function ($data)
                {
                    return $data->varlik0->varlik_adi;
                }
        ], 
                //'departman',
                'risk',
                [
                    'attribute'=>'risk_sorumlusu',
                    'format'=>'raw',
                    //'filter'=>false,
                    'value'=>function ($model) {  
                           //return @$model->zimmet0->ad." ".@$model->zimmet0->soyad;
                           return Yii::$app->params['giristipi']==1 ? @Userbilgi::findOne(['kisi_id'=>$model->risk_sorumlusu])->ad.' '.@Userbilgi::findOne(['kisi_id'=>$model->risk_sorumlusu])->soyad.' / '.$model->riskSorumlusu->username : @$model->riskSorumlusu->ad." ".@$model->riskSorumlusu->soyad." ".@$model->riskSorumlusu->username; 
                    },
                ], 
                //'risk_nedeni',
                [
                    'attribute'=>'riskdegeri_onceki',
                    'format'=>'raw',
                    //'filter'=>false,
                    'value'=>function ($model) {  
                           //return @$model->zimmet0->ad." ".@$model->zimmet0->soyad;
                           return $model->riskdegeri_onceki==null? "":$model->riskdegeri_onceki;
                    },
                ], 
                [
                    'attribute'=>'riskdegeri_sonraki',
                    'format'=>'raw',
                    //'filter'=>false,
                    'value'=>function ($model) {  
                           //return @$model->zimmet0->ad." ".@$model->zimmet0->soyad;
                           return $model->riskdegeri_sonraki==null? "":$model->riskdegeri_sonraki;
                    },
                ], 
                [
                    'attribute'=>'ozetdurum',
                    'format'=>'raw',
                    'filter'=>array(1 => "Risk Azalmış" ,2=>'Risk Artmış',3=>'Değişim Yok',4=>'Risk Kabul'),
                    'value'=>function ($data)
                        {
                             if ($data->ozetdurum==2) {
                                return '<span class="glyphicon glyphicon-arrow-up" style="color:green"></span>';
                            }elseif ($data->ozetdurum==1) {
                               return '<span class="glyphicon glyphicon-arrow-down" style="color:red"></span>';
                            }elseif ($data->ozetdurum==4){
                                return '<span class="glyphicon glyphicon-ok" style="color:brown"></span>';
                            }else{
                                return '<span class="glyphicon glyphicon-resize-horizontal" style="color:blue"></span>';
                            }
                        }
                ], 

                ['class' => 'yii\grid\ActionColumn',
                    'template' =>'{view} {update} {delete} {difac}',
                    'headerOptions' => ['style' => 'width:8%'],
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
                        'difac' => function($url, $model) {   //onaylanmamışsa ve kesin başvuru yapmamışsa
                            return 
                                Html::a('<span title="Dif Aç" class="glyphicon glyphicon-th-list"></span>', ['/bgysdiftalep/difac', 'id'=>$model->id] ,['class'=>'btn btn-info btn-xs' ]);
                                //Html::button('<span title="Dif Aç" class="glyphicon glyphicon-th-list"></span>', ['value' => Url::to(['/bgysdiftalep/difac?id='.$model->id]),'class' => 'btn btn-success','id'=>'modalButton2']) ;
                        },  
                    ],

                ],
    ];

    Modal::begin([
        'id'=>'modal',
        'size'=>'modal-lg',
    ]);

        echo "<div id='modalContent'></div>";
    Modal::end();
    ?>

    <?php Pjax::begin(); ?>
    <?php 
    echo GridView::widget([
    'id' => 'kv-grid-demo',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'resizableColumns'=>true,
    'rowOptions'=>function($model){
        if ($model->riskdegeri_onceki >= 68 and $model->riskdegeri_onceki <= 100)  {
              return ['class'=>'danger rowData'];
            }else if ($model->riskdegeri_onceki >= 35 and $model->riskdegeri_onceki <= 67)  {
              return ['class'=>'warning rowData'];
            }else if ($model->riskdegeri_onceki < 34)  {
              return ['class'=>'success rowData'];
            }
    },
    'columns' => $gridColumns, // check the configuration for grid columns by clicking button above
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
    'exportConfig' => [ GridView::EXCEL=>true, GridView::PDF=>true],
    'bordered' => true,
    'striped' => true,
    //'condensed' => $condensed,
    //'responsive' => $responsive,
    'hover' => true,
    //'showPageSummary' => true,
    'panel' => [
        'heading' => 'Risk Analizi', 
        'type' => GridView::TYPE_PRIMARY, 
        
    ], 
    'persistResize' => false,
    'toggleDataOptions' => ['minCount' => 10],
    'toolbar' =>  [
        [
            'content' =>
                Html::button('Risk Ekle', ['value' => Url::to(['/bgysrisk/create']),'class' => 'btn btn-success','id'=>'modalButton']) .' ' .
                Html::a('Risk Kabuller', ['riskkabuller'], ['class' => 'btn btn-warning'])   , 
            'options' => ['class' => 'btn-group']
        ],
        '{export}',
        '{toggleData}',
    ],
]);
    ?>
    <?php Pjax::end(); ?>
</div>

<style type="text/css">

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