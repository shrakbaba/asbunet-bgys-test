<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use app\models\Userbilgi;
use yii\helpers\ArrayHelper;
use dosamigos\datepicker\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Bgysyedeklemelistesi */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bgysyedeklemelistesi-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'yedekalinacak')->textInput(['maxlength' => true]) ?>


    <?= $form->field($model, 'sorumlu')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(\Edvlerblog\Adldap2\model\UserDbLdap::find()->all(),'id',function($model) { 
                return @Userbilgi::findOne(['kisi_id'=>$model['id']])->ad.' '.@Userbilgi::findOne(['kisi_id'=>$model['id']])->soyad.' / '.$model['username'];
            }),
        'options' => ['placeholder' => 'Sorumlu'],
        'pluginOptions' => [
            'allowClear' => true
        ],
        ]); 
    ?>  

    <?= $form->field($model, 'yedeklemesekli')->dropDownList((array(1 =>"Full-Incremental", 2 =>"Differantial", 3 =>"Full")),['prompt' => 'Yedekleme Şekli'])     ?>

    <?= $form->field($model, 'yedekleme_yontemi')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'periyodu')->dropDownList((array(1 =>"2 Saatlik", 2 =>"Günlük", 3 =>"Haftalık", 4 =>"Aylık", 5 =>"Tek yedek")),['prompt' => 'Yedekleme Periyodu'])     ?>

    <?= $form->field($model, 'yedeklemeyeri')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'yedeklemezamani')->dropDownList((array(1 =>"08:00-17:00",2 =>"Haftaiçi günler",3 =>"Haftanın her günü",4 =>"Her ayın ilk günü",5 =>"Her çarşamba",6 =>"Tek yedek")),['prompt' => 'Yedekleme Zamanı']) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
