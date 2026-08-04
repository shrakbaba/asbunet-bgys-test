<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Bgyslogs */

$this->title = 'Hareket Kaydı';
$this->params['breadcrumbs'][] = ['label' => 'Hareket Kayıtları', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="bgyslogs-view">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'controller',
            'action',
            [
                'attribute' => 'userid',
                'value' => @$model->logyapan->username,
            ],
            [
                'attribute'=>'date',
                'format' => ['date', 'php:d/m/Y H:i:s'],
            ],
            'not',
            'islem',
        ],
    ]) ?>

    <div class="form-group">
        <?= Html::button('Tamam', ['class' => 'btn btn-default', 'data-dismiss' => 'modal']) ?>
    </div>

</div>
