<?php

use yii\helpers\Html;
use yii\grid\GridView;

use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\AuthitemchildSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Rol Grupları';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="authitemchild-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::button('Rol Grubu Ekle', ['value' => Url::to(['/authitemchild/create']),'class' => 'btn btn-success btn-lg','id'=>'modalButton']) ?>
    </p>

    <?php
    Modal::begin([
        'header'=>"<h2>Rol Grubu Ekle</h2>",
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

            'parent',
            'child',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
