<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;

use yii\helpers\ArrayHelper;
use app\models\Userdb;
use app\models\Userbilgi;
use app\models\Bgysrisk;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $model app\models\Bgysdiftalep */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bgysdiftalep-form">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'dif_no')->textInput(['maxlength' => true,'readonly'=>true]) ?>

        <?= $form->field($model, 'talep_eden')->textInput(['maxlength' => true]) ?>
    
        <?= $form->field($model, 'planlanan_tarih')->widget(
            DatePicker::className(), [
            // inline too, not bad
             //'inline' => true, 
             // modify template for custom rendering
            'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
            'clientOptions' => [
                'autoclose' => true,
                'format' => 'dd/mm/yyyy', 
                'minViewMode'=> "days"
            ],
            'language'=>'tr',
            'options'=>[  
              'placeholder'=>"Gün/Ay/Yıl"
            ],
            ]); 
        ?>
   
        <?= $form->field($model, 'durum')->dropDownList(array(0=>'Devam Ediyor',1=>'Kapatıldı'), ['prompt' => 'Dif Kapatıldı mı?'])  ?>
        <?php //echo $form->field($model, 'risk_iliskisi')->dropDownList(array(0=>'Devam Ediyor',1=>'Kapatıldı'), ['prompt' => 'Dif Kapatıldı mı?'])  ?>
        <?php /*echo '<label class="control-label">İlişkili Risk</label>';
            echo Select2::widget([    
            'model' => $model,
            'name' => 'risk_iliskisi',
            'data' => ArrayHelper::map(Bgysrisk::find()->all(),'id',function($model) {
                        return $model['id'].' '.$model['risk'];
                    }) ,
            'options' => [
                'placeholder' => 'Risk seçiniz',
                'multiple' => true
            ],
            ]); */
        ?>

        <?=    $form->field($model, 'risk_iliskisi')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(Bgysrisk::find()->all(),'id',function($model) {
                                return $model['id'].' '.$model['risk'];
                    }) ,
                'options' => ['placeholder' => 'İlişki Risk Seçiniz', 'multiple' => true],
                'pluginOptions' => [ 'allowClear' => true ],
            ]); 
        ?>

        <?= $form->field($model, 'dif_konusu')->textarea(['maxlength' => true,'rows' => 6]) ?>

    <?= $form->field($model, 'sorumlu')->widget(Select2::classname(), [
        'data' => Yii::$app->params['giristipi']==1 ? 
        ArrayHelper::map(\Edvlerblog\Adldap2\model\UserDbLdap::find()->all(),'id',function($model) {
            //return $model['ad'].' '.$model['soyad'].' / '.$model['username'];
            return @Userbilgi::findOne(['kisi_id'=>$model['id']])->ad.' '.@Userbilgi::findOne(['kisi_id'=>$model['id']])->soyad.' / '.$model['username'];
            }) 
        :ArrayHelper::map(Userdb::find()->all(),'id',function($model) {                            
            return $model['ad'].' '.$model['soyad'].' / '.$model['username'];
            }) ,
        'options' => ['placeholder' => 'Dif Sorumlusu',],
        'pluginOptions' => [
            'allowClear' => true
        ],
        ]); 
    ?>                       

    <div class="form-group">
        <?= Html::submitButton('Kaydet', ['class' => 'btn btn-success btn-lg']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
