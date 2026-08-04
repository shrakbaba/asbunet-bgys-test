<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use yii\helpers\ArrayHelper;
use app\models\Userdb;
use app\models\Userbilgi;
use kartik\select2\Select2;
//use app\models\User;
/* @var $this yii\web\View */
/* @var $model app\models\Authassignment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="authassignment-form">

    <?php $form = ActiveForm::begin(); ?>

  
    <?php /* $form->field($model, 'item_name')->widget(Select2::classname(), [
	    'data' => ArrayHelper::map(Authitem::find()->all(),'name','name'),
	    'options' => ['placeholder' => 'Rol Seçiniz',],
	    'pluginOptions' => [
	        'allowClear' => true
	    ],
		]); */
	?>

    <?= $form->field($model, 'item_name')->dropDownList(\app\models\Authassignment::aktifRolListesi(), ['prompt' => 'Rol Seçiniz']);  ?>

    <?php 
        if (Yii::$app->params['giristipi']==1) {
            echo $form->field($model, 'user_id')->dropDownList(ArrayHelper::map(\Edvlerblog\Adldap2\model\UserDbLdap::find()->all(),'id',function($model) {
            //return $model['ad'].' '.$model['soyad'].' / '.$model['username'];
            return @Userbilgi::findOne(['kisi_id'=>$model['id']])->ad.' '.@Userbilgi::findOne(['kisi_id'=>$model['id']])->soyad.' / '.$model['username'];
            }), ['prompt' => 'Kişi Seçiniz']);  
        }else{
            echo $form->field($model, 'user_id')->dropDownList(ArrayHelper::map(Userdb::find()->all(),'id',function($model) {                            
            return $model['ad'].' '.$model['soyad'].' / '.$model['username'];
            }), ['prompt' => 'Kişi Seçiniz']); 
        }   
    ?>

	<?php /* $form->field($model, 'user_id')->widget(Select2::classname(), [
        'data' => Yii::$app->params['giristipi']==1 ? 
        ArrayHelper::map(\Edvlerblog\Adldap2\model\UserDbLdap::find()->all(),'id',function($model) {
            //return $model['ad'].' '.$model['soyad'].' / '.$model['username'];
            return @Userbilgi::findOne(['kisi_id'=>$model['id']])->ad.' '.@Userbilgi::findOne(['kisi_id'=>$model['id']])->soyad.' / '.$model['username'];
            }) 
        :ArrayHelper::map(Userdb::find()->all(),'id',function($model) {                            
            return $model['ad'].' '.$model['soyad'].' / '.$model['username'];
            }) ,
        'options' => ['placeholder' => 'Kişi Seçiniz',],
        'pluginOptions' => [
            'allowClear' => true
        ],
        ]); */
    ?> 

    <div class="form-group">
        <?= Html::submitButton('Kaydet', ['class' => 'btn btn-success btn-lg']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
