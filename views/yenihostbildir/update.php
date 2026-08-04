<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Yenihostbildir */

$this->title = 'Yeni VM Hatırlatması Güncelle';
$this->params['breadcrumbs'][] = ['label' => 'Yenihostbildirs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Güncelle';
?>
<div class="yenihostbildir-update">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
