<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use app\models\Userbilgi;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OlaykayitSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Olay Kayıt';
$this->params['breadcrumbs'][] = $this->title;
?>
<style type="text/css">
    .olaykayit-index .olay-ust-buton.btn {
        min-width: 0 !important;
        width: auto !important;
        padding: 6px 10px !important;
        font-size: 13px !important;
        line-height: 1.42857143 !important;
        text-align: center;
    }
    #modal .modal-dialog {
        margin-top: 22px;
    }
    #modal .modal-content {
        max-height: calc(100vh - 44px);
        overflow: hidden;
    }
    #modal .modal-body {
        max-height: calc(100vh - 145px);
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
    }
</style>

<?php
$this->registerJs(<<<JS
window.bgysOlayModalAc = function(button, event) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
        if (event.stopImmediatePropagation) {
            event.stopImmediatePropagation();
        }
    }

    var url = $(button).attr('value') || $(button).data('url');
    if (!url) {
        return false;
    }

    var title = $(button).data('modal-title') || 'Olay Kaydı';
    var modal = $('#modal');
    modal.find('.modal-header h2').text(title);
    modal.find('#modalContent').html('<div style="padding:20px">Yükleniyor...</div>');
    modal.find('.modal-body').scrollTop(0);
    modal.modal('show');
    $('body').addClass('modal-open');

    $.ajax({
        url: url,
        type: 'GET',
        cache: false
    }).done(function(data) {
        modal.find('#modalContent').html(data);
        modal.find('.modal-body').scrollTop(0);
        if ($.fn.select2 && $.fn.modal && $.fn.modal.Constructor) {
            $.fn.modal.Constructor.prototype.enforceFocus = function() {};
        }
    }).fail(function() {
        modal.find('#modalContent').html('<div class="alert alert-danger">Kayıt açılırken hata oluştu. Lütfen sayfayı yenileyip tekrar deneyiniz.</div>');
    });

    return false;
};

$(document).off('click.bgysOlayModal', '.olay-kayit-modal').on('click.bgysOlayModal', '.olay-kayit-modal', function(e) {
    return bgysOlayModalAc(this, e);
});
JS
);
?>

<div class="olaykayit-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php // Html::button('Olay Kaydı Aç', ['value' => Url::to(['/bgysolaykayit/create']),'class' => 'btn btn-success btn-lg','id'=>'modalButton']) ?>
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
                'attribute'=>'userid',
                'format'=>'raw',
                //'value'=>'user.username'." ".'user.ad',
                'value'=>function ($data)
                    {
                        //return @$data->user->username." / ".@$data->user->ad." ".@$data->user->soyad;
                        return Yii::$app->params['giristipi']==1 ? @Userbilgi::findOne(['kisi_id'=>$data->userid])->ad.' '.@Userbilgi::findOne(['kisi_id'=>$data->userid])->soyad.' / '.@$data->user->username : @$data->user->ad." ".@$data->user->soyad." ".@$data->user->username; 
                    }
            ],
            'konu',
            'mudahaleeden',
            [
                'attribute'=>'olaytarihi',
                'format' => ['date', 'php:d/m/Y'],
                'filter'=>false,
            ],

                ['class' => 'yii\grid\ActionColumn',
                    'header'=>'İşlemler',
                    'template' =>'{view} {update} {delete} {difac}',
                    'headerOptions' => ['style' => 'width:8%'],
                    'buttons' => [                                 
                    'view' => function ($url,$model) {
                        return  ( 
                            //Html::a('<span class="glyphicon glyphicon-eye-open">', ['view','id'=>$model->id], ['class' => 'btn btn-success','title'=>"İncele"] )

                            Html::button('<span class="glyphicon glyphicon-eye-open">', ['value' => Url::to(['view','id'=>$model->id]),'class' => 'olay-kayit-modal btn btn-success btn-xs' ,'title'=>"İncele",'data-modal-title'=>'Olay Kaydı','onclick' => 'return bgysOlayModalAc(this, event);'])                      
                            );
                         },
                    'update' => function ($url,$model) {
                        return  ( 
                            Html::button('<span class="glyphicon glyphicon-pencil">', ['value' => Url::to(['update','id'=>$model->id]),'class' => 'olay-kayit-modal btn btn-warning btn-xs' ,'title'=>"Güncelle",'data-modal-title'=>'Olay Kaydı Güncelle','onclick' => 'return bgysOlayModalAc(this, event);'])                         
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
                    ],

                ],
    ];

    Modal::begin([
        'header'=>"<h2>Olay Kaydı Aç</h2>",
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
    //'exportConfig' => [ GridView::EXCEL=>true, GridView::PDF=>true],
    'bordered' => true,
    'striped' => true,
    //'condensed' => $condensed,
    //'responsive' => $responsive,
    'hover' => true,
    //'showPageSummary' => true,
    'panel' => [
        'heading' => 'Olay Kaydı', 
        'type' => GridView::TYPE_PRIMARY, 
        
    ], 
    'persistResize' => false,
    'toggleDataOptions' => [
        'minCount' => 10,
        'confirmMsg' => $dataProvider->getTotalCount() . ' kayıt var. Tümünü göstermek istediğinizden emin misiniz?',
        'all' => [
            'label' => 'Tümünü Göster',
            'title' => 'Tüm kayıtları göster',
        ],
        'page' => [
            'label' => 'Sayfalı Göster',
            'title' => 'Sayfalı görünüme dön',
        ],
    ],
    'toolbar' =>  [
        [
            'content' =>
                Html::button('Olay Kayıt Ekle', ['value' => Url::to(['/bgysolaykayit/create']),'class' => 'btn btn-success olay-ust-buton olay-kayit-modal olay-kayit-create-btn','data-modal-title'=>'Olay Kaydı Ekle','onclick' => 'return bgysOlayModalAc(this, event);']),
                //Html::a('Risk Kabuller', ['riskkabuller'], ['class' => 'btn btn-warning'])   , 
            'options' => ['class' => 'btn-group']
        ],
        //'{export}',
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
