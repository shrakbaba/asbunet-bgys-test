<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Bgysbilgisinifi */

$this->params['breadcrumbs'][] = ['label' => 'Bilgi Sınıfları', 'url' => ['index']];
?>
<div class="bgysbilgisinifi-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
