<?php

use yii\helpers\Html;
use yii\grid\GridView;

use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\helpers\imdat;
use app\models\Userbilgi;
use app\models\Bgysrisk;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BgysdiftalepSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Düzeltici Faaliyetler';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bgysdiftalep-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::button('Dif Ekle', ['value' => Url::to(['/bgysdiftalep/create']),'class' => 'btn btn-success','id'=>'modalButton']) ?>
        <?php // echo Html::a('Dif Ekle', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php
    Modal::begin([
        'header'=>"<h2 id=\"dif-modal-baslik\">Dif Ekle</h2>",
        'id'=>'modal',
        'size'=>'modal-lg',
    ]);

        echo "<div id='modalContent'></div>";
    Modal::end();

    Modal::begin([
        'id' => 'dif-talep-sil-modal',
        'header' => '<h4 class="modal-title"><span class="glyphicon glyphicon-warning-sign"></span> Silme Onayı</h4>',
        'footer' => Html::button('Vazgeç', ['class' => 'btn btn-default', 'data-dismiss' => 'modal']) . ' ' .
            Html::a('Evet, Sil', '#', [
                'id' => 'dif-talep-sil-onay',
                'class' => 'btn btn-danger',
                'data-method' => 'post',
                'data-pjax' => '0',
            ]),
    ]);
    echo '<p>Bu DİF talep kaydını silmek istediğinizden emin misiniz?</p>';
    echo '<p class="text-muted">Bu işlem geri alınamaz.</p>';
    Modal::end();
    ?>

    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'dif_no',
            [
                'attribute' => 'planlanan_tarih',
                'format' => ['date', 'php:d/m/Y']
            ], 
            'talep_eden',
            'dif_konusu',
            //'durum',
            [
                'attribute'=>'durum',
                'format'=>'raw',                
                'filter'=>array(0=>'Devam Ediyor',1=>'Kapatıldı'),
                'value'=>function ($data)
                    {
                        return $data->durum==1 ? "Kapatıldı":"Devam Ediyor"; 
                    }
            ],
            //'planlanan_tarih',
            [
                'attribute'=>'sorumlu',
                'format'=>'raw',
                'value'=>function ($model) {  
                           //return @$model->zimmet0->ad." ".@$model->zimmet0->soyad;
                           return Yii::$app->params['giristipi']==1 ? @Userbilgi::findOne(['kisi_id'=>$model->sorumlu])->ad.' '.@Userbilgi::findOne(['kisi_id'=>$model->sorumlu])->soyad.' / '.@$model->sorumlu0->username : @$model->sorumlu0->ad." ".@$model->sorumlu0->soyad." ".@$model->sorumlu0->username; 
                    },
            ],
            [
                'attribute'=>'risk_iliskisi',
                'format'=>'raw',                
                'value'=>function ($data)
                {   $a=null;
                    if (json_decode($data->risk_iliskisi)) {
                        foreach (json_decode($data->risk_iliskisi) as $key => $value) {
                            $a=$a." <b>Risk ".$value."</b> ".@Bgysrisk::find()->where(['id'=>$value])->one()->risk."<br>";                            
                        } 
                    }
                    return $a; 
                }
            ],

            ['class' => 'yii\grid\ActionColumn',
                'header'=>'İşlemler',
                'contentOptions' => ['class' => 'bgys-grid-actions'],

                'template' =>'{view} {update} {delete} {difformac} {onaylidifform} {difform} ',

                'buttons' => [                    
                    'view' => function($url, $model) {   //hertürlü
                        //echo "<pre>";var_dump(Basvurukesinkayit::find()->where(['kurskayitid' => $model->id])->one());echo "</br>";
                        return Html::button('<span class="glyphicon glyphicon-eye-open"></span>', [
                            'value' => Url::to(['view', 'id' => $model->id]),
                            'class' => 'btn btn-success btn-xs dif-modal-button',
                            'title' => 'İncele',
                            'data-title' => 'DİF Detayı',
                        ]);

                    },
                    'update' => function($url, $model) {   //hertürlü
                        return ( !imdat::difformonaydurumu($model->id) ) 
                            ? 
                            Html::button('<span class="glyphicon glyphicon-pencil"></span>', [
                                'value' => Url::to(['update', 'id' => $model->id]),
                                'class' => 'btn btn-warning btn-xs dif-modal-button',
                                'title' => 'Güncelle',
                                'data-title' => 'DİF Güncelle',
                            ])
                            :
                            null;
                    },
                    'delete' => function($url, $model) {   //onaylanmamışsa ve kesin başvuru yapmamışsa
                        return ( !imdat::difformonaydurumu($model->id)) 
                            ? 
                            Html::a('<span class="glyphicon glyphicon-trash"></span>', '#', [
                                'class' => 'btn btn-danger btn-xs dif-talep-sil',
                                'title' => 'Sil',
                                'data-url' => Url::to(['delete', 'id'=>$model->id]),
                                'data-ad' => 'DİF No: ' . $model->dif_no,
                                'data-pjax' => '0',
                            ])
                            :
                            null;
                    }, 
                   'difformac' => function ($url, $model, $key) {  //kurs başvuru onaylanmış ve kesin kayıt yapılmamışsa
                        return  
                            ((!imdat::difform($model->id)) and !imdat::difformonaydurumu($model->id)  )
                            ?                                 
                                Html::button('Aksiyon Tanımla', [
                                    'value' => Url::to(['difformuac', 'i'=>$model->id]),
                                    'class'=>'btn btn-info btn-xs dif-modal-button',
                                    'title' => 'Aksiyon Tanımla',
                                    'data-title' => 'Aksiyon Tanımla',
                                ])
                            : 
                                null;
                    },   
                   'difform' => function ($url, $model, $key) {  //kurs başvuru onaylanmış ve kesin kayıt yapılmamışsa
                        return  
                            ((imdat::difform($model->id)) and !imdat::difformonaydurumu($model->id) )
                            ?                                 
                                Html::button('Tanımlanan Aksiyon', [
                                    'value' => Url::to(['difform', 'i'=>$model->id, 'a'=>0]),
                                    'class'=>'btn btn-warning btn-xs dif-modal-button',
                                    'title' => 'Tanımlanan Aksiyon',
                                    'data-title' => 'Tanımlanan Aksiyon',
                                ])
                            : 
                                null;
                    },
                    'onaylidifform' => function ($url, $model, $key) {  //kurs başvuru onaylanmış ve kesin kayıt yapılmamışsa
                        return  
                            (imdat::difform($model->id) and imdat::difformonaydurumu($model->id) )
                            ?                                 
                                Html::button('Onaylı Dif Formu', [
                                    'value' => Url::to(['difform', 'i'=>$model->id, 'a'=>1]),
                                    'class'=>'btn btn-success btn-xs dif-modal-button',
                                    'title' => 'Onaylı Dif Formu',
                                    'data-title' => 'Onaylı DİF Formu',
                                ])
                            : 
                                null;
                    }, 
                ],

            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>

<?php
$this->registerJs(<<<JS
$(document).off('click.difTalepSil', '.dif-talep-sil').on('click.difTalepSil', '.dif-talep-sil', function (event) {
    event.preventDefault();

    var modal = $('#dif-talep-sil-modal');
    var icerik = $('<div>');
    icerik.append($('<p>').append($('<strong>').text($(this).data('ad'))).append(' kaydını silmek istediğinizden emin misiniz?'));
    icerik.append($('<p>', {
        'class': 'text-muted',
        text: 'Bu işlem geri alınamaz.'
    }));

    modal.find('.modal-body').empty().append(icerik);
    $('#dif-talep-sil-onay').attr('href', $(this).data('url'));
    modal.modal('show');
});

$(document).off('pjax:start.difAlert').on('pjax:start.difAlert', function () {
    $('.alert').alert('close').remove();
});

$(document).off('click.difModal', '.dif-modal-button').on('click.difModal', '.dif-modal-button', function (event) {
    event.preventDefault();
    $('#dif-modal-baslik').text($(this).data('title') || $(this).attr('title') || 'DİF');
    $('#modal').modal('show')
        .find('#modalContent')
        .load($(this).attr('value'));
});

$(document).off('click.difCreateTitle', '#modalButton').on('click.difCreateTitle', '#modalButton', function () {
    $('#dif-modal-baslik').text('DİF Ekle');
});
JS
);
?>

<style type="text/css">
    #dif-talep-sil-modal .modal-header {
        background-color: #772043;
        color: #fff;
    }
    .bgysdiftalep-index .bgys-grid-actions {
        white-space: nowrap;
        min-width: 150px;
    }
    .bgysdiftalep-index .bgys-grid-actions .btn {
        width: auto;
        min-width: 22px;
    }
    .modal .bgysdiftakip-create {
        overflow: hidden;
        padding-bottom: 10px;
    }
    .modal .bgysdiftakip-create:after {
        content: "";
        display: table;
        clear: both;
    }
    .modal .bgysdiftakip-create .col-lg-12,
    .modal .bgysdiftakip-create .col-lg-8,
    .modal .bgysdiftakip-create .col-lg-6,
    .modal .bgysdiftakip-create .col-lg-4 {
        float: none;
        width: 100%;
        padding-left: 0;
        padding-right: 0;
    }
    .modal .bgysdiftakip-create .btn-lg {
        font-size: 14px;
        padding: 8px 14px;
    }
</style>
