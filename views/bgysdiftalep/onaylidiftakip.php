<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Bgysdiftalep */

$this->title = 'Dif Ekle';
$this->params['breadcrumbs'][] = ['label' => 'Dif Talep', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
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
        <?= $form->field($model, 'sorumlukisi')->textInput(['maxlength' => true,'readonly'=>true]) ?>
    </div>
    <div class="col-lg-12">   
        <?= $form->field($model, 'kokneden')->textarea(['maxlength' => true,'rows' => 3,'readonly'=>true]) ?>
    </div> 
    <div class="col-lg-12"> 
        <?= $form->field($model, 'uygulananfaaliyet')->textarea(['maxlength' => true,'rows' => 3,'readonly'=>true]) ?>
    </div>

    <div class="col-lg-4">
        <?= $form->field($model, 'tamamlanmatarihi')->widget(
            DatePicker::className(), [
            'inline' => false, 
            //'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
            'clientOptions' => [
               // 'autoclose' => true,
               // 'format' => 'dd/mm/yyyy', 
               // 'minViewMode'=> "days",
            ],
            'language'=>'tr',            
            'options'=>[  
                'disabled' => true,
                'placeholder'=>"Gün/Ay/Yıl",
                'readonly'=>'true',
            ],
            ]); 
        ?>
    </div> 
    
    <div class="col-lg-8"> 
        <?= $form->field($model, 'sonuc')->textarea(['maxlength' => true,'rows' => 3,'readonly'=>true]) ?>
    </div> 

    <?php ActiveForm::end(); ?>
    <div class="form-group col-lg-6">
    <?php 
        if (Yii::$app->user->can('BGYS_Yonetim_Temsilcisi') ) {
             echo Html::a('Dif Takip Onayını Kaldır', ['diftakiponayiptal', 'i'=>$model->id] ,['class'=>'btn btn-info btn-lg']);
        }
    ?>
    </div>


</div>
