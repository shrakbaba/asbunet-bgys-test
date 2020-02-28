<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Authitemchild */

$this->params['breadcrumbs'][] = ['label' => 'Rol Grupları', 'url' => ['index']];
?>
<div class="authitemchild-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
