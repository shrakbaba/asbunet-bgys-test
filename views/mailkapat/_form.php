<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use yii\widgets\MaskedInput;
use dosamigos\datepicker\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Mailkapat */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mailkapat-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="col-md-4">
                        <?= $form->field($model, 'mailhesabi')->widget(yii\widgets\MaskedInput::class, [
                            'clientOptions' => [ 'alias' =>  'email'  ], 
                        ]) ?>
                    </div> 

    <div class="col-md-4">
                        <?= $form->field($model, 'ayrilistarihi')->widget(
                            DatePicker::className(), [
                            // inline too, not bad
                             'inline' => true, 
                             // modify template for custom rendering
                            'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
                            'clientOptions' => [
                                'autoclose' => true,
                                'format' => 'dd/mm/yyyy', 
                                'minViewMode'=> "days"
                            ],
                            'language'=>'tr'
                        ]); ?>
                    </div>
	<div class="col-md-4">
        <?= $form->field($model, 'kapatildi')->dropDownList(array(0=>'Hayır',1=>'Evet'), ['prompt' => 'Hesap Kapatildi mi?'])  ?>
	</div>
    <div class="form-group col-md-12">
        <?= Html::submitButton('Kaydet', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
