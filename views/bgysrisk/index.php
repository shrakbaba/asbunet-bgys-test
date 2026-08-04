<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use app\models\Userbilgi;
use kartik\grid\GridView;
use app\models\Bgysrisk;
use app\models\Bgysdiftalep;
use kartik\date\DatePicker;
use yii\helpers\imdat;

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
    .risk-renk-aciklama {
        margin-bottom: 10px;
    }
    .risk-renk-aciklama span {
        display: inline-block;
        margin-right: 12px;
        padding: 6px 10px;
        border-radius: 4px;
        border: 1px solid #ddd;
    }
    .risk-renk-aciklama a,
    .risk-renk-aciklama a:hover,
    .risk-renk-aciklama a:focus {
        color: #333;
        text-decoration: none;
    }
    .risk-renk-dusuk {
        background-color: #dff0d8;
    }
    .risk-renk-orta {
        background-color: #fcf8e3;
    }
    .risk-renk-yuksek {
        background-color: #f2dede;
    }
    .bgysrisk-index .grid-view th,
    .bgysrisk-index .grid-view th a {
        color: #dd4b39 !important;
    }
</style>

<div class="bgysrisk-index">
    <?php 
    Pjax::begin(['id' => 'some-id', 'timeout' => false]); 

    // Modal: create / update / view / pasif hepsi aynı modal içinde açılacak
    Modal::begin([
        'id'   => 'modal',
        'size' => 'modal-lg',
    ]);

    echo "<div id='modalContent'></div>";
    Modal::end();
    ?>

    <?php 
    $gridColumns = [
        [
            'class' => 'kartik\grid\SerialColumn',
            'contentOptions' => ['class' => 'kartik-sheet-style'],
            'header' => '',
            'headerOptions' => ['class' => 'kartik-sheet-style'],
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
            'expandOneOnly' => true,
        ],

        'id',

        [
            'attribute' => 'varlik',
            'format'    => 'raw',
            'value'     => function ($data) {
                return $data->varlik0->varlik_adi;
            },
        ],

        //'departman',
        'risk',

        [
            'attribute' => 'risk_sorumlusu',
            'format'    => 'raw',
            'value'     => function ($model) {
                return Yii::$app->params['giristipi'] == 1
                    ? @Userbilgi::findOne(['kisi_id'=>$model->risk_sorumlusu])->ad . ' '
                      . @Userbilgi::findOne(['kisi_id'=>$model->risk_sorumlusu])->soyad
                      . ' / ' . $model->riskSorumlusu->username
                    : @$model->riskSorumlusu->ad . ' '
                      . @$model->riskSorumlusu->soyad . ' '
                      . @$model->riskSorumlusu->username;
            },
        ],

        [
            'attribute' => 'riskdegeri_onceki',
            'format'    => 'raw',
            'value'     => function ($model) {
                return $model->riskdegeri_onceki === null ? '' : $model->riskdegeri_onceki;
            },
        ],

        [
            'attribute' => 'riskdegeri_sonraki',
            'format'    => 'raw',
            'value'     => function ($model) {
                return $model->riskdegeri_sonraki === null ? '' : $model->riskdegeri_sonraki;
            },
        ],

        [
            'attribute' => 'ozetdurum',
            'format'    => 'raw',
            'filter'    => [
                1 => 'Risk Azalmış',
                2 => 'Risk Artmış',
                3 => 'Değişim Yok',
                4 => 'Risk Kabul',
            ],
            'value'     => function ($data) {
                if ($data->ozetdurum == 2) {
                    return '<span class="glyphicon glyphicon-arrow-up" style="color:green"></span>';
                } elseif ($data->ozetdurum == 1) {
                    return '<span class="glyphicon glyphicon-arrow-down" style="color:red"></span>';
                } elseif ($data->ozetdurum == 4 || !empty($data->riskKabulleri)) {
                    return '<span class="glyphicon glyphicon-ok" style="color:brown"></span> Risk Kabul';
                }
                return '<span class="glyphicon glyphicon-resize-horizontal" style="color:blue"></span>';
            },
        ],

        // 🔹 Güncellenme Tarihi (updated_at)
        [
            'attribute' => 'updated_at',
            'label'     => 'Güncellenme Tarihi',
            'format'    => ['datetime', 'php:d.m.Y H:i'],
            'value'     => function ($model) {
                return $model->updated_at ?: null;
            },
            'filterType' => GridView::FILTER_DATE,
            'filterWidgetOptions' => [
                'type' => DatePicker::TYPE_INPUT,
                'pluginOptions' => [
                    'autoclose'       => true,
                    'format'          => 'yyyy-mm-dd',
                    'todayHighlight'  => true,
                ],
                'options' => [
                    'placeholder' => 'Tarih seçin...',
                ],
            ],
        ],

        // 🔹 Güncelleyen Kullanıcı (updated_by)
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

        [
            'class' => 'yii\grid\ActionColumn',
            'header'=>'İşlemler',
            // delete kaldırıldı, pasif eklendi
            'template' => '{view} {update} {pasif} {difac}',
	            'headerOptions' => ['style' => 'width:8%'],
	            'buttons' => [
	                'view' => function ($url, $model) {
		                    return Html::button(
		                        '<span class="glyphicon glyphicon-eye-open"></span>',
		                        [
                                    'value' => Url::to(['view','id'=>$model->id]),
		                            'class' => 'modalButton4 btn btn-success btn-xs',
                                    'type' => 'button',
		                            'title' => 'İncele',
		                        ]
		                    );
		                },
                'update' => function ($url, $model) {
                    return Html::button(
                        '<span class="glyphicon glyphicon-pencil"></span>',
                        [
                            'value' => Url::to(['update','id'=>$model->id]),
                            'class' => 'modalButton3 btn btn-warning btn-xs',
                            'title' => 'Güncelle',
                        ]
                    );
                },
                'pasif' => function ($url, $model) {
                    return Html::button(
                        '<span class="glyphicon glyphicon-pause"></span>',
                        [
                            'class'   => 'modalButtonPasif btn btn-danger btn-xs',
                            'data-id' => $model->id,
                            'title'   => 'Pasif Yap',
                        ]
                    );
                },
		                'difac' => function($url, $model) {
		                    $mevcutDif = Bgysdiftalep::find()
		                        ->where(['regexp', 'risk_iliskisi', '(^|[^0-9])' . intval($model->id) . '([^0-9]|$)'])
		                        ->orWhere(['dif_konusu' => $model->risk . " adlı risk için açılan DİF kaydı."])
                                ->orderBy(['id' => SORT_DESC])
		                        ->one();

                            if ($mevcutDif === null) {
                                return Html::button(
                                    'DİF Aç',
                                    [
                                        'value' => Url::to(['/bgysdiftalep/difac', 'id'=>$model->id]),
                                        'class'=>'btn btn-info btn-xs bgys-risk-dif-modal',
                                        'title' => 'DİF Aç',
                                        'data-title' => 'DİF Aç',
                                    ]
                                );
                            }

                            if (imdat::difformonaydurumu($mevcutDif->id)) {
                                return Html::button(
                                    'Onaylı DİF Formu',
                                    [
                                        'value' => Url::to(['/bgysdiftalep/difform', 'i'=>$mevcutDif->id, 'a'=>1]),
                                        'class'=>'btn btn-success btn-xs bgys-risk-dif-modal',
                                        'title' => 'Onaylı DİF Formu',
                                        'data-title' => 'Onaylı DİF Formu',
                                    ]
                                );
                            }

                            if (imdat::difform($mevcutDif->id)) {
                                return Html::button(
                                    'Tanımlanan Aksiyon',
                                    [
                                        'value' => Url::to(['/bgysdiftalep/difform', 'i'=>$mevcutDif->id, 'a'=>0]),
                                        'class'=>'btn btn-warning btn-xs bgys-risk-dif-modal',
                                        'title' => 'Tanımlanan Aksiyon',
                                        'data-title' => 'Tanımlanan Aksiyon',
                                    ]
                                );
                            }

		                    return Html::button(
		                        'Aksiyon Tanımla',
		                        [
                                    'value' => Url::to(['/bgysdiftalep/difformuac', 'i'=>$mevcutDif->id]),
		                            'class'=>'btn btn-info btn-xs bgys-risk-dif-modal',
		                            'title' => 'Aksiyon Tanımla',
		                            'data-title' => 'Aksiyon Tanımla',
		                        ]
		                    );
	                },
            ],
        ],
    ];

    $butonlar = [
        [
            'content' =>
	                Html::a('Pasif Riskler', ['indexpasif'], ['class' => 'btn btn-info risk-ust-buton']) . ' ' .
	                Html::button('Risk Ekle', [
	                    'value' => Url::to(['create']),
	                    'class' => 'btn btn-success modalButton2 risk-ust-buton',
		                ]) . ' ' .
		                Html::a('Risk Kabuller', ['riskkabuller'], ['class' => 'btn btn-warning risk-ust-buton']),
	            'options' => ['class' => 'btn-group'],
	        ],
        '{export}',
        '{toggleData}',
    ];
    ?>

    <?php 
    $riskRenkAciklamasi = '<div class="risk-renk-aciklama">'
        . Html::a('<span class="risk-renk-dusuk">Düşük Risk: 1-34</span>', ['index', 'BgysriskSearch' => ['risk_seviyesi' => 'dusuk']], ['data-pjax' => '0'])
        . Html::a('<span class="risk-renk-orta">Orta Risk: 35-67</span>', ['index', 'BgysriskSearch' => ['risk_seviyesi' => 'orta']], ['data-pjax' => '0'])
        . Html::a('<span class="risk-renk-yuksek">Yüksek Risk: 68-100</span>', ['index', 'BgysriskSearch' => ['risk_seviyesi' => 'yuksek']], ['data-pjax' => '0'])
        . Html::a('<span>Tüm Riskler</span>', ['index'], ['data-pjax' => '0'])
        . '</div>';

    echo GridView::widget([
        'id' => 'kv-grid-demo',
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'resizableColumns' => true,
        'rowOptions' => function($model){
            if ($model->riskdegeri_onceki >= 68 && $model->riskdegeri_onceki <= 100)  {
                return ['class'=>'danger rowData'];
            } elseif ($model->riskdegeri_onceki >= 35 && $model->riskdegeri_onceki <= 67)  {
                return ['class'=>'warning rowData'];
            } elseif ($model->riskdegeri_onceki <= 34)  {
                return ['class'=>'success rowData'];
            }
        },
        'columns' => $gridColumns,
        'containerOptions' => ['style' => 'overflow: auto'],
        'headerRowOptions'  => ['class' => 'kartik-sheet-style'],
        'filterRowOptions'  => ['class' => 'kartik-sheet-style'],
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
        'exportConfig'      => [
            GridView::EXCEL => [
                'label' => 'Excel',
                'filename' => 'risk-analizi',
                'alertMsg' => 'EXCEL dosyası indirilmek üzere oluşturulacaktır.',
                'config' => [
                    'worksheet' => 'Risk Analizi',
                ],
            ],
            GridView::PDF => [
                'label' => 'PDF',
                'filename' => 'risk-analizi',
                'alertMsg' => 'PDF dosyası indirilmek üzere oluşturulacaktır.',
                'config' => [
                    'methods' => [
                        'SetHeader' => [[
                            'odd' => [
                                'L' => ['content' => 'BGYS Risk Analizi (PDF)', 'font-size' => 8, 'color' => '#333333'],
                                'C' => ['content' => 'Risk Analizi', 'font-size' => 16, 'color' => '#333333'],
                                'R' => ['content' => 'Oluşturulma Tarihi: ' . date('d.m.Y'), 'font-size' => 8, 'color' => '#333333'],
                            ],
                            'even' => [
                                'L' => ['content' => 'BGYS Risk Analizi (PDF)', 'font-size' => 8, 'color' => '#333333'],
                                'C' => ['content' => 'Risk Analizi', 'font-size' => 16, 'color' => '#333333'],
                                'R' => ['content' => 'Oluşturulma Tarihi: ' . date('d.m.Y'), 'font-size' => 8, 'color' => '#333333'],
                            ],
                        ]],
                    ],
                    'options' => [
                        'title' => 'Risk Analizi',
                        'subject' => 'Risk Analizi',
                    ],
                    'contentBefore' => '<h3>Risk Analizi</h3>',
                ],
            ],
        ],
        'bordered' => true,
        'striped'  => true,
        'hover'    => true,
        'panel' => [
            'heading' => 'Risk Analizi',
            'type'    => GridView::TYPE_PRIMARY,
            'before'  => $riskRenkAciklamasi,
        ],
        'persistResize'      => false,
        'toggleDataOptions'  => [
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
        'toolbar'            => $butonlar,
    ]);
    ?>

    <?php
    // ==== JS: event delegation ile, PJAX sonrasında da çalışır ====
    $this->registerJs("
    // Risk Ekle
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

    // Güncelle
    $(document).off('click.bgysRiskUpdate', '.modalButton3').on('click.bgysRiskUpdate', '.modalButton3', function(e){
        e.preventDefault();
        var url = $(this).attr('value');
        var fID = $(this).closest('tr').data('key');
        $.get(
            url || '" . Url::to(['update']) . "',
            url ? {} : { id: fID },
            function (data) {
                $('#modal').find('.modal-body').html(data);
                $('#modal').modal('show');
            }
        );
    });

    // İncele
    $(document).off('click.bgysRiskView', '.modalButton4').on('click.bgysRiskView', '.modalButton4', function(e){
        e.preventDefault();
        var url = $(this).attr('value');
        var fID = $(this).closest('tr').data('key');
        $.get(
            url || '" . Url::to(['view']) . "',
            url ? {} : { id: fID },
            function (data) {
                $('#modal').find('.modal-body').html(data);
                $('#modal').modal('show');
            }
        );
    });

    // PASİF YAP
    $(document).off('click.bgysRiskPasif', '.modalButtonPasif').on('click.bgysRiskPasif', '.modalButtonPasif', function(e){
        e.preventDefault();
        var fID = $(this).data('id') || $(this).closest('tr').data('key');
        console.log('Pasif butonu tıklandı, id=', fID);
        $.get(
            '" . Url::to(['pasif']) . "',
            { id: fID },
            function (data) {
                $('#modal').find('.modal-body').html(data);
                $('#modal').modal('show');
            }
        );
    });

    $(document).off('click.bgysRiskDifModal', '.bgys-risk-dif-modal').on('click.bgysRiskDifModal', '.bgys-risk-dif-modal', function(e){
        e.preventDefault();
        $('#modal').find('.modal-body').html('<div class=\"text-center\" style=\"padding:20px;\">Yükleniyor...</div>');
        $('#modal').modal('show');
        $.get($(this).attr('value'), function(data) {
            $('#modal').find('.modal-body').html(data);
        });
    });
    ");
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
a{
    color: #8a3939;
}
a:-webkit-any-link {
    text-decoration: none;
    color: #8a3939;
}
</style>
