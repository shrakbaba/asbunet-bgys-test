<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Userbilgi;
use app\models\Bgysrisk;

/* @var $this yii\web\View */
/* @var $model app\models\Bgysdiftalep */

$this->title = $model->dif_no;
$this->params['breadcrumbs'][] = ['label' => 'Dif Talep', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bgysdiftalep-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Güncelle', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Sil', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Bu kayıdı silmek istediğinizden emin misiniz?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
           // 'id',
            'dif_no',
            [
                'attribute' => 'talep_tarihi',
                'format' => ['date', 'php:d/m/Y']
            ], 
            'talep_eden',          
            [
                'attribute'=>'durum',
                'format'=>'raw',
                'value'=>function ($data)
                    {
                        return $data->durum==0 ? "Devam Ediyor":"Kapatıldı"; 
                    }
            ],
            [
                'attribute' => 'planlanan_tarih',
                'format' => ['date', 'php:d/m/Y']
            ],
            'dif_konusu',
            [
                'attribute'=>'olusturan_kisi',
                //'value'=> @$model->difsorumlusu->ad." ".@$model->difsorumlusu->soyad." ".@$model->difsorumlusu->username,
                'value'=>  Yii::$app->params['giristipi']==1 ? @Userbilgi::findOne(['kisi_id'=>$model->olusturan_kisi])->ad.' '.@Userbilgi::findOne(['kisi_id'=>$model->olusturan_kisi])->soyad.' / '.@$model->difsorumlusu->username : @$model->difsorumlusu->ad." ".@$model->difsorumlusu->soyad." ".@$model->difsorumlusu->username ,
            ],            
            [
            'attribute'=>'sorumlu',
            'format'=>'raw',
           //'value'=> @$model->sorumlu0->ad." ".@$model->sorumlu0->soyad." ".@$model->sorumlu0->username,
            'value'=>  Yii::$app->params['giristipi']==1 ? @Userbilgi::findOne(['kisi_id'=>$model->sorumlu])->ad.' '.@Userbilgi::findOne(['kisi_id'=>$model->sorumlu])->soyad.' / '.@$model->sorumlu0->username : @$model->sorumlu0->ad." ".@$model->sorumlu0->soyad." ".@$model->sorumlu0->username ,
            ],  
           
        ],
    ]) ?>
    <h3>İlişkili Olduğu Riskler</h3>
    <?php //?BgysriskSearch%5Bid%5D=171
        if ($model->risk_iliskisi) {
            foreach (json_decode($model->risk_iliskisi) as $key => $value) {
                echo "<a href='/bgysrisk/index?BgysriskSearch%5Bid%5D=".$value."'> <b>Risk ".$value."</b> </a> ".Bgysrisk::findOne(intval($value))->risk;echo "<br>";
            }       
        }
    ?>

</div>
