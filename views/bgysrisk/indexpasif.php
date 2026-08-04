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
        font-size: 13px;
    }
    .bgysrisk-index .risk-ust-buton.btn {
        min-width: 110px;
        padding: 6px 12px !important;
        line-height: 1.42857143 !important;
        text-align: center;
    }
</style>

<?php $this->registerJs("
window.bgysPasifRiskModalAc = function (buton) {
    var \$buton = $(buton);
    var url = \$buton.attr('value') || \$buton.data('url');
    var fID = \$buton.closest('tr').data('key');

    $.get(
        url || '" . Url::to(['view']) . "',
        url ? {} : { id: fID },
        function (data) {
            $('#modal').find('.modal-body').html(data);
            $('#modal').modal('show');
        }
    );

    return false;
};

$(document).off('click.bgysRiskCreate', '.modalButton2').on('click.bgysRiskCreate', '.modalButton2', function(e){
    e.preventDefault();
    $.get(
        '" . Url::to(['create']) . "',
        function (data) {
            $('#modal').find('.modal-body').html(data);
            $('#modal').modal('show');
        }
    );
});

$(document).off('click.bgysRiskView', '.modalButton4').on('click.bgysRiskView', '.modalButton4', function(e){
    e.preventDefault();
    bgysPasifRiskModalAc(this);
});

$(document).off('click.bgysRiskAktif', '.riskAktifButton').on('click.bgysRiskAktif', '.riskAktifButton', function(e){
    e.preventDefault();
    var url = $(this).data('url');
    window.bgysConfirmOptions = {
        title: '<span class=\"glyphicon glyphicon-warning-sign\"></span> Aktif Etme Onayı',
        description: 'Risk tekrar aktif riskler listesine taşınacaktır.',
        okText: 'Evet, Aktif Yap'
    };
    yii.confirm('Bu pasif riski tekrar aktif yapmak istediğinizden emin misiniz?', function(){
        var form = $('<form/>', {method: 'post', action: url});
        var csrfParam = yii.getCsrfParam();
        if (csrfParam) {
            form.append($('<input/>', {type: 'hidden', name: csrfParam, value: yii.getCsrfToken()}));
        }
        form.appendTo('body').submit();
    });
});
"); ?>

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
            'header' => '',
            'headerOptions' => ['class' => 'kartik-sheet-style']
        ],
        [
            'class' => 'kartik\grid\ExpandRowColumn',
            'value' => function ($model, $key, $index, $column) {
                return GridView::ROW_COLLAPSED;
            },
            'detail' => function ($model, $key, $index, $column) {
                $iliskiler = Bgysrisk::iliskiler();
                return Yii::$app->controller->renderPartial(
                    '_expand-row-details',
                    ['model' => $model, 'iliskiler' => $iliskiler]
                );
            },
            'headerOptions' => ['class' => 'kartik-sheet-style'], 
            'expandOneOnly' => true
        ],
        'id',                
        [
            'attribute'=>'varlik',
            'format'=>'raw',
            'value'=>function ($data) {
                return $data->varlik0->varlik_adi;
            }
        ], 
        'risk',
        [
            'attribute'=>'risk_sorumlusu',
            'format'=>'raw',
            'value'=>function ($model) {  
                return Yii::$app->params['giristipi']==1 
                    ? @Userbilgi::findOne(['kisi_id'=>$model->risk_sorumlusu])->ad.' '
                      .@Userbilgi::findOne(['kisi_id'=>$model->risk_sorumlusu])->soyad
                      .' / '.$model->riskSorumlusu->username 
                    : @$model->riskSorumlusu->ad." "
                      .@$model->riskSorumlusu->soyad." "
                      .@$model->riskSorumlusu->username; 
            },
        ], 
        [
            'attribute'=>'riskdegeri_onceki',
            'format'=>'raw',
            'value'=>function ($model) {  
                return $model->riskdegeri_onceki==null? "":$model->riskdegeri_onceki;
            },
        ], 
        [
            'attribute'=>'riskdegeri_sonraki',
            'format'=>'raw',
            'value'=>function ($model) {  
                return $model->riskdegeri_sonraki==null? "":$model->riskdegeri_sonraki;
            },
        ], 
        [
            'attribute'=>'ozetdurum',
            'format'=>'raw',
            'filter'=>[
                1 => "Risk Azalmış",
                2 => 'Risk Artmış',
                3 => 'Değişim Yok',
                4 => 'Risk Kabul'
            ],
            'value'=>function ($data) {
                if ($data->ozetdurum==2) {
                    return '<span class="glyphicon glyphicon-arrow-up" style="color:green"></span>';
                } elseif ($data->ozetdurum==1) {
                    return '<span class="glyphicon glyphicon-arrow-down" style="color:red"></span>';
                } elseif ($data->ozetdurum==4 || !empty($data->riskKabulleri)){
                    return '<span class="glyphicon glyphicon-ok" style="color:brown"></span> Risk Kabul';
                } else{
                    return '<span class="glyphicon glyphicon-resize-horizontal" style="color:blue"></span>';
                }
            }
        ], 
        [
            'attribute' => 'pasif_aciklama',
            'label'     => 'Pasife Alma Açıklaması',
            'format'    => 'ntext',
        ],

        [
            'attribute' => 'updated_at',
            'label'     => 'Güncellenme Tarihi',
            'format'    => ['datetime', 'php:d.m.Y H:i'],
        ],

        [
           'attribute' => 'updated_by',
            'label'     => 'Güncelleyen',
            'format'    => 'raw',
            'value'     => function ($model) {

                if ($model->updated_by == null) {
                    return '';
                }

                // LDAP ise
                if (Yii::$app->params['giristipi'] == 1) {
                    $user = $model->updatedByUser;
                    return $user ? $user->ad . ' ' . $user->soyad : '';
                }

        // Normal kullanıcı
        $user = $model->updatedByUser;
        return $user ? $user->ad . ' ' . $user->soyad . ' / ' . $user->username : '';

    },
        ],
        // 🔹 SADECE VIEW + AKTİF YAP
        [
            'class' => 'yii\grid\ActionColumn',
            'header'=>'İşlemler',
            'template' =>'{view} {aktif}',
            'headerOptions' => ['style' => 'width:10%'],
            'buttons' => [                                 
	                'view' => function ($url,$model) {
	                    return Html::button(
	                        '<span class="glyphicon glyphicon-eye-open"></span>',
	                        [
	                                'value' => Url::to(['view','id'=>$model->id]),
	                            'class' => 'modalButton4 btn btn-success btn-xs',
                                'type' => 'button',
	                            'title'=>"İncele",
                                'onclick' => 'return bgysPasifRiskModalAc(this);',
	                        ]
	                    );
	                },

                // ✅ AKTİF YAP BUTONU – BURASI buttons DİZİSİNDE
                'aktif' => function ($url, $model) {
                    return Html::button(
                        '<span class="glyphicon glyphicon-play"></span>',
                        [
                            'class' => 'riskAktifButton btn btn-primary btn-xs',
                            'type' => 'button',
                            'title' => 'Aktif Yap',
                            'data-url' => Url::to(['aktif', 'id' => $model->id]),
                        ]
                    );
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
            if ($model->riskdegeri_onceki >= 68 && $model->riskdegeri_onceki <= 100)  {
                  return ['class'=>'danger rowData'];
            } else if ($model->riskdegeri_onceki >= 35 && $model->riskdegeri_onceki <= 67)  {
                  return ['class'=>'warning rowData'];
            } else if ($model->riskdegeri_onceki <= 34)  {
                  return ['class'=>'success rowData'];
            }
        },
        'columns' => $gridColumns,
        'containerOptions' => ['style' => 'overflow: auto'],
        'headerRowOptions' => ['class' => 'kartik-sheet-style'],
        'filterRowOptions' => ['class' => 'kartik-sheet-style'],   
        'pjaxSettings'=>[
            'neverTimeout'=>true,
            'enablePushState' => false,
        ],
        'pjax' => true,
        'export' => [
            'label' => 'Dışa Aktar',
            'messages' => [
                'allowPopups' => 'İndirme işleminin düzgün çalışması için tarayıcıdaki açılır pencere engelleyicilerini kapatınız.',
                'confirmDownload' => 'Devam edilsin mi?',
                'downloadProgress' => 'Dosya oluşturuluyor. Lütfen bekleyiniz...',
                'downloadComplete' => 'İstek gönderildi. Dosyayı kaydettikten sonra bu pencereyi kapatabilirsiniz.',
            ],
            'header' => '<li role="presentation" class="dropdown-header">Sayfa Verisini Dışa Aktar</li>',
            'headerAll' => '<li role="presentation" class="dropdown-header">Tüm Veriyi Dışa Aktar</li>',
        ],
        'exportConfig' => [
            GridView::EXCEL => [
                'label' => 'Excel',
                'filename' => 'pasif-risk-analizi',
                'alertMsg' => 'EXCEL dosyası indirilmek üzere oluşturulacaktır.',
                'config' => [
                    'worksheet' => 'Pasif Risk Analizi',
                ],
            ],
            GridView::PDF => [
                'label' => 'PDF',
                'filename' => 'pasif-risk-analizi',
                'alertMsg' => 'PDF dosyası indirilmek üzere oluşturulacaktır.',
                'config' => [
                    'methods' => [
                        'SetHeader' => [[
                            'odd' => [
                                'L' => ['content' => 'BGYS Risk Analizi (PDF)', 'font-size' => 8, 'color' => '#333333'],
                                'C' => ['content' => 'Pasif Risk Analizi', 'font-size' => 16, 'color' => '#333333'],
                                'R' => ['content' => 'Oluşturulma Tarihi: ' . date('d.m.Y'), 'font-size' => 8, 'color' => '#333333'],
                            ],
                            'even' => [
                                'L' => ['content' => 'BGYS Risk Analizi (PDF)', 'font-size' => 8, 'color' => '#333333'],
                                'C' => ['content' => 'Pasif Risk Analizi', 'font-size' => 16, 'color' => '#333333'],
                                'R' => ['content' => 'Oluşturulma Tarihi: ' . date('d.m.Y'), 'font-size' => 8, 'color' => '#333333'],
                            ],
                        ]],
                    ],
                    'options' => [
                        'title' => 'Pasif Risk Analizi',
                        'subject' => 'Pasif Risk Analizi',
                    ],
                    'contentBefore' => '<h3>Pasif Risk Analizi</h3>',
                ],
            ],
        ],
        'bordered' => true,
        'striped' => true,
        'hover' => true,
        'panel' => [
            'heading' => 'Risk Analizi (Pasif Riskler)', 
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
	                    Html::a('Aktif Riskler', ['index'],['class' => 'btn btn-info risk-ust-buton']) .' ' .
		                    Html::button('Risk Ekle', ['value' => Url::to(['/bgysrisk/create']),'class' => 'btn btn-success modalButton2 risk-ust-buton']) .' ' .
		                    Html::a('Risk Kabuller', ['riskkabuller'], ['class' => 'btn btn-warning risk-ust-buton']),
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
