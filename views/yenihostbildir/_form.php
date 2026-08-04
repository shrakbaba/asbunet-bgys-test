<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Yenihostbildir */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="yenihostbildir-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="alert alert-info">
        Bu kayıt, yeni açılan VM için Zabbix, Kaspersky, IP Manage ve Palo Alto işlemlerinin tamamlanıp tamamlanmadığını takip eder.
        Eksik işlem varsa hatırlatma Bilgi İşlem mail grubuna gönderilir.
    </div>

    <?= $form->field($model, 'vm_name')->textInput([
        'maxlength' => true,
        'placeholder' => 'Yeni VM adı',
    ]) ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'zabbix')->dropDownList(array(0=>'Hayır',1=>'Evet'), ['prompt' => 'Zabbix ayarlandı mı?'])  ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'kaspersky')->dropDownList(array(0=>'Hayır',1=>'Evet'), ['prompt' => 'Kaspersky ayarlandı mı?'])  ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'ipmanage')->dropDownList(array(0=>'Hayır',1=>'Evet'), ['prompt' => 'IP Manage ayarlandı mı?'])  ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'paloalto')->dropDownList(array(0=>'Hayır',1=>'Evet'), ['prompt' => 'Palo Alto ayarlandı mı?'])  ?>
        </div>
    </div>

    <div class="form-group text-right">
        <?= Html::submitButton('Kaydet', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
