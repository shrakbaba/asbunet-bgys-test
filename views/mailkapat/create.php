<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Mailkapat */

$this->title = 'Mail Hatırlatma ';
$this->params['breadcrumbs'][] = ['label' => 'Kapatılacak Mailler', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mailkapat-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
