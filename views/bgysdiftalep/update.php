<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Bgysdiftalep */

$this->title = 'Dif Talep Güncelle';
$this->params['breadcrumbs'][] = ['label' => 'Dif Talep', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Güncelle';
?>
<div class="bgysdiftalep-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
