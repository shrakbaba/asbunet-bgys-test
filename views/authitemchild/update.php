<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Authitemchild */

$this->title = 'Rol İlişkisi Güncelle';
$this->params['breadcrumbs'][] = ['label' => 'Rol İlişkileri', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->parent, 'url' => ['view', 'parent' => $model->parent, 'child' => $model->child]];
$this->params['breadcrumbs'][] = 'Güncelle';
?>
<div class="authitemchild-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
