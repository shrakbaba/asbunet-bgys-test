<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BgyslogsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Hareket Kayıtları';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bgyslogs-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'controller',
            'action',
            //'userid',
            [
                'attribute'=>'userid',
                'format'=>'raw',
                'value'=>function ($data)
                    {
                        return @$data->logyapan->username;
                    }
            ], 
            [
                'attribute'=>'date',
                'format' => ['date', 'php:d/m/Y H:i:s'],
                'filter'=>false,
            ],
            //'not',
            //'islem',

             ['class' => 'yii\grid\ActionColumn',

                'template' =>'{view} ',

                

            ],
        ],
    ]); ?>
</div>
