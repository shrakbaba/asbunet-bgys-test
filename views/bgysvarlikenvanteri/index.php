<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\helpers\imdat;
use yii\helpers\bgys;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\export\ExportMenu;
use app\models\Bgyskategori;
use app\models\Bgysbilgisinifi;
use app\models\Bgysvarlikenvanteri;
/* @var $this yii\web\View */
/* @var $searchModel app\models\BgysvarlikenvanteriSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<style type="text/css">
    td a span {
        color: #f9fafc !important;
    }
    .panel-primary > .panel-heading {
        background-color: #772043 !important;
        border-color: #772043 !important;
    }
    .panel-primary {
        border-color: #772043 !important;
        margin-top: 10px;
    }
    .bgysvarlikenvanteri-index .kv-panel-before,
    .bgysvarlikenvanteri-index .panel-heading {
        display: none;
    }
    .bgysvarlikenvanteri-index .panel,
    .bgysvarlikenvanteri-index .panel-primary {
        border: 0 !important;
        box-shadow: none;
    }
    tr th a{
        color: #3c8dbc !important;
    }
   
</style>
<?php
$this->title = 'Varlık Envanteri';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bgysvarlikenvanteri-index">
<h1><?= Html::encode($this->title) ?></h1>

<p>
    <?= Html::button('Ekle', ['value' => Url::to(['create']),'class' => 'btn btn-lg btn-success modalButton2' ,'style'=>"margin-bottom:5px;"]) ?>
    <?php $incompleteGeneratedAssets = Bgysvarlikenvanteri::find()->where(['not', ['source_device_id' => null]])->andWhere(['or',
        ['departman' => null], ['bilgi_sinifi' => null], ['lokasyon' => null],
        ['gizlilik' => null], ['butunluk' => null], ['erisilebilirlik' => null], ['varlik_degeri' => null],
    ])->count(); ?>
    <?= Html::a(
        'Sınıflandırması Eksik (' . (int)$incompleteGeneratedAssets . ')',
        ['index', 'BgysvarlikenvanteriSearch' => ['needs_completion' => 1]],
        ['class' => 'btn btn-lg btn-warning', 'style' => 'margin-bottom:5px;']
    ) ?>
</p>

<?php

$gridColumns = [
    [
        'class' => 'kartik\grid\SerialColumn',
        'contentOptions' => ['class' => 'kartik-sheet-style'],  
        'width' => '2%',
        'header' => '',
        'headerOptions' => ['class' => 'kartik-sheet-style']
    ],
    [
        'attribute' => 'varlik_adi', 
        'vAlign' => 'middle',
        'width' => '10%',
    ],                        
    [
        'attribute' => 'asset_type',
        'filter' => Bgysvarlikenvanteri::assetTypeOptions(),
        'value' => function ($data) {
            return Bgysvarlikenvanteri::assetTypeOptions()[$data->asset_type] ?? $data->asset_type;
        },
        'vAlign' => 'middle',
        'width' => '10%',
    ],
    [
        'attribute'=>'bilgi_sinifi',
        'format'=>'raw',
        'filter'=>ArrayHelper::map(Bgysbilgisinifi::find()->all(),'id','adi'),
        //'filterType' => GridView::FILTER_SELECT2,
        'filterWidgetOptions' => [
            'options' => ['prompt' => ''],
            'pluginOptions' => ['allowClear' => true],
        ],
        'value'=>'bilgiSinifi.adi',
        'vAlign' => 'middle',
        'width' => '10%',
    ],  
    [
        'attribute'=>'kategori',
        'width' => '10%',
        'format'=>'raw',
        'filter'=>ArrayHelper::map(Bgyskategori::find()->all(),'id','adi'),
        //'filterType' => GridView::FILTER_SELECT2,
        'filterWidgetOptions' => [
            'options' => ['prompt' => ''],
            'pluginOptions' => ['allowClear' => true],
        ],
        'value'=>function ($data)
            {
                return @Bgyskategori::find()->where(['id'=>$data->kategori])->one()->adi;
            }
    ],            
    [
        'attribute'=>'varlik_degeri',
        'width' => '10%',
        'format'=>'raw',
        'filter'=>array(1 =>"Düşük" ,2=>"Orta",3=>'Yüksek',4=>'Çok Yüksek'),
        //'filterType' => GridView::FILTER_SELECT2,
        'filterWidgetOptions' => [
            'options' => ['prompt' => ''],
            'pluginOptions' => ['allowClear' => true],
        ],
        'value'=>function ($data)
            {
                return bgys::varlikdegeri($data->varlik_degeri);
            }
    ],
    [
        'label' => 'Tamamlama Durumu',
        'format' => 'raw',
        'filter' => false,
        'value' => function ($data) {
            $missing = $data->source_device_id && (!$data->departman || !$data->bilgi_sinifi || !$data->lokasyon
                || !$data->gizlilik || !$data->butunluk || !$data->erisilebilirlik || !$data->varlik_degeri);
            return $missing
                ? '<span class="label label-warning">Sınıflandırma eksik</span>'
                : '<span class="label label-success">Tam</span>';
        },
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'header'=>'İşlemler',
        'width' => '10%',
        'template' => '{view}{update}{delete} ',        
        'buttons' => [                                      
            'view' => function ($url,$model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['view', 'id' => $model->id], [
                            'class' => 'btn btn-success btn-xs', 'title' => 'İncele', 'data-pjax' => '0',
                        ]);
                         },         
            'update' => function($url, $model) {   //hertürlü
                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update', 'id' => $model->id], [
                    'class' => 'btn btn-warning btn-xs', 'title' => 'Güncelle', 'data-pjax' => '0',
                ]);
                //return Html::a(Yii::t('app','Update'), ['update', 'id'=>$model->id],['class' => 'btn btn-success modalButton3'] );

            },
            'delete' => function($url, $model) {   //onaylanmamışsa ve kesin başvuru yapmamışsa
                return  Html::a('<span class="glyphicon glyphicon-trash"></span>', 
                                                ['delete', 'id'=>$model->id] ,
                                                [   'class' => 'btn btn-danger btn-xs',
                                                    'data-pjax' => '0',
                                                    'title'=>"Sil",
                                                    'data' => [
                                                        'confirm' => 'Bu kaydın dosyasını silmek istediğinizden emin misiniz?',
                                                        'method' => 'post',
                                                    ]
                                                ]) ;
            } 
        ]     
    ]
];
Modal::begin([
    //'header'=>"<h2>Katılım Formu</h2>",
    'id'=>'modal',
    'options' => [
        'tabindex' => false,
    ],
    'size'=>'modal-lg',
]);

echo "<div id='modalContent'></div>";
Modal::end();
   
Pjax::begin(['id' => 'envanter_pjax']);
echo GridView::widget([
    'id' => 'kv-grid-demo-envanter',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => $gridColumns, // check the configuration for grid columns by clicking button above
    'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
    'headerRowOptions' => ['class' => 'kartik-sheet-style'],
    'filterRowOptions' => ['class' => 'kartik-sheet-style'],
    'pjaxSettings'=>[
        'neverTimeout'=>true,
        'enablePushState' => false,
    ],
    'pjax'=>true,
    'bordered' => true,
    'striped' => true,
    'hover' => true,
    'persistResize' => false,
    'toggleDataOptions' => ['minCount' => 10],
]);
Pjax::end();
?>

</div>
<?php $this->registerJs(
    'function init_click_handlers(){
           $(".modalButton2").click(function() {
            //alert(fID);
                $.get(
                    "create",
                    function (data)
                    {
                        $("#modal").find(".modal-body").html(data);
                        $(".modal-body").html(data);
                        $("#modal").modal("show");              
                    }    
                );    
        }); 

        $(".modalButton3").click(function() {
            var fID = $(this).closest("tr").data("key");
            //alert(fID);
                $.get(
                    "update",
                    {  id: fID   },
                    function (data)
                    {
                        $("#modal").find(".modal-body").html(data);
                        $(".modal-body").html(data);
                        $("#modal").modal("show");              
                    }    
                );    
        });
        $(".modalButton4").click(function() {
            var fID = $(this).closest("tr").data("key");
            //alert(fID);
                $.get(
                    "view",
                    {  id: fID   },
                    function (data)
                    {
                        $("#modal").find(".modal-body").html(data);
                        $(".modal-body").html(data);
                        $("#modal").modal("show");              
                    }    
                );    
        });
    };

    init_click_handlers(); //first run
    $("#some_pjax_id").on("pjax:success", function() {
      init_click_handlers(); //reactivate links in grid after pjax update
    });'
);?>
<script type="text/javascript">
    // get the current gridview page url

var url = $('#kv-grid-demo-envanter-pjax li.active a').attr('href');
$.pjax.reload({container:'#kv-grid-demo-envanter-pjax', url: url});


/*$("#kv-grid-demo-envanter").click(function() {
    $.pjax.reload({container: '#envanter_pjax', async: false});
});*/
</script>
