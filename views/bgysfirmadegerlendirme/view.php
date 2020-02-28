<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\imdat;
use app\models\Userbilgi;

/* @var $this yii\web\View */
/* @var $model app\models\Firmadegerlendirme */

?>
<div class="firmadegerlendirme-view">

  

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'firmabilgi.firmaadi',
            [
                'attribute'=>'degerlendiren',
                'label'=>'Degerlendiren',
            //'value'=> @$model->user->ad." ".@$model->user->soyad." ".@$model->user->username,
            'value'=>  Yii::$app->params['giristipi']==1 ? @Userbilgi::findOne(['kisi_id'=>$model->degerlendiren])->ad.' '.@Userbilgi::findOne(['kisi_id'=>$model->degerlendiren])->soyad.' / '.@$model->user->username : @$model->user->ad." ".@$model->user->soyad." ".@$model->user->username ,
            ], 
            'toplam',
            [
                'attribute'=>'onay',
                'format'=>'raw',
               /* 'value'=>function ($data)
                    {
                        return $data->onay==0 ? "Onaysız":"Onaylı"; 
                    }*/
                     'value'=>function ($data)
                    {  return imdat::onaydurumu($data->onay);  }
            ],
            'degerlendirilenyil',
            'degerlendirilenhizmet',
            'not',
            [
                'attribute'=>'degerlendirmetarihi',
                'format' => ['date', 'php:d/m/Y']
            ],
            [
                'attribute'=>'guncellemetarihi',
                'format' => ['date', 'php:d/m/Y']
            ],
            'kriter1',
            'kriter2',
            'kriter3',
            'kriter4',
            'kriter5',
            'kriter6',
            'kriter7',
            'kriter8',
            'kriter9',
            'kriter10',
        ],
    ]) ?>

</div>
