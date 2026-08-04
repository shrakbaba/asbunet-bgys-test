<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $model app\models\Bgysfarkindalikegitim */
?>

<div class="bgysfarkindalikegitim-view">
    <h1>Eğitim Bilgisi</h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'baslik',
            'aciklama:ntext',
            [
                'label' => 'Video',
                'format' => 'raw',
                'value' => Html::button('<span class="glyphicon glyphicon-play"></span> Videoyu Aç', [
                    'class' => 'btn btn-primary btn-xs video-ac',
                    'data-video' => $model->videoUrl,
                    'data-title' => $model->baslik,
                    'data-complete-url' => Url::to(['/bgysfarkindalikquiz/egitimtamamlandi', 'id' => $model->id]),
                    'data-quiz-url' => Url::to(['/bgysfarkindalikquiz/create', 'egitim_id' => $model->id]),
                    'data-quiz-ready' => $model->quiz_json ? 1 : 0,
                    'data-quiz-solved' => 0,
                ]),
            ],
            [
                'label' => 'Quiz Dokümanı',
                'format' => 'raw',
                'value' => $model->quiz_json
                    ? Html::a('Quiz Soruları', ['/bgysfarkindalikquiz/quizdokuman', 'id' => $model->id], ['target' => '_blank', 'style' => 'color:#337ab7;text-decoration:underline;'])
                    : ($model->quiz_dosya
                        ? Html::a(Html::encode($model->quiz_orijinal_ad ?: $model->quiz_dosya), $model->quizUrl, ['target' => '_blank', 'style' => 'color:#337ab7;text-decoration:underline;'])
                        : '(Veri Yok)'),
            ],
            [
                'label' => 'Tanımlı Quiz Sorusu',
                'value' => $model->quiz_json ? count(json_decode($model->quiz_json, true) ?: []) : 0,
            ],
            [
                'label' => 'Durum',
                'value' => $model->aktif ? 'Aktif' : 'Pasif',
            ],
            [
                'attribute' => 'created_at',
                'format' => ['datetime', 'php:d/m/Y H:i'],
            ],
        ],
    ]) ?>

    <div class="form-group">
        <?= Html::button('Tamam', ['class' => 'btn btn-default', 'data-dismiss' => 'modal']) ?>
    </div>
</div>
