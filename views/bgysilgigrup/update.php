<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Bgysilgigrup */

/*$this->title = 'Update Bgysilgigrup: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Bgysilgigrups', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';*/
?>
<div class="bgysilgigrup-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
