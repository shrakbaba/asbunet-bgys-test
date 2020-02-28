<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Authitemchild */

$this->title = $model->parent;
$this->params['breadcrumbs'][] = ['label' => 'Rol Grupları', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="authitemchild-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Güncelle', ['update', 'parent' => $model->parent, 'child' => $model->child], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Sil', ['delete', 'parent' => $model->parent, 'child' => $model->child], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Bu veriyi silmek istediğinizden emin misiniz?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'parent',
            'child',
        ],
    ]) ?>

</div>
