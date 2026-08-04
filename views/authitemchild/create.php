<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Authitemchild */

$this->title = 'Rol Grubu Ekle';
$this->params['breadcrumbs'][] = ['label' => 'Rol İlişkileri', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="authitemchild-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
