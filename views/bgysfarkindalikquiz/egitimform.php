<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Bgysfarkindalikegitim */
?>

<div class="bgysfarkindalikegitim-form">
    <h1><?= $model->isNewRecord ? 'Eğitim Videosu / Quiz Ekle' : 'Eğitimi Güncelle' ?></h1>

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'],
    ]); ?>

    <?= $form->field($model, 'baslik')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'aciklama')->textarea(['rows' => 3]) ?>
    <?= $form->field($model, 'videoFile')->fileInput(['accept' => 'video/mp4']) ?>

    <?php if (!$model->isNewRecord && $model->video_dosya) { ?>
        <p>
            Mevcut video:
            <?= Html::a(Html::encode($model->video_dosya), $model->videoUrl, ['target' => '_blank']) ?>
        </p>
    <?php } ?>

    <?= $form->field($model, 'quizFile')->fileInput(['accept' => '.pdf'])->hint('Quiz dokümanı PDF formatında yüklenir ve arşiv/referans dokümanı olarak tutulur. Yeni PDF seçip kaydettiğinizde mevcut quiz dokümanı güncellenir. Kullanıcılara gösterilen quiz aşağıdaki soru alanlarından verilir.') ?>

    <?php if (!$model->isNewRecord && $model->quiz_dosya) { ?>
        <p>
            Mevcut quiz dokümanı:
            <?= Html::a(Html::encode($model->quiz_orijinal_ad ?: $model->quiz_dosya), $model->quizUrl, ['target' => '_blank', 'style' => 'color:#337ab7;text-decoration:underline;']) ?>
        </p>
    <?php } ?>

    <div class="form-group <?= $model->hasErrors('quiz_json') ? 'has-error' : '' ?>">
        <label>Quiz Soruları</label>
        <div class="help-block">
            Kullanıcıya gösterilecek quiz için soruları ve doğru cevapları buradan tanımlayın. Soru silerseniz değişiklik kaydet dediğinizde veritabanına aktarılır.
        </div>
        <div class="alert alert-info">
            <strong>PDF içinde önerilen format:</strong><br>
            1. Soru metni?<br>
            A) Birinci seçenek<br>
            B) İkinci seçenek<br>
            C) Üçüncü seçenek<br>
            D) Dördüncü seçenek<br>
            Doğru C<br><br>
            PDF arşiv için tutulur; sistemin quizde sorduğu sorular aşağıdaki alanlardan alınır.
        </div>
        <?= Html::error($model, 'quiz_json', ['class' => 'help-block']) ?>

        <div id="quizSorulari">
            <?php
            $quizSorulari = [];
            if ($model->quiz_json) {
                $quizSorulari = json_decode($model->quiz_json, true) ?: [];
            }
            if (empty($quizSorulari)) {
                $quizSorulari = [[
                    'soru' => '',
                    'secenekler' => ['', '', '', ''],
                    'dogru' => 0,
                ]];
            }
            ?>
            <?php foreach ($quizSorulari as $index => $quizSoru) { ?>
                <div class="panel panel-default quiz-soru" data-index="<?= $index ?>">
                    <div class="panel-heading">
                        <strong>Soru <span class="quiz-soru-no"><?= $index + 1 ?></span></strong>
                        <button type="button" class="btn btn-danger btn-xs pull-right quiz-soru-sil">
                            <span class="glyphicon glyphicon-remove"></span>
                        </button>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label>Soru Metni</label>
                            <textarea name="Quiz[<?= $index ?>][soru]" class="form-control" rows="2"><?= Html::encode($quizSoru['soru'] ?? '') ?></textarea>
                        </div>
                        <?php for ($secenekIndex = 0; $secenekIndex < 4; $secenekIndex++) { ?>
                            <div class="input-group" style="margin-bottom:6px;">
                                <span class="input-group-addon">
                                    <input type="radio" name="Quiz[<?= $index ?>][dogru]" value="<?= $secenekIndex ?>" <?= (int)($quizSoru['dogru'] ?? 0) === $secenekIndex ? 'checked' : '' ?>>
                                </span>
                                <input type="text" name="Quiz[<?= $index ?>][secenekler][<?= $secenekIndex ?>]" class="form-control" placeholder="<?= chr(65 + $secenekIndex) ?> şıkkı" value="<?= Html::encode($quizSoru['secenekler'][$secenekIndex] ?? '') ?>">
                            </div>
                        <?php } ?>
                        <small>Doğru cevap olan şıkkın başındaki yuvarlağı seçin.</small>
                    </div>
                </div>
            <?php } ?>
        </div>

        <button type="button" id="quizSoruEkle" class="btn btn-info">
            <span class="glyphicon glyphicon-plus"></span> Soru Ekle
        </button>
    </div>

    <?= $form->field($model, 'aktif')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Kaydet', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<?php
$this->registerJs(<<<JS
function quizSoruNumaralariniGuncelle() {
    $('#quizSorulari .quiz-soru').each(function(i) {
        $(this).attr('data-index', i);
        $(this).find('.quiz-soru-no').text(i + 1);
        $(this).find('textarea').attr('name', 'Quiz[' + i + '][soru]');
        $(this).find('input[type=radio]').each(function(j) {
            $(this).attr('name', 'Quiz[' + i + '][dogru]').val(j);
        });
        $(this).find('input[type=text]').each(function(j) {
            $(this).attr('name', 'Quiz[' + i + '][secenekler][' + j + ']');
        });
    });
}

$(document).off('click.quizSoruEkle', '#quizSoruEkle').on('click.quizSoruEkle', '#quizSoruEkle', function() {
    var index = $('#quizSorulari .quiz-soru').length;
    var html = ''
        + '<div class="panel panel-default quiz-soru" data-index="' + index + '">'
        + '<div class="panel-heading"><strong>Soru <span class="quiz-soru-no">' + (index + 1) + '</span></strong>'
        + '<button type="button" class="btn btn-danger btn-xs pull-right quiz-soru-sil"><span class="glyphicon glyphicon-remove"></span></button><div class="clearfix"></div></div>'
        + '<div class="panel-body">'
        + '<div class="form-group"><label>Soru Metni</label><textarea name="Quiz[' + index + '][soru]" class="form-control" rows="2"></textarea></div>';
    for (var i = 0; i < 4; i++) {
        html += '<div class="input-group" style="margin-bottom:6px;">'
            + '<span class="input-group-addon"><input type="radio" name="Quiz[' + index + '][dogru]" value="' + i + '"' + (i === 0 ? ' checked' : '') + '></span>'
            + '<input type="text" name="Quiz[' + index + '][secenekler][' + i + ']" class="form-control" placeholder="' + String.fromCharCode(65 + i) + ' şıkkı">'
            + '</div>';
    }
    html += '<small>Doğru cevap olan şıkkın başındaki yuvarlağı seçin.</small></div></div>';
    $('#quizSorulari').append(html);
});

$(document).off('click.quizSoruSil', '.quiz-soru-sil').on('click.quizSoruSil', '.quiz-soru-sil', function() {
    if ($('#quizSorulari .quiz-soru').length === 1) {
        $(this).closest('.quiz-soru').find('textarea,input[type=text]').val('');
        $(this).closest('.quiz-soru').find('input[type=radio]').first().prop('checked', true);
        return;
    }
    $(this).closest('.quiz-soru').remove();
    quizSoruNumaralariniGuncelle();
});
JS
);
?>
