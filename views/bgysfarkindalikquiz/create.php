<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Bgysfarkindalikquiz */

//$this->params['breadcrumbs'][] = ['label' => 'Bgysfarkindalikquizzes', 'url' => ['index']];
?>
<div class="bgysfarkindalikquiz-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
