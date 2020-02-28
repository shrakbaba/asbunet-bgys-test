<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Bgyslogs */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Bgyslogs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="bgyslogs-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'controller',
            'action',
            'userid',
            'date',
            'not',
            'islem',
        ],
    ]) ?>

</div>
