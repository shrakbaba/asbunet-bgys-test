<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;
use app\models\Bgysbilgisinifi;
use yii\helpers\bgys;

/* @var $this yii\web\View */
/* @var $model app\models\Bgysvarlikenvanteri */

?>
<div class="bgysvarlikenvanteri-view">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'varlik_adi',           
                       
            [
                'attribute'=>'departman',
                'value'=>$model->departman0->departman,
            ],           
            [
                'attribute'=>'lokasyon',
                'value'=>$model->lokasyon0->lokasyon,
            ],
            [
                'attribute'=>'bilgi_sinifi',
                'value'=>$model->bilgiSinifi->adi,
            ],                    
            [
                'attribute'=>'kategori',
                'value'=>$model->kategori0->adi,
            ],
            'varlik_sahibi',                                
            [
                'attribute'=>'gizlilik',
                //'value'=>$model->gizlilik0->anlam,
                'value'=>function ($data)
                    {
                        return bgys::varlikdegeri($data->gizlilik);
                    }
            ],                    
            [
                'attribute'=>'butunluk',
                //'value'=>$model->butunluk0->anlam,
                'value'=>function ($data)
                    {
                        return bgys::varlikdegeri($data->butunluk);
                    }
            ],                    
            [
                'attribute'=>'erisilebilirlik',
                'value'=>function ($data)
                    {
                        return bgys::varlikdegeri($data->erisilebilirlik);
                    }
                //'value'=>$model->erisilebilirlik0->anlam,
            ],                   
            [
                'attribute'=>'varlik_degeri',
                'value'=>function ($data)
                    {
                        return bgys::varlikdegeri($data->varlik_degeri);
                    }
            ],
            
        ],
    ]) ?>

</div>
