<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use yii\helpers\ArrayHelper;
use app\models\Bgysvarlikenvanteri;
use app\models\Userdb;
use app\models\Bgyssiddettablosu;
use app\models\Bgysdepartman;
use app\models\Bgysolasilik;
use kartik\select2\Select2;
use app\models\Userbilgi;

/* @var $this yii\web\View */
/* @var $model app\models\Bgysrisk */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bgysrisk-form">

    <?php $form = ActiveForm::begin(); ?>

<div class="col-lg-6">
    <?= $form->field($model, 'varlik')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(Bgysvarlikenvanteri::find()->all(),'id','varlik_adi'),
        'options' => ['placeholder' => 'Varlık',],
        'pluginOptions' => [
            'allowClear' => true
        ],
        ]); 
    ?>

</div> 

<div class="col-lg-6">
     <?= $form->field($model, 'departman')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(Bgysdepartman::find()->all(),'id','departman'),
        'options' => ['placeholder' => 'Departman',],
        'pluginOptions' => [
            'allowClear' => true
        ],
        ]); 
    ?>
</div> 
<div class="col-lg-12">
    <?= $form->field($model, 'risk')->textArea(['rows'=>3,'maxlength' => true]) ?></div> 
<div class="col-lg-12">
    <?= $form->field($model, 'risk_nedeni')->textArea(['rows'=>3,'maxlength' => true]) ?></div> 

<div class="col-lg-12">
     <?= $form->field($model, 'risk_sorumlusu')->widget(Select2::classname(), [
        'data' => Yii::$app->params['giristipi']==1 ? 
        ArrayHelper::map(\Edvlerblog\Adldap2\model\UserDbLdap::find()->all(),'id',function($model) {
            //return $model['ad'].' '.$model['soyad'].' / '.$model['username'];
            return @Userbilgi::findOne(['kisi_id'=>$model['id']])->ad.' '.@Userbilgi::findOne(['kisi_id'=>$model['id']])->soyad.' / '.$model['username'];
            }) 
        :ArrayHelper::map(Userdb::find()->all(),'id',function($model) {                            
            return $model['ad'].' '.$model['soyad'].' / '.$model['username'];
            }) ,
        'options' => ['placeholder' => 'Sorumlu',],
        'pluginOptions' => [
            'allowClear' => true
        ],
        ]); 
    ?>                       
</div>

<div class="clearfix"></div>
<h4>Müdahale Öncesi Değerler</h4>
<hr>
<div class="col-lg-3">
        <?= $form->field($model, 'olasilik_onceki')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(Bgysolasilik::find()->all(),'id','deger'),
        'options' => ['placeholder' => 'Olasılık',],
        'pluginOptions' => [
            'allowClear' => true
        ],
        ]); 
    ?>
</div> 

<div class="col-lg-3">
        <?= $form->field($model, 'gizlilik_onceki')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(Bgyssiddettablosu::find()->all(),'id','anlam'),
        'options' => ['placeholder' => 'Gizlilik',],
        'pluginOptions' => [
            'allowClear' => true
        ],
        ]); 
    ?>
</div> 
<div class="col-lg-3">
        <?= $form->field($model, 'butunluk_onceki')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(Bgyssiddettablosu::find()->all(),'id','anlam'),
        'options' => ['placeholder' => 'Butunluk',],
        'pluginOptions' => [
            'allowClear' => true
        ],
        ]); 
    ?>
</div> 
<div class="col-lg-3">
        <?= $form->field($model, 'erisilebilirlik_onceki')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(Bgyssiddettablosu::find()->all(),'id','anlam'),
        'options' => ['placeholder' => 'Erişilebilirlik',],
        'pluginOptions' => [
            'allowClear' => true
        ],
        ]); 
    ?>                        
                        </div> 
<br>
<h4>Müdahale Sonrası Değerler</h4>
<hr>
<div class="col-lg-3">
     <?= $form->field($model, 'olasilik_sonraki')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(Bgysolasilik::find()->all(),'id','deger'),
        'options' => ['placeholder' => 'Olasılık',],
        'pluginOptions' => [
            'allowClear' => true
        ],
        ]); 
    ?>   
</div> 
<div class="col-lg-3">
         <?= $form->field($model, 'gizlilik_sonraki')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(Bgyssiddettablosu::find()->all(),'id','anlam'),
        'options' => ['placeholder' => 'Gizlilik',],
        'pluginOptions' => [
            'allowClear' => true
        ],
        ]); 
    ?>  
</div> 
<div class="col-lg-3">
                            
    <?= $form->field($model, 'butunluk_sonraki')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(Bgyssiddettablosu::find()->all(),'id','anlam'),
        'options' => ['placeholder' => 'Butunluk',],
        'pluginOptions' => [
            'allowClear' => true
        ],
        ]); 
    ?>
                        </div> 
<div class="col-lg-3">

                                <?= $form->field($model, 'erisilebilirlik_sonraki')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(Bgyssiddettablosu::find()->all(),'id','anlam'),
        'options' => ['placeholder' => 'Erişilebilirlik',],
        'pluginOptions' => [
            'allowClear' => true
        ],
        ]); 
    ?>
                        </div> 
<div class="clearfix"></div>
<div class="col-lg-12">
    <?= $form->field($model, 'yuksek_riskin_sebebi')->textArea(['rows'=>3,'maxlength' => true]) ?>
</div>

<div class="clearfix"></div>
    <div class="form-group">
        <?= Html::submitButton('Kaydet', ['class' => 'btn btn-success btn-lg']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
