<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $searchModel app\models\BgysfarkindalikquizSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $egitimiIzledi bool */
/* @var $cozulenEgitimIdleri array */
/* @var $izlenenEgitimIdleri array */

$this->title = 'Farkındalık Eğitimi';
$this->params['breadcrumbs'][] = $this->title;
?>

    <?php
    Modal::begin([
        'id'=>'modal',
        'size'=>'modal-lg',
    ]);

        echo "<div id='modalContent'></div>";
    Modal::end();

    Modal::begin([
        'id'=>'videoModal',
        'size'=>'modal-lg',
        'header' => '<h3 id="videoModalTitle"></h3>',
    ]);
    ?>
        <video id="my-video" controls preload="metadata" style="width:100%;height:auto;max-height:70vh;" poster="MY_VIDEO_POSTER.jpg">
            <source id="videoSource" src="" type='video/mp4'>
            <p>
                Videoyu izlemek için tarayıcınızın HTML5 video desteği açık olmalıdır.
            </p>
        </video>
    <?php
    Modal::end();
    ?>
<h1><?= Html::encode($this->title) ?></h1>

<?php if (Yii::$app->user->can('BGYS_Super_Admin')) { ?>
    <p>
        <?= Html::button('Eğitim Videosu / Quiz Ekle', [
            'value' => Url::to(['/bgysfarkindalikquiz/egitimekle']),
            'class' => 'egitim-modal btn btn-success',
            'title' => 'Eğitim Videosu / Quiz Ekle',
        ]) ?>
    </p>
<?php } ?>

<?php if (empty($egitimler)) { ?>
    <div class="alert alert-warning">Henüz aktif farkındalık eğitimi bulunmuyor.</div>
<?php } else { ?>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th style="color:#dd4b39;">Eğitimler</th>
                <th style="color:#dd4b39;">Açıklaması</th>
                <th style="color:#dd4b39;">Video</th>
                <th style="color:#dd4b39;">Quiz</th>
                <th style="color:#dd4b39;">İşlemler</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($egitimler as $egitimSatiri) { ?>
                <?php
                $satirQuizSorulari = $egitimSatiri->quiz_json ? json_decode($egitimSatiri->quiz_json, true) : [];
                $satirQuizHazir = is_array($satirQuizSorulari) && count($satirQuizSorulari) > 0;
                $satirQuizCozuldu = in_array((int)$egitimSatiri->id, (array)$cozulenEgitimIdleri, true);
                $satirEgitimIzlendi = in_array((int)$egitimSatiri->id, (array)$izlenenEgitimIdleri, true);
                $satirQuizUrl = Url::to(['/bgysfarkindalikquiz/create', 'egitim_id' => $egitimSatiri->id]);
                ?>
                <tr class="<?= ($egitim && $egitim->id == $egitimSatiri->id ? 'info' : '') ?>">
                    <td>
                        <?= Html::a(Html::encode($egitimSatiri->baslik), ['egitim', 'id' => $egitimSatiri->id]) ?>
                        <span class="label <?= $egitimSatiri->aktif ? 'label-success' : 'label-default' ?>" style="margin-left:6px;">
                            <?= $egitimSatiri->aktif ? 'Aktif' : 'Pasif' ?>
                        </span>
                    </td>
                    <td><?= Html::encode($egitimSatiri->aciklama ?: '(Veri Yok)') ?></td>
                    <td>
                        <?= Html::button('<span class="glyphicon glyphicon-play"></span> Videoyu Aç', [
                            'class' => 'btn btn-primary btn-xs video-ac',
                            'data-video' => $egitimSatiri->videoUrl,
                            'data-title' => $egitimSatiri->baslik,
                            'data-complete-url' => Url::to(['/bgysfarkindalikquiz/egitimtamamlandi', 'id' => $egitimSatiri->id]),
                            'data-quiz-url' => Url::to(['/bgysfarkindalikquiz/create', 'egitim_id' => $egitimSatiri->id]),
                            'data-quiz-ready' => $satirQuizHazir ? 1 : 0,
                            'data-quiz-solved' => $satirQuizCozuldu ? 1 : 0,
                        ]) ?>
                    </td>
                    <td>
                        <?php if ($satirQuizHazir) { ?>
                            <?= Html::a('Quiz Soruları', ['/bgysfarkindalikquiz/quizdokuman', 'id' => $egitimSatiri->id], ['target' => '_blank', 'style' => 'color:#337ab7;text-decoration:underline;']) ?>
                            <br>
                            <?= Html::button($satirQuizCozuldu ? 'Cevaplarımı Görüntüle' : 'Quiz’e Başla', [
                                'class' => 'quiz-basla btn btn-success btn-xs',
                                'style' => 'margin-top:5px;',
                                'disabled' => !$satirQuizCozuldu && !$satirEgitimIzlendi,
                                'data-url' => $satirQuizUrl,
                            ]) ?>
                        <?php } else { ?>
                            <span class="text-muted">(Veri Yok)</span>
                        <?php } ?>
                    </td>
                    <td>
                        <?php if (Yii::$app->user->can('BGYS_Super_Admin')) { ?>
                            <?= Html::button('<span class="glyphicon glyphicon-eye-open"></span>', [
                                'value' => Url::to(['/bgysfarkindalikquiz/egitimgoster', 'id' => $egitimSatiri->id]),
                                'class' => 'egitim-modal btn btn-success btn-xs',
                                'title' => 'Görüntüle',
                            ]) ?>
                            <?= Html::button('<span class="glyphicon glyphicon-pencil"></span>', [
                                'value' => Url::to(['/bgysfarkindalikquiz/egitimguncelle', 'id' => $egitimSatiri->id]),
                                'class' => 'egitim-modal btn btn-warning btn-xs',
                                'title' => 'Güncelle',
                            ]) ?>
                            <?= Html::a($egitimSatiri->aktif ? '<span class="glyphicon glyphicon-pause"></span>' : '<span class="glyphicon glyphicon-play"></span>', ['/bgysfarkindalikquiz/egitimaktifpasif', 'id' => $egitimSatiri->id], [
                                'class' => 'btn btn-info btn-xs',
                                'title' => $egitimSatiri->aktif ? 'Pasife Al' : 'Aktif Et',
                                'data-bgys-confirm-title' => '<span class="glyphicon glyphicon-warning-sign"></span> Eğitim Durumu Onayı',
                                'data-bgys-confirm-description' => '',
                                'data-bgys-confirm-ok' => $egitimSatiri->aktif ? 'Evet, Pasife Al' : 'Evet, Aktif Et',
                                'data-bgys-confirm-class' => $egitimSatiri->aktif ? 'btn-warning' : 'btn-primary',
                                'data' => [
                                    'confirm' => $egitimSatiri->aktif ? 'Bu eğitimi pasife almak istediğinizden emin misiniz?' : 'Bu eğitimi aktif etmek istediğinizden emin misiniz?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                            <?= Html::a('<span class="glyphicon glyphicon-trash"></span>', ['/bgysfarkindalikquiz/egitimsil', 'id' => $egitimSatiri->id], [
                                'class' => 'btn btn-danger btn-xs',
                                'title' => 'Sil',
                                'data-bgys-confirm-title' => '<span class="glyphicon glyphicon-warning-sign"></span> Silme Onayı',
                                'data-bgys-confirm-description' => 'Bu işlem geri alınamaz.',
                                'data-bgys-confirm-ok' => 'Evet, Sil',
                                'data' => [
                                    'confirm' => 'Bu eğitimi silmek istediğinizden emin misiniz?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                        <?php } else { ?>
                            <span class="text-muted">-</span>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
<?php } ?>

<?php if ($egitim) { ?>
    <?php
    $quizSorulari = $egitim->quiz_json ? json_decode($egitim->quiz_json, true) : [];
    $quizHazir = is_array($quizSorulari) && count($quizSorulari) > 0;
    ?>
    <h3><?= Html::encode($egitim->baslik) ?></h3>
    <?php if ($egitim->aciklama) { ?>
        <p><?= Html::encode($egitim->aciklama) ?></p>
    <?php } ?>

    <?php if ($a) { ?>
        <div class="alert alert-info">
            Bu eğitim için quiz kaydınız daha önce alınmış.
        </div>
    <?php } elseif (!$quizHazir) { ?>
        <div class="alert alert-warning">
            Bu eğitim için quiz soruları henüz tanımlanmamış.
        </div>
    <?php } ?>

<div class="panel panel-default" style="max-width:420px;">
  <div class="panel-heading">
    <strong><?= Html::encode($egitim->baslik) ?></strong>
  </div>
  <div class="panel-body">
    <p><?= Html::encode($egitim->aciklama ?: 'Eğitim videosunu izlemek için videoyu açın.') ?></p>
    <?= Html::button('<span class="glyphicon glyphicon-play"></span> Videoyu Aç', [
        'class' => 'btn btn-primary video-ac',
        'data-video' => $egitim->videoUrl,
        'data-title' => $egitim->baslik,
        'data-complete-url' => Url::to(['/bgysfarkindalikquiz/egitimtamamlandi', 'id' => $egitim->id]),
        'data-quiz-url' => Url::to(['/bgysfarkindalikquiz/create', 'egitim_id' => $egitim->id]),
        'data-quiz-ready' => $quizHazir ? 1 : 0,
        'data-quiz-solved' => $a ? 1 : 0,
    ]) ?>
  </div>
</div>

<?php if ($quizHazir) { ?>
    <p style="margin-top:15px;">
        <?= Html::button($a ? 'Quiz Sonucunu Görüntüle' : ($egitimiIzledi ? 'Quiz’e Başla' : 'Video tamamlanınca quiz aktifleşecek'), [
            'id' => 'quizButton',
            'class' => 'btn btn-success',
            'disabled' => !$a && !$egitimiIzledi,
            'data-url' => Url::to(['/bgysfarkindalikquiz/create', 'egitim_id' => $egitim->id]),
        ]) ?>
    </p>
<?php } ?>
<?php } ?>

<?php
$this->registerJs(<<<JS
function bgysQuizAc() {
    var url = $('#quizButton').length ? $('#quizButton').data('url') : null;
    if (arguments.length && arguments[0]) {
        url = arguments[0];
    }
    if (!url) {
        return;
    }
    $('#modalContent').html('<div class="text-center" style="padding:20px;">Quiz yükleniyor...</div>');
    $('#modal').modal('show')
        .find('#modalContent')
        .load(url);
}

function bgysQuizAktifEtVeAc() {
    if (window.bgysAktifQuizOpenedUrl && window.bgysAktifQuizOpenedUrl === window.bgysAktifQuizUrl) {
        return;
    }
    window.bgysAktifQuizOpenedUrl = window.bgysAktifQuizUrl;

    var postData = {};
    if (typeof yii !== 'undefined') {
        postData[yii.getCsrfParam()] = yii.getCsrfToken();
    }
    $.post(window.bgysEgitimTamamlamaUrl || '', postData).always(function() {
        if ($('#quizButton').length) {
            $('#quizButton')
                .data('quiz-opened', true)
                .prop('disabled', false)
                .text('Quiz’e Başla');
        }
        $('.quiz-basla').filter(function() {
            return $(this).data('url') === window.bgysAktifQuizUrl;
        }).prop('disabled', false).text('Quiz’e Başla');
    });
}

$('#quizButton').on('click', function() {
    if (!$(this).prop('disabled')) {
        bgysQuizAc();
    }
});

$(document).off('click.quizBasla', '.quiz-basla').on('click.quizBasla', '.quiz-basla', function() {
    if (!$(this).prop('disabled')) {
        bgysQuizAc($(this).data('url'));
    }
});

var video = document.getElementById('my-video');
if (video) {
    video.addEventListener('ended', bgysQuizAktifEtVeAc);
    video.addEventListener('timeupdate', function() {
        if (video.duration && video.currentTime >= video.duration - 1) {
            bgysQuizAktifEtVeAc();
        }
    });
}

JS
);
$this->registerJs(<<<JS
$(document).off('click.egitimModal', '.egitim-modal').on('click.egitimModal', '.egitim-modal', function(e) {
    e.preventDefault();
    $('#modalContent').html('<div class="text-center" style="padding:20px;">Yükleniyor...</div>');
    $('#modal').modal('show')
        .find('#modalContent')
        .load($(this).attr('value'));
});

$(document).off('click.quizYenidenBasla', '.quiz-yeniden-basla').on('click.quizYenidenBasla', '.quiz-yeniden-basla', function(e) {
    e.preventDefault();
    $('#modalContent').html('<div class="text-center" style="padding:20px;">Quiz yükleniyor...</div>');
    $('#modalContent').load($(this).attr('value'));
});

$(document).off('click.videoAc', '.video-ac').on('click.videoAc', '.video-ac', function() {
    var videoUrl = $(this).data('video');
    var title = $(this).data('title');
    $('#videoModalTitle').text(title);
    $('#videoSource').attr('src', videoUrl);
    window.bgysEgitimTamamlamaUrl = $(this).data('complete-url');
    window.bgysAktifQuizUrl = $(this).data('quiz-url');
    window.bgysAktifQuizHazir = parseInt($(this).data('quiz-ready'), 10) === 1;
    window.bgysAktifQuizCozuldu = parseInt($(this).data('quiz-solved'), 10) === 1;

    var video = document.getElementById('my-video');
    if (video) {
        video.controls = true;
        video.load();
        try {
            video.currentTime = 0;
        } catch (e) {}
    }
    $('#videoModal').modal('show');
});

$('#videoModal').on('shown.bs.modal', function() {
    var video = document.getElementById('my-video');
    if (video) {
        var playPromise = video.play();
        if (playPromise && typeof playPromise.catch === 'function') {
            playPromise.catch(function() {});
        }
    }
});

$('#videoModal').on('hidden.bs.modal', function() {
    var video = document.getElementById('my-video');
    if (video) {
        video.pause();
    }
});

$(document).off('beforeSubmit.egitimForm', '#modalContent form').on('beforeSubmit.egitimForm', '#modalContent form', function(e) {
    var form = this;
    var action = $(form).attr('action') || '';
    if (action.indexOf('egitimekle') === -1 && action.indexOf('egitimguncelle') === -1) {
        return true;
    }
    var formData = new FormData(form);
    $.ajax({
        url: $(form).attr('action'),
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (typeof response === 'string' && response.indexOf('window.location.reload') !== -1) {
                window.location.reload();
                return;
            }
            $('#modalContent').html(response);
        }
    });
    return false;
});
JS
);
?>
