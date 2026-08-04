<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Yenihostbildir */

$this->title = 'Sunucu Hatırlatma: '.$model->id;
$this->params['breadcrumbs'][] = ['label' => 'Yeni VMler', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="yenihostbildir-view">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'vm_name',
             [
                'attribute'=>'zabbix',
                'format'=>'raw',
                'filter'=>array(0=>'Hayır',1=>'Evet'),
                'value'=>function ($data)
                    {
                        return $data->zabbix==0? "Hayır":"Evet"; 
                    }
            ],
             [
                'attribute'=>'kaspersky',
                'format'=>'raw',
                'filter'=>array(0=>'Hayır',1=>'Evet'),
                'value'=>function ($data)
                    {
                        return $data->kaspersky==0? "Hayır":"Evet"; 
                    }
            ],
             [
                'attribute'=>'ipmanage',
                'format'=>'raw',
                'filter'=>array(0=>'Hayır',1=>'Evet'),
                'value'=>function ($data)
                    {
                        return $data->ipmanage==0? "Hayır":"Evet"; 
                    }
            ],
             [
                'attribute'=>'paloalto',
                'format'=>'raw',
                'filter'=>array(0=>'Hayır',1=>'Evet'),
                'value'=>function ($data)
                    {
                        return $data->paloalto==0? "Hayır":"Evet"; 
                    }
            ],
            [
                'attribute' => 'tarihi',
                'format' => ['date', 'php:d/m/Y']
            ], 
            //'json',
        ],
    ]) ?>

    <p>
        <button type="button" class="btn btn-default" data-dismiss="modal">Tamam</button>
    </p>

</div>
