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


<?php 
    $basla=2019;
    $bitis=date('Y')+2;
    $itemsfor=[];

    for ($i=$basla; $i<$bitis; $i++) { 
        $itemsfor[$i]=strval($i);
    }
?>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'yil')->dropDownList(($itemsfor),['prompt' => 'İlgili Yıl'])     ?>

    <?= $form->field($model, 'kontrol')->textArea(['rows'=>3,'maxlength' => true]) ?>

    <?= $form->field($model, 'metrikler')->textArea(['rows'=>3,'maxlength' => true]) ?>

    <?= $form->field($model, 'hedef_degeri')->textInput() ?>

    <?= $form->field($model, 'olcum_sikligi')->dropDownList((array(1 =>"Yılda 1", 2 =>"6 Ayda bir", 3 =>"3 Ayda 1", 4 =>"Ayda 1")),['prompt' => 'Ölçüm Sıklığı'])     ?>

    <?= $form->field($model, 'planlanan_tarihi')->widget(
        DatePicker::className(), [
        // inline too, not bad
         'inline' => true, 
         // modify template for custom rendering
        'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
        'clientOptions' => [
            'autoclose'=>true, 
            'minViewMode'=> "months",
            'format' => 'yyyy-mm',            
            'endDate' => date('Y-m', strtotime('+1 years')),
            'startDate' => date('Y-m', strtotime('-1 years')),
        ],
        'language'=>'tr'
    ]); ?>

    <?php // $form->field($model, 'olcum_sonucu')->textArea(['rows'=>3,'maxlength' => true]) ?>

    <?= $form->field($model, 'kontrol_kriteri')->textArea(['rows'=>3,'maxlength' => true]) ?>

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

    <div class="form-group">
        <?= Html::submitButton('Kaydet', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
