<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;
use app\models\Bgysbilgisinifi;
use yii\helpers\bgys;
use app\models\Bgysvarlikenvanteri;

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
                'attribute' => 'asset_type',
                'value' => Bgysvarlikenvanteri::assetTypeOptions()[$model->asset_type] ?? $model->asset_type,
            ],
                       
            [
                'attribute'=>'departman',
                'value'=>$model->departman0 ? $model->departman0->departman : 'Belirtilmemiş',
            ],           
            [
                'attribute'=>'lokasyon',
                'value'=>$model->lokasyon0 ? $model->lokasyon0->lokasyon : 'Belirtilmemiş',
            ],
            [
                'attribute'=>'bilgi_sinifi',
                'value'=>$model->bilgiSinifi ? $model->bilgiSinifi->adi : 'Belirtilmemiş',
            ],                    
            [
                'attribute'=>'kategori',
                'value'=>$model->kategori0 ? $model->kategori0->adi : 'Belirtilmemiş',
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
