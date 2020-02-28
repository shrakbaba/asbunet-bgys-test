<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Bgysilgigrup */

/*$this->title = 'Create Bgysilgigrup';
$this->params['breadcrumbs'][] = ['label' => 'Bgysilgigrups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;*/
?>
<div class="bgysilgigrup-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
