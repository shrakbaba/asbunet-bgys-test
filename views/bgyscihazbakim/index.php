<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\Envcihazliste;
use app\models\Userdb;
use app\models\Userbilgi;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BgyscihazbakimSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Bakım Takipleri';
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
<div class="bgyscihazbakim-index">

    
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::button('Bakım Kaydı Aç', ['value' => Url::to(['bgyscihazbakim/create']),'class' => 'btn btn-success btn-lg','id'=>'modalButton']) ?>
        
    </p>
<?php
Modal::begin([
    'header'=>"<h2>Bakım Kaydı Aç</h2>",
    'id'=>'modal',
    'size'=>'modal-lg',
]);

    echo "<div id='modalContent'></div>";
Modal::end();
?><?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'cihazid',
            //'sorumlu',
            [
                'attribute' => 'cihazid',  
                'filter'=>ArrayHelper::map(Envcihazliste::find()->all(),'id',function($model) {
                            return $model->cihazTuru->cihaz_turu."/".$model->marka->marka."/".$model->model->model;
                            }),              
                'format'=>'raw',
            'value'=>function ($data){
             return Html::a(($data->cihaz->cihazTuru->cihaz_turu)."/".($data->cihaz->marka->marka)."/".$data->cihaz->model->model , '/envcihazliste/view?id='.$data->cihazid ,['target'=>'_blank']); 
            } 
            ], 
               // 'sorumlu',             
            [
            'attribute'=>'sorumlu',
            'filter'=>//ArrayHelper::map(Userdb::find()->all(),'id',function($model) {
                      //      return $model['ad'].' '.$model['soyad'].' / '.$model['username'];
                      //      }),
                    Yii::$app->params['giristipi']==1 ? 
                ArrayHelper::map(\Edvlerblog\Adldap2\model\UserDbLdap::find()->all(),'id',function($model) {
                    //return $model['ad'].' '.$model['soyad'].' / '.$model['username'];
                    return @Userbilgi::findOne(['kisi_id'=>$model['id']])->ad.' '.@Userbilgi::findOne(['kisi_id'=>$model['id']])->soyad.' / '.$model['username'];
                    }) 
                :ArrayHelper::map(Userdb::find()->all(),'id',function($model) {                            
                    return $model['ad'].' '.$model['soyad'].' / '.$model['username'];
                    }) ,
            'format'=>'raw',
            'value'=>function ($model) {  
                       //return @$model->zimmet0->ad." ".@$model->zimmet0->soyad;
                       return Yii::$app->params['giristipi']==1 ? @Userbilgi::findOne(['kisi_id'=>$model->sorumlu])->ad.' '.@Userbilgi::findOne(['kisi_id'=>$model->sorumlu])->soyad.' / '.$model->sorumlu0->username : @$model->sorumlu0->ad." ".@$model->sorumlu0->soyad." ".@$model->sorumlu0->username; 
                },
            ], 

            'periyod',
            //'bakimformlari',
            //'sozlesme',
            [
                'attribute' => 'bakimtarihi',
                'format' => ['date', 'php:d/m/Y']
            ], 
            //'guncellemetarihi',

            
            
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
