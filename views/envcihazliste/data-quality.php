<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $summary array */
/* @var $issue string|null */

$this->title = 'Cihaz Verisi Tamamlama';
$this->params['breadcrumbs'][] = ['label' => 'Cihaz Listesi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$cards = [
    null => ['Tüm Kayıtlar', $summary['total'], 'default'],
    'bgys' => ['BGYS Varlığı Eksik', $summary['bgys'], 'danger'],
    'amount' => ['Adet Eksik', $summary['amount'], 'warning'],
    'location' => ['Konum Eksik', $summary['location'], 'warning'],
    'document' => ['Belge Eksik', $summary['document'], 'info'],
    'expired' => ['Süresi Geçmiş', $summary['expired'], 'danger'],
    'legacy' => ['Aktarılan Eski Kayıt', $summary['legacy'], 'default'],
];
?>

<div class="envcihazliste-data-quality">
    <h1><?= Html::encode($this->title) ?></h1>
    <p class="text-muted">Bu ekran eksik alanları gösterir; hiçbir veriyi otomatik olarak değiştirmez.</p>

    <div class="row" style="margin-bottom: 15px;">
        <?php foreach ($cards as $key => $card): ?>
            <div class="col-md-3 col-sm-6" style="margin-bottom: 8px;">
                <?= Html::a(
                    Html::encode($card[0]) . '<br><strong style="font-size:22px">' . (int)$card[1] . '</strong>',
                    ['data-quality', 'issue' => $key],
                    ['class' => 'btn btn-' . ($issue === $key ? 'primary' : $card[2]) . ' btn-block', 'encode' => false]
                ) ?>
            </div>
        <?php endforeach; ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            ['attribute' => 'cihaz_turu_id', 'value' => 'cihazTuru.cihaz_turu', 'label' => 'Cihaz Türü'],
            ['attribute' => 'marka_id', 'value' => 'marka.marka', 'label' => 'Marka'],
            ['attribute' => 'model_id', 'value' => 'model.model', 'label' => 'Model'],
            [
                'label' => 'Eksik / Kontrol Edilecek Alanlar',
                'format' => 'raw',
                'value' => function ($model) {
                    $items = [];
                    if (!$model->bgys_asset_id) $items[] = '<span class="label label-danger">BGYS varlığı</span>';
                    if (!$model->adet || $model->adet <= 0) $items[] = '<span class="label label-warning">Adet</span>';
                    if (!trim((string)$model->konum)) $items[] = '<span class="label label-warning">Konum</span>';
                    if (!trim((string)$model->dosya)) $items[] = '<span class="label label-info">Belge</span>';
                    if ($model->garanti_bitis && $model->garanti_bitis < date('Y-m-d')) $items[] = '<span class="label label-danger">Süre geçmiş</span>';
                    if ($model->is_legacy) $items[] = '<span class="label label-default">Eski kayıt</span>';
                    return implode(' ', $items) ?: '<span class="label label-success">Tam</span>';
                },
            ],
            [
                'attribute' => 'bgys_asset_id',
                'value' => function ($model) {
                    return $model->bgysAsset ? $model->bgysAsset->varlik_adi : 'Bağlı değil';
                },
                'label' => 'BGYS Varlığı',
            ],
            [
                'label' => 'İşlem',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::button('Tamamla', [
                        'class' => 'btn btn-warning btn-xs device-complete',
                        'data-url' => Url::to(['update', 'id' => $model->id]),
                    ]);
                },
            ],
        ],
        'bordered' => true,
        'striped' => true,
        'hover' => true,
    ]) ?>
</div>

<?php
Modal::begin(['id' => 'device-complete-modal', 'size' => 'modal-lg']);
echo '<div id="device-complete-content"></div>';
Modal::end();

$this->registerJs(<<<JS
$(document).off('click.deviceComplete', '.device-complete').on('click.deviceComplete', '.device-complete', function () {
    var modal = $('#device-complete-modal');
    modal.find('.modal-body').html('<p>Yükleniyor...</p>');
    modal.modal('show');
    $.get($(this).data('url')).done(function (html) {
        modal.find('.modal-body').html(html);
    }).fail(function () {
        modal.find('.modal-body').html('<div class="alert alert-danger">Kayıt formu yüklenemedi.</div>');
    });
});
JS
);
?>
