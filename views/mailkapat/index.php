<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MailkapatSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Kapatılacak Mailler';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mailkapat-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::button('Mail Kapatma Hatırlatması Ekle', ['value' => Url::to(['create']),'class' => 'btn btn-success hatirlatma-modal','title'=>'Mail Kapatma Hatırlatması Ekle']) ?>
    </p>

<?php
Modal::begin([
    'header'=>"<h3 id='hatirlatma-modal-title'>Mail Kapatma Hatırlatması</h3>",
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

            //'id',
            'mailhesabi',
            [
                'attribute' => 'ayrilistarihi',
                'format' => ['date', 'php:d/m/Y'],
            ],
            [
                'attribute'=>'kapatildi',
                'format'=>'raw',
                'filter'=>array(0=>'Hayır',1=>'Evet'),
                'value'=>function ($data)
                    {
                        return $data->kapatildi==0? "Hayır":"Evet"; 
                    }
            ],
[
                'class' => 'yii\grid\ActionColumn',
                'header'=>'İşlemler',
                'headerOptions' => ['style' => 'width:8%'],
                'template' => '{view} {update} {delete}' ,  
                'buttons' => [                                      
                    'view' => function ($url,$model) {
                        return  ( 
                            Html::button('<span class="glyphicon glyphicon-eye-open"></span>', ['value' => Url::to(['view','id'=>$model->id]),'class' => 'hatirlatma-modal btn btn-success btn-xs' ,'title'=>"İncele"])                      
                            );
                         },
                    'update' => function ($url,$model) {
                        return  ( 
                            Html::button('<span class="glyphicon glyphicon-pencil"></span>', ['value' => Url::to(['update','id'=>$model->id]),'class' => 'hatirlatma-modal btn btn-warning btn-xs' ,'title'=>"Mail Kapatma Hatırlatması Güncelle"])                         
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
function bgysHatirlatmaModalHazirla() {
    $(document).off('click.mailHatirlatmaModal', '.hatirlatma-modal').on('click.mailHatirlatmaModal', '.hatirlatma-modal', function(e) {
        e.preventDefault();
        var button = $(this);
        $('#hatirlatma-modal-title').text(button.attr('title') || 'Mail Kapatma Hatırlatması');
        $('#modalContent').html('<div class="text-center" style="padding:20px;">Yükleniyor...</div>');
        $('#modal').modal('show')
            .find('#modalContent')
            .load(button.attr('value'));
    });
}

bgysHatirlatmaModalHazirla();
$(document).on('pjax:success', function() {
    bgysHatirlatmaModalHazirla();
});

$(document).off('beforeSubmit.mailHatirlatmaForm', '#modalContent form').on('beforeSubmit.mailHatirlatmaForm', '#modalContent form', function(e) {
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
