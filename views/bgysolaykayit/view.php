<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Olaykayit */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Olay Kayıt', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="olaykayit-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php /* Html::a('Güncelle', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Sil', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Bu veriyi silmek istediğinizden emin misiniz?',
                'method' => 'post',
            ],
        ]) */ ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
           // 'id',
           //'userid',
            [
                'value'=>$model->user->username,
                'label'=>'Kayıt Eden Kullanıcı',
            ],
            //'user.username',
            'konu',
            [
                'attribute' => 'olaytarihi',
                'format' => ['date', 'php:d/m/Y']
            ], 
            [
                'attribute' => 'mudahaletarihi',
                'format' => ['date', 'php:d/m/Y']
            ], 
            'mudahaleeden',
            'yapilanmudahale',
            'sonuc',
            'onlem'
        ],
    ]) ?>
<?php if ($model->belge || $model->belgeler) { ?>
    <div style="margin-top:15px">
        <label>Eklenen Belgeler</label>
        <ul style="padding-left:18px">
            <?php if ($model->belge) { ?>
                <li>
                    <?= Html::a($model->belge, ['pdfgoster', 'id' => $model->id], [
                        'target' => '_blank',
                        'style' => 'color:#337ab7;text-decoration:underline;',
                        'onclick' => "window.open(this.href, '_blank'); return false;",
                    ]) ?>
                </li>
            <?php } ?>
            <?php foreach ($model->belgeler as $belge) { ?>
                <li>
                    <?= Html::a(Html::encode($belge->orijinal_ad), ['belgegoster', 'id' => $belge->id], [
                        'target' => '_blank',
                        'style' => 'color:#337ab7;text-decoration:underline;',
                        'onclick' => "window.open(this.href, '_blank'); return false;",
                    ]) ?>
                </li>
            <?php } ?>
        </ul>
    </div>
<?php } ?>
</div>
