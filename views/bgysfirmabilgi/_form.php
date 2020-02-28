<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\Firmabilgi */
/* @var $form yii\widgets\ActiveForm */
?>

	<style type="text/css">
		
		.field-firmabilgi-file{
			padding-top: 15px;
			}
	</style>

<div class="firmabilgi-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'firmaadi')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'yetkilikisi')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'telefon')->widget(yii\widgets\MaskedInput::class, ['mask' => '(999)-999-9999',]) ?>

    <?= $form->field($model, 'faaliyet_alani')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'tedarik_tipi')->dropDownList((array(1 =>"Hizmet" ,2=>"Malzeme",3=>'Servis',4=>'Yüksek Teknoloji',5=>'Yazılım',6=>'Lisans')), 
                        ['prompt' => 'Tedarik Tipi'])     ?>

    <?= $form->field($model, 'mail')->widget(yii\widgets\MaskedInput::class, [
                            'clientOptions' => [ 'alias' =>  'email'  ], 
                        ]) ?>

    <?php /*/ $form->field($model, 'file')->widget(FileInput::classname(), [
              'options' => ['accept' => 'image/*'],
               'pluginOptions'=>['allowedFileExtensions'=>['pdf'],'showUpload' => false,],
          ]);  */ ?>

	<?php if($model->belge) {
		?> <a class ="btn btn-info" style="float: right" href="pdfsil?i=<?php echo $model->id; ?>"> PDF Belgesini Sil</a>
	<?php } ?>
    
    <?= $form->field($model, 'file')->widget(FileInput::classname(), [
				               'pluginOptions'=>
						        	$model->belge ? [
					               		'initialPreview'=>[
							            	"/uploads/bgys/".md5("firma")."/".$model->belge,
							        	],
			            				'initialPreviewFileType' => 'pdf',
			        					'initialPreviewAsData'=>true,
			        					'allowedFileExtensions'=>['pdf'],
		        						'showUpload' => false 
			        				]
		        					:[
		        						'allowedFileExtensions'=>['pdf'],
		        						'showUpload' => false
		        					] ,
				          ]);   ?>

    <div class="form-group">
        <?= Html::submitButton('Kaydet', ['class' => 'btn btn-success btn-lg']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
