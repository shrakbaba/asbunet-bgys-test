<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Bgysfarkindalikquiz;

/* @var $this yii\web\View */
/* @var $model app\models\Bgysfarkindalikquiz */

$this->title = 'Farkındalık Eğitim Sonucu';
$this->params['breadcrumbs'][] = ['label' => 'Farkındalık Eğitim Sonuçları', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="bgysfarkindalikquiz-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php //echo Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php /*echo Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) */ ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            [
                'label' => 'Ad Soyad',
                'value' => $model->cevaplayanAdSoyad,
            ],
            [
                'label' => 'Eğitim',
                'value' => $model->egitim ? $model->egitim->baslik : '(Veri Yok)',
            ],
            'ip',
            //'cevaplamatarihi',
            [
                'attribute' => 'cevaplamatarihi',
                'format' => ['date', 'php:d/m/Y H:i:s']
            ],
            'puan'
            //'cevaplar',
        ],
    ]) ?>
<div  class="col-md-12">
    <div class="col-md-4 h4">Soru</div>
    <div class="col-md-8 h4">Kullanıcı Cevabı</div>
</div>

<?php
$cevaplar=json_decode($model->cevaplar, true);
foreach ((array)$cevaplar as $key => $value) {
    $soruIndex = ((int)str_replace('soru', '', $key)) - 1;
    $quizSoru = $model->quizSorulari[$soruIndex] ?? null;
    if ($quizSoru === null) {
        continue;
    }
    $soru = $quizSoru['soru'];
    $secenekler = $quizSoru['secenekler'];
    $cevapsikki = $secenekler[$value] ?? '(Cevap Yok)';
    ?>
    <div  class="col-md-12">
        <div class="col-md-4"><?= $soru ?></div>
        <div class="col-md-8"><?= ($cevapsikki) ?></div>
    </div> <?php

}

?> 

    <div class="clearfix"></div>
    <div class="form-group" style="margin-top:15px;">
        <?= Html::button('Tamam', ['class' => 'btn btn-default', 'data-dismiss' => 'modal']) ?>
    </div>

</div>
