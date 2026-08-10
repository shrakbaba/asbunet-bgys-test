<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;
use dosamigos\datepicker\DateRangePicker;
use kartik\select2\Select2;

use kartik\file\FileInput;
use app\models\Userbilgi;
use app\models\Userdb;
use app\components\SecureFileStorage;
/* @var $this yii\web\View */
/* @var $model app\models\Olaykayit */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="olaykayit-form">

    <?php
    $kullaniciListesi = Yii::$app->params['giristipi']==1
        ? ArrayHelper::map(\Edvlerblog\Adldap2\model\UserDbLdap::find()->all(), function($user) {
            $bilgi = Userbilgi::findOne(['kisi_id'=>$user['id']]);
            return $bilgi ? trim($bilgi->ad.' '.$bilgi->soyad.' / '.$user['username']) : $user['username'];
        }, function($user) {
            $bilgi = Userbilgi::findOne(['kisi_id'=>$user['id']]);
            return $bilgi ? trim($bilgi->ad.' '.$bilgi->soyad.' / '.$user['username']) : $user['username'];
        })
        : ArrayHelper::map(Userdb::find()->all(), function($user) {
            return trim($user->ad.' '.$user->soyad.' / '.$user->username);
        }, function($user) {
            return trim($user->ad.' '.$user->soyad.' / '.$user->username);
        });

    if ($model->mudahaleeden && !isset($kullaniciListesi[$model->mudahaleeden])) {
        $kullaniciListesi[$model->mudahaleeden] = $model->mudahaleeden;
    }
    ?>

    <?php $form = ActiveForm::begin(); ?>
    <?= Html::fileInput('replace_file', null, [
        'id' => 'olay-belge-guncelle-input',
        'accept' => 'application/pdf',
        'style' => 'display:none',
    ]) ?>

    <!--<?php // $form->field($model, 'userid')->textInput() ?>-->

    <?= $form->field($model, 'konu')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'olaytarihi')->textInput()->label('Olay Tarihi ==> Müdahale Tarihi')->widget(DateRangePicker::className(), [
        'attributeTo' => 'mudahaletarihi', 
        'form' => $form, // best for correct client validation
        'language' => 'tr',
        'size' => 'lg',
        'clientOptions' => [
            'autoclose' => true,
            'format' => 'dd/mm/yyyy' 
        ]
    ]) ;?>

    <?= $form->field($model, 'mudahaleeden')->widget(Select2::classname(), [
        'data' => $kullaniciListesi,
        'options' => ['placeholder' => 'Müdahale eden kullanıcıyı seçiniz'],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ]) ?>

    <?= $form->field($model, 'yapilanmudahale')->textInput(['maxlength' => true])->textarea(['rows' => '3']) ?>
    
    <?= $form->field($model, 'sonuc')->textInput(['maxlength' => true])->textarea(['rows' => '3']) ?>
    <?= $form->field($model, 'onlem')->textInput(['maxlength' => true])->textarea(['rows' => '3']) ?>

    <?php if(!$model->isNewRecord && ($model->belge || $model->belgeler)) { ?>
        <div class="form-group">
            <label>Eklenen Belgeler</label>
            <div>
                <?php if($model->belge) { ?>
                    <div style="margin-bottom:6px">
                        <?php $legacyBelgeMevcut = SecureFileStorage::exists($model->belge, 'events', [Yii::$app->basePath . '/web/uploads/bgys/' . md5('olay')]); ?>
                        <?= $legacyBelgeMevcut ? Html::a(Html::encode($model->belge), ['pdfgoster', 'id' => $model->id], [
                            'target' => '_blank',
                            'style' => 'color:#337ab7;text-decoration:underline;',
                            'onclick' => "window.open(this.href, '_blank'); return false;",
                        ]) : Html::tag('span', 'Belge dosyası eksik – Güncelle ile yeniden yükleyin.', ['class' => 'text-danger']) ?>
                        <?= Html::button('<span class="glyphicon glyphicon-remove"></span>', [
                            'class' => 'btn btn-danger btn-xs',
                            'title' => 'Belgeyi Sil',
                            'data-url' => \yii\helpers\Url::to(['pdfsil', 'i' => $model->id]),
                            'data-message' => 'Bu belgeyi silmek istediğinizden emin misiniz?',
                        ]) ?>
                        <button type="button" class="btn btn-info btn-xs olay-belge-guncelle" data-legacy-id="<?= $model->id ?>">Güncelle</button>
                    </div>
                <?php } ?>

                <?php foreach ($model->belgeler as $belge) { ?>
                    <div style="margin-bottom:6px">
                        <?php $ekBelgeMevcut = SecureFileStorage::exists($belge->dosya, 'events', [Yii::$app->basePath . '/web/uploads/bgys/' . md5('olay')]); ?>
                        <?= $ekBelgeMevcut ? Html::a(Html::encode($belge->orijinal_ad), ['belgegoster', 'id' => $belge->id], [
                            'target' => '_blank',
                            'style' => 'color:#337ab7;text-decoration:underline;',
                            'onclick' => "window.open(this.href, '_blank'); return false;",
                        ]) : Html::tag('span', Html::encode($belge->orijinal_ad) . ': dosya eksik – Güncelle ile yeniden yükleyin.', ['class' => 'text-danger']) ?>
                        <?= Html::button('<span class="glyphicon glyphicon-remove"></span>', [
                            'class' => 'btn btn-danger btn-xs',
                            'title' => 'Belgeyi Sil',
                            'data-url' => \yii\helpers\Url::to(['belgesil', 'id' => $belge->id]),
                            'data-message' => 'Bu belgeyi silmek istediğinizden emin misiniz?',
                        ]) ?>
                        <button type="button" class="btn btn-info btn-xs olay-belge-guncelle" data-belge-id="<?= $belge->id ?>">Güncelle</button>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php } ?>

     <?= $form->field($model, 'file')->widget(FileInput::classname(), [
                                'options' => [
                                    'multiple' => true,
                                    'accept' => 'application/pdf',
                                ],
                               'pluginOptions'=>
                                    [
                                        'allowedFileExtensions'=>['pdf'],
                                        'showUpload' => false,
                                        'maxFileSize' => 1024,
                                        'maxFileCount' => 10,
                                        'msgPlaceholder' => 'PDF belgesi seçiniz ...',
                                        'msgSizeTooLarge' => 'Seçilen dosya 1 MB değerinden büyük olamaz.',
                                    ],
                          ])->label('Olayla ilgili dokümanlar (PDF)');   ?>
   
    <div class="form-group">
        <?= Html::submitButton('Kaydet', ['class' => 'btn btn-success btn-lg']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$belgeGuncelleUrl = \yii\helpers\Url::to(['belgeguncelle']);
$this->registerJs(<<<JS
var bgysOlayBelgeGuncellenecek = {};

function bgysOlayFormuYenile(url) {
    var modal = $('#modal');
    modal.find('#modalContent').html('<div style="padding:20px">Yükleniyor...</div>');
    modal.find('.modal-body').scrollTop(0);
    $.get(url, function(data) {
        modal.find('#modalContent').html(data);
        modal.find('.modal-body').scrollTop(0);
    }).fail(function() {
        modal.find('#modalContent').html('<div class="alert alert-danger">İşlemden sonra form yeniden yüklenemedi. Lütfen sayfayı yenileyiniz.</div>');
    });
}

$(document).off('click.bgysOlayBelgeGuncelle', '.olay-belge-guncelle').on('click.bgysOlayBelgeGuncelle', '.olay-belge-guncelle', function() {
    bgysOlayBelgeGuncellenecek = {
        belgeId: $(this).data('belge-id') || '',
        legacyId: $(this).data('legacy-id') || ''
    };
    $('#olay-belge-guncelle-input').val('').trigger('click');
});

$(document).off('change.bgysOlayBelgeGuncelle', '#olay-belge-guncelle-input').on('change.bgysOlayBelgeGuncelle', '#olay-belge-guncelle-input', function() {
    if (!this.files || !this.files.length) {
        return;
    }
    var data = new FormData();
    data.append('replace_file', this.files[0]);
    data.append('belge_id', bgysOlayBelgeGuncellenecek.belgeId || '');
    data.append('legacy_id', bgysOlayBelgeGuncellenecek.legacyId || '');
    data.append(yii.getCsrfParam(), yii.getCsrfToken());

    $.ajax({
        url: '{$belgeGuncelleUrl}',
        type: 'POST',
        data: data,
        processData: false,
        contentType: false
    }).done(function(response) {
        if (response && response.success) {
            bgysOlayFormuYenile(response.reloadUrl);
        } else {
            alert((response && response.message) ? response.message : 'Belge güncellenemedi.');
        }
    }).fail(function() {
        alert('Belge güncellenirken hata oluştu.');
    });
});

$(document).off('click.bgysOlayBelgeSil', '.olaykayit-form .btn-danger[data-url]').on('click.bgysOlayBelgeSil', '.olaykayit-form .btn-danger[data-url]', function(e) {
    e.preventDefault();
    var button = $(this);
    yii.confirm(button.data('message') || 'Bu belgeyi silmek istediğinizden emin misiniz?', function() {
        $.post(button.data('url'), yii.getCsrfParam() + '=' + encodeURIComponent(yii.getCsrfToken()))
            .done(function(response) {
                if (response && response.success) {
                    bgysOlayFormuYenile(response.reloadUrl);
                } else {
                    alert((response && response.message) ? response.message : 'Belge silinemedi.');
                }
            })
            .fail(function() {
                alert('Belge silinirken hata oluştu.');
            });
    });
});
JS
);
?>
