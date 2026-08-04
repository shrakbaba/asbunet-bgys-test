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

    <div class="alert alert-info">
        Bu kayıt, kurumdan ayrılan kişiye ait e-posta hesabının kapatılıp kapatılmadığını takip etmek için kullanılır.
        Hatırlatma mailleri Bilgi İşlem mail grubuna gönderilir.
    </div>

    <?= $form->field($model, 'mailhesabi')->widget(yii\widgets\MaskedInput::class, [
        'clientOptions' => [ 'alias' =>  'email'  ],
        'options' => [
            'class' => 'form-control',
            'placeholder' => 'Kapatılacak e-posta hesabı',
        ],
    ])->hint('Kapatılması takip edilecek kullanıcı e-posta adresi.') ?>

    <?= $form->field($model, 'ayrilistarihi')->widget(
        DatePicker::className(), [
        'clientOptions' => [
            'autoclose' => true,
            'format' => 'dd/mm/yyyy',
            'minViewMode'=> "days"
        ],
        'language'=>'tr',
        'options'=>[
            'class'=>'form-control',
            'placeholder'=>"Gün/Ay/Yıl"
        ],
    ])->hint('Sistem bu tarihten sonraki 5., 10. ve 15. günlerde hatırlatma üretir.') ?>

    <?= $form->field($model, 'kapatildi')->dropDownList(array(0=>'Hayır',1=>'Evet'), ['prompt' => 'Hesap kapatıldı mı?'])  ?>

    <div class="form-group">
        <?= Html::submitButton('Kaydet', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
