<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Bgysrisk;

/* @var $this yii\web\View */
/* @var $model app\models\Bgysrisk */
/* @var $form yii\widgets\ActiveForm */

$model2 = Bgysrisk::findOne(intval($_GET['id']));
$this->title = $model2->risk;
$this->params['breadcrumbs'][] = ['label' => 'Riskler', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="bgysrisk-form">
    <h3><?= Html::encode($this->title) ?></h3>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'aciklama')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Kaydet', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
