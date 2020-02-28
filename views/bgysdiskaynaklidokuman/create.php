<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Bgysdiskaynaklidokuman */
/*$this->params['breadcrumbs'][] = ['label' => 'Dış Kaynaklı Dokümanlar', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;*/
?>
<div class="bgysdiskaynaklidokuman-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
