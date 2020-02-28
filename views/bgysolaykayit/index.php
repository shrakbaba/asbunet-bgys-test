<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use app\models\Userbilgi;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OlaykayitSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Olay Kayıt';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="olaykayit-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::button('Olay Kaydı Aç', ['value' => Url::to(['/bgysolaykayit/create']),'class' => 'btn btn-success btn-lg','id'=>'modalButton']) ?>
        <?php // echo Html::a('Dif Ekle', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

     <?php
    Modal::begin([
        'header'=>"<h2>Olay Kaydı Aç</h2>",
        'id'=>'modal',
        'size'=>'modal-lg',
    ]);

        echo "<div id='modalContent'></div>";
    Modal::end();
    ?>

    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute'=>'userid',
                'format'=>'raw',
                //'value'=>'user.username'." ".'user.ad',
                'value'=>function ($data)
                    {
                        //return @$data->user->username." / ".@$data->user->ad." ".@$data->user->soyad;
                        return Yii::$app->params['giristipi']==1 ? @Userbilgi::findOne(['kisi_id'=>$data->userid])->ad.' '.@Userbilgi::findOne(['kisi_id'=>$data->userid])->soyad.' / '.@$data->user->username : @$data->user->ad." ".@$data->user->soyad." ".@$data->user->username; 
                    }
            ],
            'konu',
            'mudahaleeden',
            [
                'attribute'=>'olaytarihi',
                'format' => ['date', 'php:d/m/Y'],
                'filter'=>false,
            ],


            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>

