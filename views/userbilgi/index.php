<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserbilgiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Kullanıcı Bilgileri';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="userbilgi-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php // Html::a('Create Userbilgi', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'ad',
            'soyad',
            'email:email',
            'tc',
            //'telefon',
            //'adres',
            //'dogumyili',
            //'kisi_id',
            //'tarihi',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
