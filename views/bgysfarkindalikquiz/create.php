<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Bgysfarkindalikquiz */

//$this->params['breadcrumbs'][] = ['label' => 'Bgysfarkindalikquizzes', 'url' => ['index']];
$this->title = ($model->egitim ? $model->egitim->baslik : 'Farkındalık Eğitimi') . ' Quiz';
?>
<div class="bgysfarkindalikquiz-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
