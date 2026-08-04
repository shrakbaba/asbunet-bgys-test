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

$this->title = 'Bakım Yönetimi';
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

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::button('Bakım Kaydı Aç', ['value' => Url::to(['bgyscihazbakim/create']),'class' => 'btn btn-success bakim-modal','title'=>'Bakım Kaydı Aç']) ?>
        
    </p>
<?php
Modal::begin([
    'header'=>"<h3 id='bakim-modal-title'>Bakım Kaydı</h3>",
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
                'header'=>'İşlemler',
                'headerOptions' => ['style' => 'width:8%'],
                'template' => '{view} {update} {delete}' ,  
                'buttons' => [                                      
                    'view' => function ($url,$model) {
                        return  ( 
                            // Html::a('<span class="glyphicon glyphicon-eye-open">', ['view','id'=>$model->id], ['class' => 'btn btn-success','title'=>"İncele"] )

                            Html::button('<span class="glyphicon glyphicon-eye-open"></span>', ['value' => Url::to(['view','id'=>$model->id]),'class' => 'bakim-modal btn btn-success btn-xs' ,'title'=>"İncele"])                      
                            );
                         },
                    'update' => function ($url,$model) {
                        return  ( 
                            Html::button('<span class="glyphicon glyphicon-pencil"></span>', ['value' => Url::to(['update','id'=>$model->id]),'class' => 'bakim-modal btn btn-warning btn-xs' ,'title'=>"Güncelle"])                         
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
                                                        'confirm' => 'Bu bakım kaydını silmek istediğinizden emin misiniz?',
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
<?php
$this->registerJs(<<<JS
function bgysBakimModalHazirla() {
    $(document).off('click.bgysBakimModal', '.bakim-modal').on('click.bgysBakimModal', '.bakim-modal', function(e) {
        e.preventDefault();
        var button = $(this);
        $('#bakim-modal-title').text(button.attr('title') || 'Bakım Kaydı');
        $('#modalContent').html('<div class="text-center" style="padding:20px;">Yükleniyor...</div>');
        $('#modal').modal('show')
            .find('#modalContent')
            .load(button.attr('value'));
    });
}

bgysBakimModalHazirla();
$(document).on('pjax:success', function() {
    bgysBakimModalHazirla();
});

$(document).off('beforeSubmit.bgysBakimForm', '#modalContent form').on('beforeSubmit.bgysBakimForm', '#modalContent form', function(e) {
    e.preventDefault();
    var form = $(this);
    var formData = new FormData(form[0]);

    $.ajax({
        url: form.attr('action'),
        type: form.attr('method') || 'post',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (typeof response === 'string' && response.indexOf('window.location.reload') !== -1) {
                window.location.reload();
                return;
            }
            $('#modalContent').html(response);
        },
        error: function() {
            $('#modalContent').html('<div class="alert alert-danger">İşlem sırasında hata oluştu. Lütfen sayfayı yenileyip tekrar deneyiniz.</div>');
        }
    });

    return false;
});
JS
);
?>
