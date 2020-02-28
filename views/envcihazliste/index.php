<?php

use yii\helpers\Html;
use yii\grid\GridView;
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
<div class="envcihazliste-index">
<?= "</br>"; ?>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <p>
        <?= Html::a('Özet', ['dashboard'], ['class' => 'btn btn-info btn-lg',"style"=>"float:right;" ]) ?>
        
        <?= Html::button('Cihaz Ekle', ['value' => Url::to(['envcihazliste/create']),'class' => 'btn btn-success btn-lg modalButton2']) ?>

        <?= Html::button("Modeller",['class'=>'btn btn-secondary',
                        'onclick'=>"window.location.href = '" . \Yii::$app->urlManager->createUrl(['/envmodel/index']) . "';"
                    ])
        ?>
        <?= Html::button("Markalar",['class'=>'btn btn-warning',
                        'onclick'=>"window.location.href = '" . \Yii::$app->urlManager->createUrl(['/envmarka/index']) . "';"
                    ])
        ?>
        <?= Html::button("Cihaz Türleri",['class'=>'btn btn-danger',
                        'onclick'=>"window.location.href = '" . \Yii::$app->urlManager->createUrl(['/envcihazturu/index']) . "';"
                    ])
        ?>
    </p>
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
           if ($model->garanti_bitis < date('Y-m-d',strtotime("+1 months")))  {
              return ['class'=>'danger'];
          }else if ($model->garanti_bitis < date('Y-m-d',strtotime("+3 months")))  {
              return ['class'=>'info'];
          }else if ($model->garanti_bitis < date('Y-m-d',strtotime("+6 months")))  {
              return ['class'=>'success'];
          }
      },
      'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

            //'id',
        [
            'attribute'=>'cihaz_turu_id',
            'format'=>'raw',
            'value'=>'cihazTuru.cihaz_turu',
        ],
            //'cihaz_turu_id',
        [
            'attribute'=>'marka_id',
            'format'=>'raw',
            'value'=>'marka.marka',
        ],
            //'marka_id',
        [
            'attribute'=>'model_id',
            'format'=>'raw',
            'value'=>'model.model',
        ],

            //'model_id',
            //'adet',
            //'alim_tarihi',
            //'garanti_bitis',
       /* [
            'attribute' => 'alim_tarihi',
            'format' => ['date', 'php:d/m/Y'],
            'filter'=>false,
        ], */
        'konum',
        'adet',
        [
            'attribute' => 'garanti_bitis',
            'format' => ['date', 'php:d/m/Y'],
            'filter'=>false,
        ], 
        [
                'attribute' => 'link',                
                'format'=>'raw',
                'value' => function ($model) {   
                    if ($model->link!='')
                       return Html::a('Link', $model->link, ['target'=>'_blank']); else return 'no link';
                },
        ], 
        [
            'attribute'=>'zimmet',
            'format'=>'raw',
            'value'=>function ($model) {  
                       //return @$model->zimmet0->ad." ".@$model->zimmet0->soyad;
                       return Yii::$app->params['giristipi']==1 ? @Userbilgi::findOne(['kisi_id'=>$model->zimmet])->ad.' '.@Userbilgi::findOne(['kisi_id'=>$model->zimmet])->soyad.' / '.@$model->zimmet0->username : @$model->zimmet0->ad." ".@$model->zimmet0->soyad." ".@$model->zimmet0->username; 
                },
        ],
        /*[
           'attribute' => 'file',
           'format' => 'raw',
           'value' => function ($model) {   
            if ($model->dosya!='')
              return '<img src="'.Yii::getAlias('@env_dosya_goster')."/".$model->dosya.'" width="50px" height="auto">'; else return 'no image';
            },
        ],*/

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
