<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Bgysfarkindalikquiz */

$this->title = ($model->egitim ? $model->egitim->baslik : 'Farkındalık Eğitimi') . ' Quiz Sonucu';
$cevaplar = json_decode($model->cevaplar, true) ?: [];
?>

<div class="bgysfarkindalikquiz-cevaplar">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::button('Yeniden Başla', [
            'value' => Url::to(['/bgysfarkindalikquiz/create', 'egitim_id' => $model->egitim_id, 'yeniden' => 1]),
            'class' => 'quiz-yeniden-basla btn btn-warning',
        ]) ?>
    </p>

    <table class="table table-bordered table-striped">
        <tr>
            <th>Ad Soyad</th>
            <td><?= Html::encode($model->cevaplayanAdSoyad) ?></td>
        </tr>
        <tr>
            <th>Eğitim</th>
            <td><?= Html::encode($model->egitim ? $model->egitim->baslik : '(Veri Yok)') ?></td>
        </tr>
        <tr>
            <th>Son Puan</th>
            <td><?= Html::encode($model->puan) ?></td>
        </tr>
        <tr>
            <th>Cevaplama Tarihi</th>
            <td><?= Yii::$app->formatter->asDatetime($model->cevaplamatarihi, 'php:d/m/Y H:i:s') ?></td>
        </tr>
    </table>

    <h4>Verdiğiniz Cevaplar</h4>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Soru</th>
                <th>Verilen Cevap</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($model->quizSorulari as $index => $quizSoru) { ?>
                <?php
                $alan = 'soru' . ($index + 1);
                $cevapIndex = $cevaplar[$alan] ?? null;
                $secenekler = $quizSoru['secenekler'] ?? [];
                ?>
                <tr>
                    <td><?= Html::encode($quizSoru['soru'] ?? '') ?></td>
                    <td><?= $cevapIndex !== null && isset($secenekler[$cevapIndex]) ? Html::encode($secenekler[$cevapIndex]) : '<span class="text-muted">(Cevap Yok)</span>' ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <div class="form-group">
        <?= Html::button('Tamam', ['class' => 'btn btn-default', 'data-dismiss' => 'modal']) ?>
    </div>
</div>

<?php
$this->registerJs(<<<JS
$(document).off('click.quizYenidenBasla', '.quiz-yeniden-basla').on('click.quizYenidenBasla', '.quiz-yeniden-basla', function(e) {
    e.preventDefault();
    $('#modalContent').html('<div class="text-center" style="padding:20px;">Quiz yükleniyor...</div>');
    $('#modalContent').load($(this).attr('value'));
});
JS
);
?>
