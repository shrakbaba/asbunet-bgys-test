<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use kartik\file\FileInput;
use unclead\multipleinput\MultipleInput;

/* @var $this yii\web\View */
/* @var $model app\models\Firmabilgi */
/* @var $form yii\widgets\ActiveForm */
$faaliyetAlanlari = is_array($model->faaliyet_alani)
    ? $model->faaliyet_alani
    : (array)json_decode($model->faaliyet_alani);
?>

	<style type="text/css">
		
		.field-firmabilgi-file{
			padding-top: 15px;
			}
		.field-bgysfirmabilgi-faaliyet_alani .multiple-input-list__item,
		.field-firmabilgi-faaliyet_alani .multiple-input-list__item {
			margin-left: 0;
			margin-right: 0;
		}
		.field-bgysfirmabilgi-faaliyet_alani .list-cell__button,
		.field-firmabilgi-faaliyet_alani .list-cell__button {
			padding-left: 8px;
			padding-right: 0;
		}
		.field-bgysfirmabilgi-faaliyet_alani input.form-control,
		.field-firmabilgi-faaliyet_alani input.form-control {
			height: 34px;
		}
		.field-bgysfirmabilgi-faaliyet_alani .multiple-input-list__btn,
		.field-firmabilgi-faaliyet_alani .multiple-input-list__btn {
			margin-top: 0;
			height: 34px;
			width: 40px;
			line-height: 20px;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			vertical-align: top !important;
		}
	</style>

<div class="firmabilgi-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'firmaadi')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'yetkilikisi')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'telefon')->widget(yii\widgets\MaskedInput::class, ['mask' => '(999)-999-9999',]) ?>
    
 <?= $form->field($model, 'faaliyet_alani')->widget(MultipleInput::className(), [
	        'max'               => 6,
	        'min'               => 1, // should be at least 2 rows
	        'data'				=> $faaliyetAlanlari,
	        'allowEmptyList'    => false,
	        'enableGuessTitle'  => true,
	        //'theme'  => MultipleInput::THEME_BS,
	        'addButtonPosition' => MultipleInput::POS_ROW, // show add button in the header
	        'layoutConfig' => [
	        	'wrapperClass' => 'col-xs-11',
	        	'buttonActionClass' => 'col-xs-1',
	        	'errorClass' => 'col-xs-12',
	        ],
	        'addButtonOptions' => [
	        	'class' => 'btn btn-default faaliyet-alani-ekle',
	        ],
	        'removeButtonOptions' => [
	        	'class' => 'btn btn-danger faaliyet-alani-sil',
	        ],
	        'enableError'  => true,
	    ])
	    ->label(false);
	?>

	<?= $form->field($model, 'tedarik_tipi')->dropDownList((array(1 =>"Hizmet" ,2=>"Malzeme",3=>'Servis',4=>'Yüksek Teknoloji',5=>'Yazılım',6=>'Lisans')), 
                        ['prompt' => 'Tedarik Tipi'])     ?>

    <?= $form->field($model, 'mail')->widget(yii\widgets\MaskedInput::class, [
                            'clientOptions' => [ 'alias' =>  'email'  ], 
                        ]) ?>

    <?php /*/ $form->field($model, 'file')->widget(FileInput::classname(), [
              'options' => ['accept' => 'image/*'],
               'pluginOptions'=>['allowedFileExtensions'=>['pdf'],'showUpload' => false,],
          ]);  */ ?>

	<?php if($model->belge) { ?>
		<div class="form-group">
			<label>Eklenen Belge</label>
			<div>
				<?= Html::a(Html::encode($model->belge), ['belge', 'id' => $model->id], [
					'target' => '_blank',
					'data-pjax' => '0',
					'style' => 'color:#337ab7;text-decoration:underline;',
				]) ?>
				<?= Html::a('<span class="glyphicon glyphicon-remove"></span>', ['pdfsil', 'i' => $model->id], [
					'class' => 'btn btn-danger btn-xs',
					'style' => 'margin-left: 8px;',
					'title' => 'Belgeyi Sil',
					'data' => [
						'confirm' => 'Bu PDF belgesini silmek istediğinizden emin misiniz?',
						'method' => 'post',
						'pjax' => '0',
					],
				]) ?>
				<?= Html::button('Güncelle', [
					'class' => 'btn btn-warning btn-xs belge-guncelle',
					'style' => 'margin-left: 8px;',
					'title' => 'Belgeyi Güncelle',
				]) ?>
			</div>
		</div>
	<?php } ?>
    
    <?= $form->field($model, 'file')->widget(FileInput::classname(), [
				'options' => ['accept' => 'application/pdf'],
				'pluginOptions'=>[
					'allowedFileExtensions'=>['pdf'],
					'showUpload' => false,
					'showPreview' => false,
				],
			]);   ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Kaydet' : 'Güncelle', ['class' => 'btn btn-success btn-lg']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$this->registerJs(<<<JS
$(document).off('beforeAddRow.faaliyetAlani').on('beforeAddRow.faaliyetAlani', '.field-bgysfirmabilgi-faaliyet_alani .multiple-input, .field-firmabilgi-faaliyet_alani .multiple-input', function () {
    var sonInput = $(this).find('.multiple-input-list__item:visible').last().find('input[type="text"]').first();
    var deger = $.trim(sonInput.val() || '');

    $(this).closest('.form-group').find('.help-block').text('');
    sonInput.closest('.form-group').removeClass('has-error');

    if (deger === '') {
        sonInput.closest('.form-group').addClass('has-error');
        $(this).closest('.form-group').find('.help-block').first().text('Faaliyet Alanı boş bırakılamaz.');
        sonInput.focus();
        return false;
    }
});

	function bgysFaaliyetAlanlariniToparla(kapsayici) {
	    var degerler = [];
	    kapsayici.find('.multiple-input-list__item input[type="text"]').each(function () {
        var deger = $.trim($(this).val() || '');
        if (deger !== '') {
            degerler.push(deger);
        }
    });

	    kapsayici.find('.multiple-input-list__item input[type="text"]').each(function (index) {
	        $(this).val(degerler[index] || '');
	    });

	    var ilkInput = kapsayici.find('.multiple-input-list__item input[type="text"]').first();
	    if ($.trim(ilkInput.val() || '') !== '') {
	        kapsayici.closest('.form-group').find('.help-block').text('');
	        ilkInput.closest('.form-group').removeClass('has-error');
	    }
	}

$('.field-bgysfirmabilgi-faaliyet_alani .multiple-input, .field-firmabilgi-faaliyet_alani .multiple-input').each(function () {
    bgysFaaliyetAlanlariniToparla($(this));
});

	$(document).off('afterAddRow.faaliyetAlani afterDeleteRow.faaliyetAlani').on('afterAddRow.faaliyetAlani afterDeleteRow.faaliyetAlani', '.field-bgysfirmabilgi-faaliyet_alani .multiple-input, .field-firmabilgi-faaliyet_alani .multiple-input', function () {
	    bgysFaaliyetAlanlariniToparla($(this));
	});

	$(document).off('input.faaliyetAlani change.faaliyetAlani blur.faaliyetAlani').on('input.faaliyetAlani change.faaliyetAlani blur.faaliyetAlani', '.field-bgysfirmabilgi-faaliyet_alani .multiple-input input[type="text"], .field-firmabilgi-faaliyet_alani .multiple-input input[type="text"]', function () {
	    bgysFaaliyetAlanlariniToparla($(this).closest('.multiple-input'));
	});

	$(document).off('beforeSubmit.faaliyetAlani').on('beforeSubmit.faaliyetAlani', '.firmabilgi-form form', function () {
	    $(this).find('.field-bgysfirmabilgi-faaliyet_alani .multiple-input, .field-firmabilgi-faaliyet_alani .multiple-input').each(function () {
	        bgysFaaliyetAlanlariniToparla($(this));
	    });
	});

	$(document).off('mousedown.faaliyetAlaniSubmit click.faaliyetAlaniSubmit').on('mousedown.faaliyetAlaniSubmit click.faaliyetAlaniSubmit', '.firmabilgi-form button[type="submit"], .firmabilgi-form input[type="submit"]', function () {
	    $(this).closest('form').find('.field-bgysfirmabilgi-faaliyet_alani .multiple-input, .field-firmabilgi-faaliyet_alani .multiple-input').each(function () {
	        bgysFaaliyetAlanlariniToparla($(this));
	    });
	});

	$(document).off('click.belgeGuncelle').on('click.belgeGuncelle', '.belge-guncelle', function () {
	    $(this).closest('form').find('input[type="file"]').trigger('click');
	});

document.removeEventListener('click', window.bgysFaaliyetAlaniSilmeOnayi, true);
window.bgysFaaliyetAlaniSilmeOnayi = function (event) {
    var buton = $(event.target).closest('.field-bgysfirmabilgi-faaliyet_alani .js-input-remove, .field-firmabilgi-faaliyet_alani .js-input-remove');
    if (!buton.length) {
        return;
    }

    if (buton.data('bgys-onaylandi')) {
        buton.removeData('bgys-onaylandi');
        return;
    }

    event.preventDefault();
    event.stopPropagation();
    event.stopImmediatePropagation();

	    window.bgysConfirmOptions = {
	        title: '<span class="glyphicon glyphicon-warning-sign"></span> Faaliyet Alanı Silme Onayı',
	        description: '',
	        okText: 'Evet, Sil'
	    };
    yii.confirm('Bu faaliyet alanını silmek istediğinize emin misiniz?', function () {
        buton.data('bgys-onaylandi', true);
        buton.trigger('click');
    });
};
document.addEventListener('click', window.bgysFaaliyetAlaniSilmeOnayi, true);
JS
);
?>
