<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Authassignment */

$this->title = $model->item_name;
$this->params['breadcrumbs'][] = ['label' => 'Rol Atamaları', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="authassignment-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Güncelle', ['update', 'item_name' => $model->item_name, 'user_id' => $model->user_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Sil', ['delete', 'item_name' => $model->item_name, 'user_id' => $model->user_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Bu veriyi silmek istediğinizden emin misiniz?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'item_name',
            [
                'value'=>$model->user->username,
                'label'=>'Atanan Kullanıcı',
            ],
            //'user_id',
            'created_at',
        ],
    ]) ?>

</div>
