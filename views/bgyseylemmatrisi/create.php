<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Bgyseylemmatrisi */

$this->params['breadcrumbs'][] = ['label' => 'Eylem Matrisi', 'url' => ['index']];
?>
<div class="bgyseylemmatrisi-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
