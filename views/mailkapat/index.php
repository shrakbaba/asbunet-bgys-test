<?php

use yii\helpers\Html;
use yii\grid\GridView;

use yii\helpers\imdat;
/* @var $this yii\web\View */
/* @var $searchModel app\models\MailkapatSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Kapatılacak Mailler';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mailkapat-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Mail Hatırlatma Ekle', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'mailhesabi',
            [
                'attribute' => 'ayrilistarihi',
                'format' => ['date', 'php:d/m/Y'],
                'filter'=>false,
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

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
