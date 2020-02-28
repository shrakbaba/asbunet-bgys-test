<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\bgys;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use app\models\Userbilgi;

/* @var $this yii\web\View */
/* @var $model app\models\Bgysrisk */

?>
<div class="bgysrisk-view">

     <?php
    Modal::begin([
        'header'=>"<h2>Risk Kabul</h2>",
        'id'=>'modal',
        'size'=>'modal-lg',
    ]);

        echo "<div id='modalContent'></div>";
    Modal::end();
    ?>

    <?php Pjax::begin(); ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',        
            [
                'attribute'=>'varlik',
                'value'=>$model->varlik0->varlik_adi,
            ],                      
            [
                'attribute'=>'departman',
                'value'=>$model->departman0->departman,
            ], 
            'risk',
            'risk_nedeni',       
            [
                'attribute'=>'risk_sorumlusu',
                //'value'=>$model->riskSorumlusu->username,
                'value'=>  Yii::$app->params['giristipi']==1 ? @Userbilgi::findOne(['kisi_id'=>$model->risk_sorumlusu])->ad.' '.@Userbilgi::findOne(['kisi_id'=>$model->risk_sorumlusu])->soyad.' / '.@$model->riskSorumlusu->username : @$model->riskSorumlusu->ad." ".@$model->riskSorumlusu->soyad." ".@$model->riskSorumlusu->username ,
            ], 

            [
                'attribute'=>'olasilik_onceki',
                'value'=>$model->olasilikOnceki->deger,
            ], 
            [
                'attribute'=>'gizlilik_onceki',
                'value'=>$model->gizlilikOnceki->anlam,
            ], [
                'attribute'=>'butunluk_onceki',
                'value'=>$model->butunlukOnceki->anlam,
            ], [
                'attribute'=>'erisilebilirlik_onceki',
                'value'=>$model->erisilebilirlikOnceki->anlam,
            ], 
            'riskdegeri_onceki',

            [
                'attribute'=>'olasilik_sonraki',
                'value'=>@$model->olasilikSonraki->deger,
            ], 
            [
                'attribute'=>'gizlilik_sonraki',
                'value'=>@$model->gizlilikSonraki->anlam,
            ], [
                'attribute'=>'butunluk_sonraki',
                'value'=>@$model->butunlukSonraki->anlam,
            ], [
                'attribute'=>'erisilebilirlik_sonraki',
                'value'=>@$model->erisilebilirlikSonraki->anlam,
            ], 
            'riskdegeri_sonraki',
            'yuksek_riskin_sebebi',
            //'ozetdurum'                              
            [
                'attribute'=>'ozetdurum',
                'value'=>function ($data)
                    {
                        return bgys::ozetdurum($data->ozetdurum);
                    }
            ],
        ],
    ]) ?>
<?php Pjax::end(); ?>
</div>
