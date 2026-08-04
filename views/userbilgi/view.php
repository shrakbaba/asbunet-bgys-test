<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Userbilgi */

$this->title = 'Kullanıcı Bilgisi';
$this->params['breadcrumbs'][] = ['label' => 'Kullanıcı Bilgileri', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$tamVeriGorebilir = Yii::$app->user->can('BGYS_Super_Admin') || Yii::$app->user->can('BGYS_Yonetim_Temsilcisi');
$kendiKaydi = !Yii::$app->user->isGuest && (int)$model->kisi_id === (int)Yii::$app->user->identity->id;
$attributes = [
    'id',
    [
        'label' => 'Kullanıcı Adı',
        'value' => $model->kisi ? $model->kisi->username : null,
    ],
    'ad',
    'soyad',
    'email:email',
];
if ($tamVeriGorebilir || $kendiKaydi) {
    $attributes[] = 'tc';
    $attributes[] = 'telefon';
    $attributes[] = 'adres';
    $attributes[] = 'dogumyili';
    $attributes[] = 'kisi_id';
    $attributes[] = 'tarihi';
}
?>
<div class="userbilgi-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => $attributes,
    ]) ?>

    <div class="text-right">
        <?= Html::button('Tamam', ['class' => 'btn btn-default', 'data-dismiss' => 'modal']) ?>
    </div>

</div>
