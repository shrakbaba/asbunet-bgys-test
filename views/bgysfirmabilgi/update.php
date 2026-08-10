<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Firmabilgi */

?>
<div class="firmabilgi-update">

    <p><?= Html::a('Tedarikçi Listesine Dön', ['index'], ['class' => 'btn btn-default']) ?></p>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
