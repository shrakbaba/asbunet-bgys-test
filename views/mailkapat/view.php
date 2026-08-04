<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Mailkapat */

$this->title = 'Mail Hatırlatma: '.$model->id;
$this->params['breadcrumbs'][] = ['label' => 'Kapatılacak Mailler', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mailkapat-view">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
           // 'id',
            'mailhesabi',
            [
                'attribute' => 'ayrilistarihi',
                'format' => ['date', 'php:d/m/Y']
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
        ],
    ]) ?>

    <p>
        <button type="button" class="btn btn-default" data-dismiss="modal">Tamam</button>
    </p>

</div>
