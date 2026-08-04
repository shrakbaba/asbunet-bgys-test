<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Authitemchild */

$this->title = 'Rol İlişkisi';
$this->params['breadcrumbs'][] = ['label' => 'Rol İlişkileri', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="authitemchild-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'parent',
            'child',
        ],
    ]) ?>

    <div class="form-group">
        <?= Html::button('Tamam', ['class' => 'btn btn-default', 'data-dismiss' => 'modal']) ?>
    </div>

</div>
