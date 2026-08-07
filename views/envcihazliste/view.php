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
            'attribute' => 'bgys_asset_id',
            'value' => $model->bgysAsset ? $model->bgysAsset->varlik_adi : '(Bağlı değil)',
        ],
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

<h4>Zimmet Geçmişi</h4>
<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Kullanıcı</th>
                <th>Teslim Tarihi</th>
                <th>İade Tarihi</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($model->zimmetHistory) { ?>
            <?php foreach ($model->zimmetHistory as $assignment) { ?>
                <?php $userInfo = Userbilgi::findOne(['kisi_id' => $assignment->user_id]); ?>
                <tr>
                    <td><?= Html::encode($userInfo ? trim($userInfo->ad . ' ' . $userInfo->soyad) : 'Kullanıcı #' . $assignment->user_id) ?></td>
                    <td><?= Yii::$app->formatter->asDatetime($assignment->teslim_tarihi, 'php:d/m/Y H:i') ?></td>
                    <td><?= $assignment->iade_tarihi ? Yii::$app->formatter->asDatetime($assignment->iade_tarihi, 'php:d/m/Y H:i') : 'Aktif' ?></td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr><td colspan="3">Zimmet geçmişi bulunmuyor.</td></tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<?php if ($model->dosya) {      ?>
                        <span class="btn btn-info col-md-2" onclick="window.open('/uploads/bgys/<?php echo md5("cihaz")."/".$model->dosya ?>')" style="margin: 10px;color:white">Belge</span>
                <?php } ?>

</div>
