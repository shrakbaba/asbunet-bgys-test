<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\imdat;

/* @var $this yii\web\View */
/* @var $model app\models\Authitem */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Rbac Nesneleri', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="authitem-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Güncelle', ['update', 'id' => $model->name], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Sil', ['delete', 'id' => $model->name], [
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
            'name',
           // 'type',
            'description:ntext',
            [
                'attribute'=>'type',
                'format'=>'raw',
                'filter'=>array(1=>'Rol',2=>'İzin'),
                'value'=>function ($data)
                    {
                        return imdat::item_tipi($data->type);
                    }
            ],
            'rule_name',
            'data',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
