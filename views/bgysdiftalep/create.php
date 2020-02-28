<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Bgysdiftalep */

$this->params['breadcrumbs'][] = ['label' => 'Dif Talep', 'url' => ['index']];
?>
<div class="bgysdiftalep-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
