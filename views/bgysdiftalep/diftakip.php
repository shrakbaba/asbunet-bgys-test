<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePickerAsset;
use dosamigos\datepicker\DatePickerLanguageAsset;
use yii\helpers\ArrayHelper;
use app\models\Userdb;
use app\models\Userbilgi;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Bgysdiftalep */

$this->title = 'Dif Ekle';
$this->params['breadcrumbs'][] = ['label' => 'Dif Talep', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
DatePickerAsset::register($this);
DatePickerLanguageAsset::register($this)->js[] = 'bootstrap-datepicker.tr.min.js';
?>
<div class="bgysdiftakip-create">
 <?php $form = ActiveForm::begin(); ?>
    <h4>Talep Form No:<?= $diftalep->dif_no ?></h4>
    <div style="text-align: center"> 
    	<h3>DİF TALEP BÖLÜMÜ  </h3>
    </div>
    <div style="text-align: center"> 
    	<div style="float:left">Talep Eden Kişi/Birim: <b><?= $diftalep->talep_eden ?></b> </div>
    	<div style="float:right">Tarih:<b><?= date('d/m/Y',strtotime($diftalep->talep_tarihi)) ?></b> </div>
    </div>
    <div style="clear:left;text-align: center"><hr> <h4>Dif Konusu</h4> </div>	
    <div > <?= $diftalep->dif_konusu ?> </div>	
   	<hr>
    <div style="text-align: center"> 
    	<h3>DİF TAKİP BÖLÜMÜ  </h3>
    </div>
    <div class="col-lg-12">
        <?= $form->field($model, 'sorumlukisi')->widget(Select2::classname(), [
            'data' => Yii::$app->params['giristipi']==1 ?
                ArrayHelper::map(\Edvlerblog\Adldap2\model\UserDbLdap::find()->all(), function($user) {
                    return @Userbilgi::findOne(['kisi_id'=>$user['id']])->ad.' '.@Userbilgi::findOne(['kisi_id'=>$user['id']])->soyad.' / '.$user['username'];
                }, function($user) {
                    return @Userbilgi::findOne(['kisi_id'=>$user['id']])->ad.' '.@Userbilgi::findOne(['kisi_id'=>$user['id']])->soyad.' / '.$user['username'];
                })
                : ArrayHelper::map(Userdb::find()->all(), function($user) {
                    return $user['ad'].' '.$user['soyad'].' / '.$user['username'];
                }, function($user) {
                    return $user['ad'].' '.$user['soyad'].' / '.$user['username'];
                }),
            'options' => ['placeholder' => 'Sorumlu kişi seçiniz'],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ]) ?>
    </div>
    <div class="col-lg-12">   
        <?= $form->field($model, 'kokneden')->textarea(['maxlength' => true,'rows' => 3]) ?>
    </div> 
    <div class="col-lg-12"> 
        <?= $form->field($model, 'uygulananfaaliyet')->textarea(['maxlength' => true,'rows' => 3]) ?>
    </div>

    <div class="col-lg-4">
        <?= $form->field($model, 'tamamlanmatarihi')->textInput([
            'class' => 'form-control dif-tamamlanma-tarihi',
            'placeholder'=>"Gün/Ay/Yıl",
            'autocomplete' => 'off',
        ]) ?>
    </div> 
    
    <div class="col-lg-8"> 
        <?= $form->field($model, 'sonuc')->textarea(['maxlength' => true,'rows' => 3]) ?>
    </div>
    <div class="form-group col-lg-6">
        <?= Html::submitButton('Kaydet', ['class' => 'btn btn-success btn-lg']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <div class="form-group col-lg-6">
    <?php 
        if (!$model->isNewRecord && Yii::$app->user->can('BGYS_Yonetim_Temsilcisi') ) {
             echo Html::a('Dif Takip Onayla', ['diftakiponay', 'i'=>$model->id], ['class'=>'btn btn-info btn-lg', 'data-method'=>'post']);
        }
    ?>
    </div>


</div>

<?php
$this->registerJs("
$('.datepicker-dropdown').remove();

$('.dif-tamamlanma-tarihi').each(function () {
    var input = $(this);
    if (input.data('datepicker')) {
        input.datepicker('destroy');
    }
	    input.datepicker({
	        autoclose: true,
	        format: 'dd/mm/yyyy',
	        language: 'tr',
	        minViewMode: 'days',
	        orientation: 'top auto',
	        container: 'body',
	        todayHighlight: true
	    });
	    input.on('show', function () {
	        setTimeout(function () {
	            var pickers = $('.datepicker-dropdown:visible');
	            if (pickers.length > 1) {
	                pickers.not(pickers.first()).remove();
	            }
	        }, 0);
	    });
	});

setTimeout(function () {
    $('.dif-tamamlanma-tarihi').datepicker('hide').blur();
    $('.datepicker-dropdown').hide();
}, 200);
");
?>
