<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use app\models\Userbilgi;
use yii\helpers\ArrayHelper;
use dosamigos\datepicker\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Bgysizlemeolcme */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bgysizlemeolcme-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php //echo $form->field($model, 'yil')->dropDownList((array(2020 =>"2020", 2019 =>"2019", 2018 =>"2018")),['prompt' => 'İlgili Yıl'])     ?>

    <?= $form->field($model, 'hedeforani')->textInput() ?>

    <?= $form->field($model, 'olcumsonucu')->textArea(['rows'=>3,'maxlength' => true]) ?>

    <?php /* echo $form->field($model, 'sorumlu')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(\Edvlerblog\Adldap2\model\UserDbLdap::find()->all(),'id',function($model) { 
                return @Userbilgi::findOne(['kisi_id'=>$model['id']])->ad.' '.@Userbilgi::findOne(['kisi_id'=>$model['id']])->soyad.' / '.$model['username'];
            }),
        'options' => ['placeholder' => 'Sorumlu'],
        'pluginOptions' => [
            'allowClear' => true
        ],
        ]); */
    ?>   

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
