<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserbilgiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Kullanıcı Bilgileri';
$this->params['breadcrumbs'][] = $this->title;
$tamVeriGorebilir = Yii::$app->user->can('BGYS_Super_Admin') || Yii::$app->user->can('BGYS_Yonetim_Temsilcisi');
$guncellemeYetkisiVar = Yii::$app->user->can('BGYS_Super_Admin');
$silmeYetkisiVar = Yii::$app->user->can('BGYS_Super_Admin');
$columns = [
    ['class' => 'yii\grid\SerialColumn'],
    'id',
    [
        'attribute' => 'username',
        'value' => 'kisi.username',
        'label' => 'Kullanıcı Adı',
    ],
    'ad',
    'soyad',
    'email:email',
];

if ($tamVeriGorebilir) {
    $columns[] = 'tc';
    $columns[] = 'telefon';
    $columns[] = 'adres';
    $columns[] = 'dogumyili';
}

$columns[] = [
    'class' => 'yii\grid\ActionColumn',
    'header'=>'İşlemler',
    'template' => '{view}{update}{delete}',
    'headerOptions' => ['style' => 'width:90px; white-space:nowrap; text-align:center;'],
    'contentOptions' => ['style' => 'width:90px; white-space:nowrap; text-align:center;'],
    'buttons' => [
        'view' => function ($url, $model) {
            return Html::button('<span class="glyphicon glyphicon-eye-open"></span>', [
                'value' => Url::to(['view', 'id' => $model->id]),
                'class' => 'userbilgi-modal btn btn-success btn-xs',
                'title' => 'Görüntüle',
            ]);
        },
        'update' => function ($url, $model) {
            return Html::button('<span class="glyphicon glyphicon-pencil"></span>', [
                'value' => Url::to(['update', 'id' => $model->id]),
                'class' => 'userbilgi-modal btn btn-warning btn-xs',
                'title' => 'Güncelle',
            ]);
        },
        'delete' => function ($url, $model) {
            return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                'class' => 'btn btn-danger btn-xs',
                'title' => 'Sil',
                'data' => [
                    'confirm' => 'Bu kullanıcı bilgisini silmek istediğinizden emin misiniz?',
                    'method' => 'post',
                ],
            ]);
        },
    ],
    'visibleButtons' => [
        'update' => function ($model) use ($guncellemeYetkisiVar) {
            return $guncellemeYetkisiVar || (!Yii::$app->user->isGuest && (int)$model->kisi_id === (int)Yii::$app->user->identity->id);
        },
        'delete' => function ($model) use ($silmeYetkisiVar) {
            return $silmeYetkisiVar;
        },
    ],
];
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
        'columns' => $columns,
    ]); ?>
</div>
<?php
Modal::begin([
    'id' => 'modal',
    'size' => 'modal-lg',
]);
echo "<div id='modalContent'></div>";
Modal::end();

$this->registerJs(<<<JS
$(document).off('click.userbilgiModal', '.userbilgi-modal').on('click.userbilgiModal', '.userbilgi-modal', function(e) {
    e.preventDefault();
    $('#modalContent').html('<div class="text-center" style="padding:20px;">Yükleniyor...</div>');
    $('#modal').modal('show')
        .find('#modalContent')
        .load($(this).attr('value'));
});
JS
);
?>
