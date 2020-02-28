<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\EnvmarkaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Markalar';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
#statusMsg {
height:30px;
width:550px;
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
<div class="envmarka-index">

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        
        <?= Html::button('Marka Ekle', ['value' => Url::to(['envmarka/create']),'class' => 'btn btn-warning btn-lg modalButton2']) ?>

        <?= Html::button("Cihazlar",['class'=>'btn btn-success',
                        'onclick'=>"window.location.href = '" . \Yii::$app->urlManager->createUrl(['/envcihazliste/index']) . "';"
                    ])
        ?>
        <?= Html::button("Modeller",['class'=>'btn btn-secondary',
                        'onclick'=>"window.location.href = '" . \Yii::$app->urlManager->createUrl(['/envmodel/index']) . "';"
                    ])
        ?>
        <?= Html::button("Cihaz Türleri",['class'=>'btn btn-danger',
                        'onclick'=>"window.location.href = '" . \Yii::$app->urlManager->createUrl(['/envcihazturu/index']) . "';"
                    ])
        ?>
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
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'marka',

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
