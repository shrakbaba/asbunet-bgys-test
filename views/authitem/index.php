<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\AuthitemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Roller';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="authitem-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::button('Yeni Rol Ekle', ['value' => Url::to(['/authitem/create']),'class' => 'authitem-modal btn btn-success','title'=>'Yeni Rol Ekle']) ?>
    </p>


    <?php
    Modal::begin([
        'id'=>'modal',
        'size'=>'modal-lg',
    ]);

        echo "<div id='modalContent'></div>";
    Modal::end();
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'description:ntext',

            [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'İşlemler',
                'template' => '{view}{update}{delete}',
                'headerOptions' => ['style' => 'width:90px; white-space:nowrap; text-align:center;'],
                'contentOptions' => ['style' => 'width:90px; white-space:nowrap; text-align:center;'],
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::button('<span class="glyphicon glyphicon-eye-open"></span>', [
                            'value' => Url::to(['view', 'id' => $model->name]),
                            'class' => 'authitem-modal btn btn-success btn-xs',
                            'title' => 'Görüntüle',
                        ]);
                    },
                    'update' => function ($url, $model) {
                        return Html::button('<span class="glyphicon glyphicon-pencil"></span>', [
                            'value' => Url::to(['update', 'id' => $model->name]),
                            'class' => 'authitem-modal btn btn-warning btn-xs',
                            'title' => 'Güncelle',
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model->name], [
                            'class' => 'btn btn-danger btn-xs',
                            'title' => 'Sil',
                            'data-bgys-confirm-title' => '<span class="glyphicon glyphicon-warning-sign"></span> Silme Onayı',
                            'data-bgys-confirm-description' => 'Bu işlem geri alınamaz.',
                            'data-bgys-confirm-ok' => 'Evet, Sil',
                            'data-bgys-confirm-class' => 'btn-danger',
                            'data' => [
                                'confirm' => 'Bu rolü silmek istediğinizden emin misiniz?',
                                'method' => 'post',
                            ],
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>
</div>
<?php
$this->registerJs(<<<JS
$(document).off('click.authitemModal', '.authitem-modal').on('click.authitemModal', '.authitem-modal', function(e) {
    e.preventDefault();
    $('#modalContent').html('<div class="text-center" style="padding:20px;">Yükleniyor...</div>');
    $('#modal').modal('show')
        .find('#modalContent')
        .load($(this).attr('value'));
});
JS
);
?>
