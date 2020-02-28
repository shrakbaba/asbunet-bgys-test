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
        $dataProvider->query->where('yil = '.$yil);

        $gridColumns=[
            [
                'class' => 'kartik\grid\SerialColumn',
                'contentOptions' => ['class' => 'kartik-sheet-style'],  
                //'width' => '2%',
                'header' => '',
                'headerOptions' => ['class' => 'kartik-sheet-style']
            ],
            /*[
                'class' => 'kartik\grid\ExpandRowColumn',
                //'width' => '2%',
                'value' => function ($model, $key, $index, $column) {
                    return GridView::ROW_COLLAPSED;
                },
                'detail' => function ($model, $key, $index, $column) {
                    return Yii::$app->controller->renderPartial('_expand-row-details');
                },
                'headerOptions' => ['class' => 'kartik-sheet-style'], 
                //'expandOneOnly' => true
            ],*/
            //'id',
            //'yil',
            'kontrol',
            'hedef_degeri',
           // 'olcum_sikligi',
            [
                'attribute'=>'olcum_sikligi',
                'format'=>'raw',                
                //'filter'=>array(1 =>"Yılda 1", 2 =>"6 Ayda bir", 3 =>"3 Ayda 1", 4 =>"Ayda 1"),
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
            //'planlanan_tarihi',
            //'olcum_sonucu',
            //'kontrol_kriteri',
            //'sorumlu',
            //'olusturma_tarihi',                    
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}{update}{delete}{kayitgir}' ,  
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
                    'kayitgir' => function ($url,$model) {
                        return  (   (bgys::olcmesorumlumu($model->id) or Yii::$app->user->can('bilgiislem_admin')) 
                            ? Html::button('<span class="glyphicon glyphicon-thumbs-up">', ['value' => Url::to(['kayitgir','id'=>$model->id]),'class' => 'modalButton5 btn btn-info' ,'title'=>"İşlem Gir"]) 
                            : ""               
                        
                        );
                         },
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
            'heading' => 'Risk Analizi', 
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
