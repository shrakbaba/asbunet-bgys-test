<?php

use yii\helpers\Html;
//use yii\widgets\DetailView;
use kartik\detail\DetailView;

use yii\helpers\ArrayHelper;
use app\models\Envcihazturu;
use app\models\Userbilgi;


/* @var $this yii\web\View */
/* @var $model app\models\Envcihazliste */
?>
<div class="envcihazliste-view">

<?php 
echo DetailView::widget([
    'model'=>$model,
    'responsive' => true,
    'hover'=>true,
    'mode'=>DetailView::MODE_VIEW,
    'attributes'=>[
        //'cihazTuru.cihaz_turu',
        [
            'attribute'=>'cihaz_turu_id',
            'value'=>$model->cihazTuru->cihaz_turu,
        ],
        [
            'attribute'=>'marka_id',
            'value'=>$model->marka->marka,
        ],
        [
            'attribute'=>'model_id',
            'value'=>$model->model->model,
        ],
        'adet',
        [
            'attribute' => 'alim_tarihi',
            'format' => ['date', 'php:d/m/Y']
        ], 'duyuru6',
            'duyuru3',
            'duyuru1',
            'konum',
            /*[
                'attribute' => 'key',                
                'format'=>'raw',
                'value'=>'<span class="text-justify"><em>' . $model->key . '</em></span>',
                'type'=>DetailView::INPUT_TEXTAREA, 
                'options'=>['rows'=>4]
            ],
            [
                'attribute' => 'service_tag', 
                'format'=>'raw',     
                'value'=>'<span class="text-justify"><em>' . $model->service_tag . '</em></span>',   
                'type'=>DetailView::INPUT_TEXTAREA, 
                'options'=>['rows'=>4]          
            ],*/
            //'dosya',
            'ozet',  
            [
                'attribute' => 'link',                
                'format'=>'raw',
                'value' => (($model->link == " " or $model->link == null) ? "Link yok" : Html::a('Link', $model->link, ['target'=>'_blank']))
                /*function ($model) {   
                    if ($model->link!='')
                       return Html::a('Link', $model->link, ['target'=>'_blank']); else return 'no link';
                },*/
            ],  
            [
                'attribute'=>'service_tag',
                'format'=>'raw',
                'value'=>'<span class="text-justify"><em>' . $model->service_tag . '</em></span>',
                'type'=>DetailView::INPUT_TEXTAREA, 
                
                'valueColOptions' => ['style' => 'height: 85%'],
            ]  ,
            [
            'attribute'=>'zimmet',
            'format'=>'raw',
            //'value'=> @$model->zimmet0->ad." ".@$model->zimmet0->soyad
            'value'=>  Yii::$app->params['giristipi']==1 ? @Userbilgi::findOne(['kisi_id'=>$model->zimmet])->ad.' '.@Userbilgi::findOne(['kisi_id'=>$model->zimmet])->soyad.' / '.@$model->zimmet0->username : @$model->zimmet0->ad." ".@$model->zimmet0->soyad." ".@$model->zimmet0->username 
            ],    
    ]
    
]); ?>
<?php if ($model->dosya) {      ?>
                        <span class="btn btn-info col-md-2" onclick="window.open('/uploads/bgys/<?php echo md5("cihaz")."/".$model->dosya ?>')" style="margin: 10px;color:white">Belge</span>
                <?php } ?>

</div>
