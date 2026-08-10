<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EnvcihazturuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cihaz Türleri';
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
<div class="envcihazturu-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <h1>Cihaz Türü Ekleme Sayfası</h1>
    <p class="bgys-env-nav">
        <?= Html::button('Tür Ekle', ['value' => Url::to(['envcihazturu/create']),'class' => 'btn btn-danger modalButton2']) ?>
        
        <?= Html::button("Cihazlar",['class'=>'btn btn-success',
                        'onclick'=>"window.location.href = '" . \Yii::$app->urlManager->createUrl(['/envcihazliste/index']) . "';"
                    ])
        ?>
        <?= Html::button("Modeller",['class'=>'btn btn-secondary',
                        'onclick'=>"window.location.href = '" . \Yii::$app->urlManager->createUrl(['/envmodel/index']) . "';"
                    ])
        ?>
        <?= Html::button("Markalar",['class'=>'btn btn-warning',
                        'onclick'=>"window.location.href = '" . \Yii::$app->urlManager->createUrl(['/envmarka/index']) . "';"
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

Modal::begin([
    'id' => 'cihaz-turu-sil-modal',
    'header' => '<h4 class="modal-title"><span class="glyphicon glyphicon-warning-sign"></span> Silme Onayı</h4>',
    'footer' => Html::button('Vazgeç', ['class' => 'btn btn-default', 'data-dismiss' => 'modal']) . ' ' .
        Html::a('Evet, Sil', '#', [
            'id' => 'cihaz-turu-sil-onay',
            'class' => 'btn btn-danger',
            'data-method' => 'post',
            'data-pjax' => '0',
        ]),
]);
echo '<p><strong id="silinecek-cihaz-turu"></strong> cihaz türünü silmek istediğinizden emin misiniz?</p>';
echo '<p class="text-muted">Bu işlem geri alınamaz.</p>';
Modal::end();
?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn',
                'headerOptions' => ['style' => 'width:5%'],],

            [
                'attribute' => 'cihaz_turu',
                'headerOptions' => ['style' => 'width:45%'],
            ],
            [
                'attribute' => 'asset_type',
                'filter' => \app\models\Bgysvarlikenvanteri::assetTypeOptions(),
                'value' => function ($model) {
                    return \app\models\Bgysvarlikenvanteri::assetTypeOptions()[$model->asset_type] ?? 'Sınıflandırılmamış';
                },
                'headerOptions' => ['style' => 'width:25%'],
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
                        $kullanimSayisi = $model->getEnvCihazListes()->count();
                        if ($kullanimSayisi > 0) {
                            return Html::tag('span',
                                Html::button('<span class="glyphicon glyphicon-trash"></span>', [
                                    'class' => 'btn btn-default btn-xs disabled',
                                    'disabled' => true,
                                ]),
                                ['title' => 'Bu cihaz türü ' . $kullanimSayisi . ' cihazda kullanıldığı için silinemez.']
                            );
                        }

                        return  (  
                            Html::a('<span class="glyphicon glyphicon-trash"></span>', '#', [
                                'class' => 'btn btn-danger btn-xs cihaz-turu-sil',
                                'data-url' => Url::to(['delete', 'id'=>$model->id]),
                                'data-ad' => $model->cihaz_turu,
                                'title' => "Sil",
                            ])
                            );
                         },
                ]
            ],
        ],
    ]); ?>
</div>

<?php
$this->registerJs(<<<JS
$(document).off('click.cihazTuruSil', '.cihaz-turu-sil').on('click.cihazTuruSil', '.cihaz-turu-sil', function (event) {
    event.preventDefault();
    $('#silinecek-cihaz-turu').text($(this).data('ad'));
    $('#cihaz-turu-sil-onay').attr('href', $(this).data('url'));
    $('#cihaz-turu-sil-modal').modal('show');
});
JS
);
?>

<style>
#cihaz-turu-sil-modal .modal-header {
    background-color: #772043;
    color: #fff;
}
</style>
