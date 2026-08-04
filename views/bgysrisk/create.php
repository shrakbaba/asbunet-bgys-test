<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Bgysrisk */

$this->title = 'Risk Ekle';
$this->params['breadcrumbs'][] = ['label' => 'Riskler', 'url' => ['index']];
?>
<div class="bgysrisk-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
