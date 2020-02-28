<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BgysriskkabulSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Risk Kabuller';
$this->params['breadcrumbs'][] = ['label' => 'Riskler', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bgysriskkabul-index">

    <h3><?= Html::encode($this->title) ?></h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'riskid',
            [
                'attribute'=>'riskid',
                'format'=>'raw',
                'value'=>function ($data)
                    {
                        return $data->risk->risk;
                    }
            ], 
            'aciklama',
            //'kabuleden',
            [
                'attribute'=>'kabuleden',
                'format'=>'raw',
                'value'=>function ($data)
                    {
                        return $data->kabuleden0->username;
                    }
            ], 
            //'tarih',
            [
                'attribute'=>'tarih',
                'format' => ['date', 'php:d/m/Y'],
                'filter'=>false,
            ],

            ['class' => 'yii\grid\ActionColumn',

                'template' =>'{delete}',

                'buttons' => [   
                    'delete' => function($url, $model) {   //onaylanmamışsa ve kesin başvuru yapmamışsa
                        return 
                            Html::a('<span class="glyphicon glyphicon-trash"></span>', ['riskkabuldelete', 'id'=>$model->id],['class'=>'btn btn-info btn-xs' ] );
                    },  
                ],

            ],
        ],
    ]); ?>
</div>
