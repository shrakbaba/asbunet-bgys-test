<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Authitem */

$this->title = 'Rbac Nesnesi Güncelle: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Rbac Nesneleri', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->name]];
$this->params['breadcrumbs'][] = 'Güncelle';
?>
<div class="authitem-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
