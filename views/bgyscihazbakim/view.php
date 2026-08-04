<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Userbilgi;

/* @var $this yii\web\View */
/* @var $model app\models\Bgyscihazbakim */

$this->title = 'Bakım Kaydı: '.$model->id;
$this->params['breadcrumbs'][] = ['label' => 'Bakım Kayıtları', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="bgyscihazbakim-view">

    <h2><?= Html::encode($this->title) ?></h2>

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
            <p>
                <strong>Cihaz Sözleşmesi:</strong>
                <?= Html::a($model->sozlesme, ['/uploads/bgys/'.md5("bakim").'/'.$model->sozlesme], ['target'=>'_blank']) ?>
            </p>
            <?php } ?>

            <?php 
            if ($model->bakimformlari and $model->bakimformlari!="null" ) {      
                $model->bakimformlari=json_decode($model->bakimformlari);
                
                echo '<p><strong>Bakım Formları:</strong></p><ul>';
                foreach (@$model->bakimformlari as $key => $value) { ?>
                <li><?= Html::a($value, ['/uploads/bgys/'.md5("bakim").'/'.$value], ['target'=>'_blank']) ?></li>

                <?php        }
                echo '</ul>';
                ?>

                <?php } ?>

            <p>
                <button type="button" class="btn btn-default" data-dismiss="modal">Tamam</button>
            </p>

            </div>
