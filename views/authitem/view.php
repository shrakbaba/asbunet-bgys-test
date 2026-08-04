<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Authitem */

$this->title = 'Rol Bilgisi';
$this->params['breadcrumbs'][] = ['label' => 'Roller', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="authitem-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'description:ntext',
        ],
    ]) ?>

    <div class="text-right">
        <?= Html::button('Tamam', ['class' => 'btn btn-default', 'data-dismiss' => 'modal']) ?>
    </div>

</div>
