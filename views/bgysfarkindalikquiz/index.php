<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BgysfarkindalikquizSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Farkındalık Eğitimi';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bgysfarkindalikquiz-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php //echo Html::a('Create Bgysfarkindalikquiz', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'cevaplayan',
            //'ip',
            'puan',
            //'cevaplamatarihi',
            [
                'attribute' => 'cevaplamatarihi',
                'format' => ['date', 'php:d/m/Y H:i:s']
            ],
            //'cevaplar',

            ['class' => 'yii\grid\ActionColumn',

                'template' =>'{view} {delete} ',

                'buttons' => [
                    'view' => function($url, $model) {   //hertürlü
                        //echo "<pre>";var_dump(Basvurukesinkayit::find()->where(['kurskayitid' => $model->id])->one());echo "</br>";
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => Yii::t('app', 'View')]);
                    },
                    'delete' => function($url, $model) {   //onaylanmamışsa ve kesin başvuru yapmamışsa
                        return                             
                            Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                'title' => Yii::t('app', 'Delete'),
                                'data' => [
                                    'confirm' => 'Bu kayıdı silmek istediğinizden emin misiniz?',
                                    'method' => 'post',
                                ],
                            ]);
                    }, 
                ],

            ],
        ],
    ]); ?>
    
</div>
