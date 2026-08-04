<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AuthruleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Authrules';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="authrule-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::button('Erişim Kuralı Ekle', ['value' => Url::to(['/authrule/create']),'class' => 'btn btn-success btn-lg','id'=>'modalButton']) ?>
    </p>
    
    <?php
    Modal::begin([
        'header'=>"<h2>Erişim Kuralı Ekle</h2>",
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

            'name',
            'data',
            'created_at',
            'updated_at',

            ['class' => 'yii\grid\ActionColumn', 'header'=>'İşlemler'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
