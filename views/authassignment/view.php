<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Authassignment */

$this->title = 'Rol Ataması';
$this->params['breadcrumbs'][] = ['label' => 'Rol Atamaları', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="authassignment-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'item_name',
            [
                'value'=>$model->user ? $model->user->username : 'Kullanıcı kaydı bulunamadı (ID: ' . $model->user_id . ')',
                'label'=>'Atanan Kullanıcı',
            ],
            //'user_id',
            'created_at',
        ],
    ]) ?>

    <div class="text-right">
        <?= Html::button('Tamam', ['class' => 'btn btn-default', 'data-dismiss' => 'modal']) ?>
    </div>

</div>
