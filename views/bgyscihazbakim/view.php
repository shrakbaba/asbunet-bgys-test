<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Userbilgi;

/* @var $this yii\web\View */
/* @var $model app\models\Bgyscihazbakim */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Bakım Kayıtları', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="bgyscihazbakim-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Güncelle', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Sil', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Bu veriyi silmek istediğinizden emin misiniz?',
                'method' => 'post',
            ],
            ]) ?>
        </p>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
            //'id',
                //'cihazid',
                [
                'attribute' => 'cihazid',                
                'format'=>'raw',
                'value' => Html::a(($model->cihaz->cihazTuru->cihaz_turu)."/".($model->cihaz->marka->marka)."/".$model->cihaz->model->model , '/envcihazliste/view?id='.$model->cihazid ,['target'=>'_blank'])
            ], 
               // 'sorumlu',             
            [
            'attribute'=>'sorumlu',
            'format'=>'raw',
            //'value'=> @$model->sorumlu0->ad." ".@$model->sorumlu0->soyad." ".@$model->sorumlu0->username,
            'value'=>  Yii::$app->params['giristipi']==1 ? @Userbilgi::findOne(['kisi_id'=>$model->sorumlu])->ad.' '.@Userbilgi::findOne(['kisi_id'=>$model->sorumlu])->soyad.' / '.@$model->sorumlu0->username : @$model->sorumlu0->ad." ".@$model->sorumlu0->soyad." ".@$model->sorumlu0->username ,
            ], 
                'periyod',
                //'bakimformlari',
            //'sozlesme',
                /*[
                    'attribute' => 'kayittarihi',
                    'format' => ['date', 'php:d/m/Y']
                ],*/
                /*[
                    'attribute' => 'guncellemetarihi',
                    'format' => ['date', 'php:d/m/Y']
                ],*/
                [
                    'attribute' => 'bakimtarihi',
                    'format' => ['date', 'php:d/m/Y']
                ],
            ],
            ]) ?>
            <?php if ($model->sozlesme ) {      ?>
            <span class="btn btn-info col-md-2" onclick="window.open('/uploads/bgys/<?php echo md5("bakim")."/".$model->sozlesme ?>')" style="margin: 10px;color:white">Sözleşme</span>
            <?php } ?>

            <?php 
            if ($model->bakimformlari and $model->bakimformlari!="null" ) {      
                $model->bakimformlari=json_decode($model->bakimformlari);
                
                foreach (@$model->bakimformlari as $key => $value) { ?>
                <span class="btn btn-info col-md-2" onclick="window.open('/uploads/bgys/<?php echo md5("bakim")."/".$value ?>')" style="margin: 10px;color:white">Bakım<?= $key+1?> </span>

                <?php        }
                ?>

                <?php } ?>

            </div>
