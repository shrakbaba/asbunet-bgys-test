<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\AuthitemchildSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Rol İlişkileri';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="authitemchild-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::button('Rol Grubu Ekle', ['value' => Url::to(['/authitemchild/create']),'class' => 'authitemchild-modal btn btn-success','title'=>'Rol Grubu Ekle']) ?>
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

            'parent',
            'child',

            [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'İşlemler',
                'template' => '{view}{update}{delete}',
                'headerOptions' => ['style' => 'width:90px; white-space:nowrap; text-align:center;'],
                'contentOptions' => ['style' => 'width:90px; white-space:nowrap; text-align:center;'],
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::button('<span class="glyphicon glyphicon-eye-open"></span>', [
                            'value' => Url::to(['view', 'parent' => $model->parent, 'child' => $model->child]),
                            'class' => 'authitemchild-modal btn btn-success btn-xs',
                            'title' => 'Görüntüle',
                        ]);
                    },
                    'update' => function ($url, $model) {
                        return Html::button('<span class="glyphicon glyphicon-pencil"></span>', [
                            'value' => Url::to(['update', 'parent' => $model->parent, 'child' => $model->child]),
                            'class' => 'authitemchild-modal btn btn-warning btn-xs',
                            'title' => 'Güncelle',
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'parent' => $model->parent, 'child' => $model->child], [
                            'class' => 'btn btn-danger btn-xs',
                            'title' => 'Sil',
                            'data-bgys-confirm-title' => '<span class="glyphicon glyphicon-warning-sign"></span> Silme Onayı',
                            'data-bgys-confirm-description' => 'Bu işlem geri alınamaz.',
                            'data-bgys-confirm-ok' => 'Evet, Sil',
                            'data-bgys-confirm-class' => 'btn-danger',
                            'data' => [
                                'confirm' => 'Bu rol ilişkisini silmek istediğinizden emin misiniz?',
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
$(document).off('click.authitemchildModal', '.authitemchild-modal').on('click.authitemchildModal', '.authitemchild-modal', function(e) {
    e.preventDefault();
    $('#modalContent').html('<div class="text-center" style="padding:20px;">Yükleniyor...</div>');
    $('#modal').modal('show')
        .find('#modalContent')
        .load($(this).attr('value'));
});
JS
);
?>
