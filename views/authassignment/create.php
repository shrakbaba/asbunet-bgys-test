<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Authassignment */

$this->params['breadcrumbs'][] = ['label' => 'Rol Atamaları', 'url' => ['index']];
?>
<div class="authassignment-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
