<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Yenihostbildir */

$this->title = 'Yeni VM Hatırlatması Ekle';
$this->params['breadcrumbs'][] = ['label' => 'Yeni VMler', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="yenihostbildir-create">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
