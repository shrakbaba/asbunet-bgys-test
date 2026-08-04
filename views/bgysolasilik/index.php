<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BgysolasilikSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Olasılık';
$this->params['breadcrumbs'][] = $this->title;
?>
<style type="text/css">
    td a span {
        color: #f9fafc !important;
    }
</style>
<div class="bgysolasilik-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

     <p>
        <?= Html::button('Olasılık Ekle', ['value' => Url::to(['bgysolasilik/create']),'class' => 'btn btn-success btn-lg modalButton2']) ?>
    </p>

<?php Pjax::begin(); ?>

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
            ['class' => 'yii\grid\SerialColumn',
                'headerOptions' => ['style' => 'width:5%'],],

       
            [
                'attribute' => 'deger',
                'headerOptions' => ['style' => 'width:30%'],
            ], 
            [
                'attribute' => 'basamak',
                'headerOptions' => ['style' => 'width:30%'],
            ], 

            [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'İşlemler',
                'headerOptions' => ['style' => 'width:8%'],
                'template' => '{view}{update}{delete}' ,  
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
                                                        'confirm' => 'Bu kaydı silmek istediğinizden emin misiniz?',
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
