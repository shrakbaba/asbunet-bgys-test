<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Authrule */

$this->params['breadcrumbs'][] = ['label' => 'Authrules', 'url' => ['index']];
?>
<div class="authrule-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
