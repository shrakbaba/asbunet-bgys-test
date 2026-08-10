<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BgyslogsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Hareket Kayıtları';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bgyslogs-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php
    Modal::begin([
        'id' => 'bgyslogs-modal',
        'size' => 'modal-lg',
    ]);
    echo "<div id='bgyslogs-modal-content'></div>";
    Modal::end();
    ?>

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
                        return $data->actor ?: @$data->logyapan->username;
                    }
            ], 
            [
                'attribute' => 'result',
                'value' => function ($data) {
                    return $data->result === 'failure' ? 'Başarısız' : 'Başarılı';
                },
                'filter' => ['success' => 'Başarılı', 'failure' => 'Başarısız'],
            ],
            'ip_address',
            [
                'attribute'=>'date',
                'format' => ['date', 'php:d/m/Y H:i:s'],
                'filter'=>false,
            ],
            //'not',
            //'islem',

             ['class' => 'yii\grid\ActionColumn',
                'header'=>'İşlemler',
                'headerOptions' => ['style' => 'width:70px; white-space:nowrap; text-align:center;'],
                'contentOptions' => ['style' => 'width:70px; white-space:nowrap; text-align:center;'],

                'template' =>'{view} ',
                'buttons' => [
                    'view' => function($url, $model) {
                        return Html::button('<span class="glyphicon glyphicon-eye-open"></span>', [
                            'value' => Url::to(['view', 'id' => $model->id]),
                            'class' => 'bgyslogs-modal-button btn btn-success btn-xs',
                            'title' => 'Görüntüle',
                        ]);
                    },
                ],

            ],
        ],
    ]); ?>
</div>
<?php
$this->registerJs(<<<JS
$(document).off('click.bgysLogsModal', '.bgyslogs-modal-button').on('click.bgysLogsModal', '.bgyslogs-modal-button', function(e) {
    e.preventDefault();
    $('#bgyslogs-modal-content').html('<div class="text-center" style="padding:20px;">Yükleniyor...</div>');
    $('#bgyslogs-modal').modal('show')
        .find('#bgyslogs-modal-content')
        .load($(this).attr('value'));
});
JS
);
?>
