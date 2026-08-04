<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Authitem */

$this->title = 'Yeni Rol Ekle';
$this->params['breadcrumbs'][] = ['label' => 'Roller', 'url' => ['index']];
?>
<div class="authitem-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
