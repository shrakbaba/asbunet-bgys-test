<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Olaykayit */

$this->params['breadcrumbs'][] = ['label' => 'Olay Kayıtları', 'url' => ['index']];
?>
<div class="olaykayit-create">

    <?php if (!Yii::$app->request->isAjax) { ?>
        <h1><?= Html::encode($this->title) ?></h1>
    <?php } ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
