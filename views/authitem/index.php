<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\imdat;

use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\AuthitemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Rbac Nesneleri';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="authitem-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::button('Yetki Nesnesi Ekle', ['value' => Url::to(['/authitem/create']),'class' => 'btn btn-success btn-lg','id'=>'modalButton']) ?>
    </p>


    <?php
    Modal::begin([
        'header'=>"<h2>Yetki Nesnesi Ekle</h2>",
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
            //'type',
            'description:ntext',
            [
                'attribute'=>'type',
                'format'=>'raw',
                'filter'=>array(1=>'Rol',2=>'İzin'),
                'value'=>function ($data)
                    {
                        return imdat::item_tipi($data->type);
                    }
            ],
            //'rule_name',
            //'data',
            //'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
