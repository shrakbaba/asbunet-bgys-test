<?php

use yii\helpers\Html;
use yii\grid\GridView;

use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\AuthassignmentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Rol Atamaları';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="authassignment-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::button('Rol Ataması Ekle', ['value' => Url::to(['authassignment/create']),'class' => 'btn btn-success btn-lg','id'=>'modalButton']) ?>
    </p>

<?php
Modal::begin([       
    'header'=>"<h2>Rol Ataması Ekle</h2>",
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

            'item_name',
            [
                'attribute'=>'user_id',
                'format'=>'raw',
                'value'=>'user.username',
            ],
           // 'created_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>  
<?php Pjax::end(); ?>
</div>
