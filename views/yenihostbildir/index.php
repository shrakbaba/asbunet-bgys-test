<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\YenihostbildirSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Yeni VMler';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="yenihostbildir-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::button('Yeni VM Hatırlatması Ekle', ['value' => Url::to(['create']),'class' => 'btn btn-success sunucu-hatirlatma-modal','title'=>'Yeni VM Hatırlatması Ekle']) ?>
    </p>

<?php
Modal::begin([
    'header'=>"<h3 id='sunucu-hatirlatma-modal-title'>Sunucu Hatırlatma</h3>",
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

            'vm_name',
            [
                'attribute'=>'zabbix',
                'format'=>'raw',
                'filter'=>array(0=>'Hayır',1=>'Evet'),
                'value'=>function ($data)
                    {
                        return $data->zabbix==0? "Hayır":"Evet"; 
                    }
            ],
            [
                'attribute'=>'kaspersky',
                'format'=>'raw',
                'filter'=>array(0=>'Hayır',1=>'Evet'),
                'value'=>function ($data)
                    {
                        return $data->kaspersky==0? "Hayır":"Evet"; 
                    }
            ],
            [
                'attribute'=>'ipmanage',
                'format'=>'raw',
                'filter'=>array(0=>'Hayır',1=>'Evet'),
                'value'=>function ($data)
                    {
                        return $data->ipmanage==0? "Hayır":"Evet"; 
                    }
            ],
            [
                'attribute'=>'paloalto',
                'format'=>'raw',
                'filter'=>array(0=>'Hayır',1=>'Evet'),
                'value'=>function ($data)
                    {
                        return $data->paloalto==0? "Hayır":"Evet"; 
                    }
            ],
            //'tarihi',
            //'json',
            [
                'attribute' => 'tarihi',
                'format' => ['date', 'php:d/m/Y'],
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'İşlemler',
                'headerOptions' => ['style' => 'width:8%'],
                'template' => '{view} {update} {delete}' ,  
                'buttons' => [                                      
                    'view' => function ($url,$model) {
                        return  ( 
                            Html::button('<span class="glyphicon glyphicon-eye-open"></span>', ['value' => Url::to(['view','id'=>$model->id]),'class' => 'sunucu-hatirlatma-modal btn btn-success btn-xs' ,'title'=>"İncele"])                      
                            );
                         },
                    'update' => function ($url,$model) {
                        return  ( 
                            Html::button('<span class="glyphicon glyphicon-pencil"></span>', ['value' => Url::to(['update','id'=>$model->id]),'class' => 'sunucu-hatirlatma-modal btn btn-warning btn-xs' ,'title'=>"Yeni VM Hatırlatması Güncelle"])                         
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
</div>
<?php
$this->registerJs(<<<JS
function bgysSunucuHatirlatmaModalHazirla() {
    $(document).off('click.sunucuHatirlatmaModal', '.sunucu-hatirlatma-modal').on('click.sunucuHatirlatmaModal', '.sunucu-hatirlatma-modal', function(e) {
        e.preventDefault();
        var button = $(this);
        $('#sunucu-hatirlatma-modal-title').text(button.attr('title') || 'Sunucu Hatırlatma');
        $('#modalContent').html('<div class="text-center" style="padding:20px;">Yükleniyor...</div>');
        $('#modal').modal('show')
            .find('#modalContent')
            .load(button.attr('value'));
    });
}

bgysSunucuHatirlatmaModalHazirla();
$(document).on('pjax:success', function() {
    bgysSunucuHatirlatmaModalHazirla();
});

$(document).off('beforeSubmit.sunucuHatirlatmaForm', '#modalContent form').on('beforeSubmit.sunucuHatirlatmaForm', '#modalContent form', function(e) {
    e.preventDefault();
    var form = $(this);

    $.ajax({
        url: form.attr('action'),
        type: form.attr('method') || 'post',
        data: form.serialize(),
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
