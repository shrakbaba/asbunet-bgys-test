<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Mailkapat */

$this->title = 'Güncelle: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Kapatılacak Mailler', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Güncelle';
?>
<div class="mailkapat-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
