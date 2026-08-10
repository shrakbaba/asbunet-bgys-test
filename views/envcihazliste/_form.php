<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\Envmarka;
use app\models\Envmodel;
use app\models\Envcihazturu;
use app\models\Userdb;
use app\models\Userbilgi;
use app\models\Bgysvarlikenvanteri;

use dosamigos\datepicker\DatePicker;
use dosamigos\datepicker\DateRangePicker;

use kartik\file\FileInput;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $model app\models\Envcihazliste */
/* @var $form yii\widgets\ActiveForm */
$selectedType = $model->cihaz_turu_id ? Envcihazturu::findOne($model->cihaz_turu_id) : null;
$brandQuery = Envmarka::find()->alias('b');
$selectedType
    ? $brandQuery->innerJoin('env_cihaz_turu_marka tm', 'tm.marka_id=b.id')->andWhere(['tm.cihaz_turu_id' => $selectedType->id])
    : $brandQuery->andWhere('0=1');
$modelQuery = Envmodel::find()->alias('m');
($selectedType && $model->marka_id)
    ? $modelQuery->innerJoin('env_cihaz_turu_model tt', 'tt.model_id=m.id')->andWhere(['tt.cihaz_turu_id' => $selectedType->id, 'm.marka_id' => $model->marka_id])
    : $modelQuery->andWhere('0=1');
$assetQuery = Bgysvarlikenvanteri::find();
($selectedType && $selectedType->asset_type)
    ? $assetQuery->andWhere(['asset_type' => $selectedType->asset_type])
    : $assetQuery->andWhere('0=1');
?>

<div class="envcihazliste-form">

    <?php $form = ActiveForm::begin(['layout' => 'horizontal','options'=>['enctype'=>'multipart/form-data']]); ?>

         <?= $form->field($model, 'cihaz_turu_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(Envcihazturu::find()->all(),'id','cihaz_turu'),
        'options' => ['placeholder' => 'Tür Seçiniz',],
        'pluginOptions' => [
            'allowClear' => true
        ],
        ]); 
    ?>

                   <?= $form->field($model, 'marka_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map($brandQuery->orderBy('b.marka')->all(),'id','marka'),
        'options' => ['placeholder' => 'Marka Seçiniz'],
        'pluginOptions' => [
            'allowClear' => true
        ],
        ]); 
    ?>

          <?= $form->field($model, 'model_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map($modelQuery->orderBy('m.model')->all(),'id','model'),
        'options' => ['placeholder' => 'Model Seçiniz',],
        'pluginOptions' => [
            'allowClear' => true
        ],
        ]); 
    ?>
   
    <?= $form->field($model, 'adet')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bgys_asset_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(
            $assetQuery
                ->orderBy(['varlik_adi' => SORT_ASC])
                ->all(),
            'id',
            function ($asset) {
                return $asset->varlik_adi . ($asset->varlik_sahibi ? ' / ' . $asset->varlik_sahibi : '');
            }
        ),
        'options' => ['placeholder' => 'İlişkili BGYS varlığını seçin'],
        'pluginOptions' => ['allowClear' => true],
    ])->hint('Seçilen cihaz türünün sınıfına uygun BGYS varlıkları gösterilir. Tür sınıflandırılmamışsa seçenek gelmez.') ?>

    <div id="software-inventory-fields" style="<?= $selectedType && $selectedType->asset_type === Bgysvarlikenvanteri::TYPE_SOFTWARE ? '' : 'display:none;' ?>">
        <div class="panel panel-info">
            <div class="panel-heading"><strong>Yazılım Lisans ve Barındırma Bilgileri</strong></div>
            <div class="panel-body">
                <?= $form->field($model, 'license_type')->widget(Select2::classname(), [
                    'data' => \app\models\Envcihazliste::licenseTypeOptions(),
                    'options' => ['placeholder' => 'Lisans türünü seçin'],
                    'pluginOptions' => ['allowClear' => true],
                ]) ?>
                <?= $form->field($model, 'license_quantity')->textInput(['type' => 'number', 'min' => 1]) ?>
                <div class="row">
                    <div class="col-md-6"><?= $form->field($model, 'license_start_date')->input('date') ?></div>
                    <div class="col-md-6"><?= $form->field($model, 'license_end_date')->input('date') ?></div>
                </div>
                <?= $form->field($model, 'hosting_environment')->widget(Select2::classname(), [
                    'data' => \app\models\Envcihazliste::hostingEnvironmentOptions(),
                    'options' => ['placeholder' => 'Barındırma ortamını seçin'],
                    'pluginOptions' => ['allowClear' => true],
                ]) ?>
                <?= $form->field($model, 'hosting_detail')->textInput(['maxlength' => true])
                    ->hint('Sunucu, veri merkezi, bulut hizmeti veya SaaS hizmetinin doğrulanmış adı.') ?>
                <?= $form->field($model, 'supplier_name')->textInput(['maxlength' => true])
                    ->hint('Marka/üreticiden farklıysa satın alma veya hizmet tedarikçisi.') ?>
                <?= $form->field($model, 'lifecycle_status')->widget(Select2::classname(), [
                    'data' => \app\models\Envcihazliste::lifecycleStatusOptions(),
                    'options' => ['placeholder' => 'Yaşam döngüsü durumunu seçin'],
                    'pluginOptions' => ['allowClear' => true],
                ]) ?>
            </div>
        </div>
    </div>

   
    <?= $form->field($model, 'alim_tarihi')->textInput()->label('Garanti Süresi')->widget(DateRangePicker::className(), [
        'attributeTo' => 'garanti_bitis', 
        'form' => $form, // best for correct client validation
        'language' => 'tr',
        'size' => 'lg',
        'clientOptions' => [
            'autoclose' => true,
            'format' => 'dd/mm/yyyy' 
        ]
    ]) ;?>


    <?= $form->field($model, 'konum')->textInput(['maxlength' => true])->textarea(['rows' => '2'])->hint('Çoklu girdileri ; ile ayırınız.') ?>

        
    <?= $form->field($model, 'key')->textInput(['maxlength' => true])->textarea(['rows' => '2'])->hint('Çoklu girdileri ; ile ayırınız.')  ?>
        
    <?= $form->field($model, 'service_tag')->textInput(['maxlength' => true])->textarea(['rows' => '2'])->hint('Çoklu girdileri ; ile ayırınız.')  ?>

    <?= $form->field($model, 'ozet')->textInput(['maxlength' => true])->textarea(['rows' => '2']) ?>

    <?= $form->field($model, 'link')->textInput(['maxlength' => true])->textarea(['rows' => '1']) ?>
    
    <?php if($model->dosya) {
        echo Html::a('PDF Belgesini Sil', ['pdfsil', 'i' => $model->id], [
            'class' => 'btn btn-info',
            'style' => 'float: right',
            'data' => [
                'confirm' => 'Bu kaydın dosyasını silmek istediğinizden emin misiniz?',
                'method' => 'post',
            ],
        ]);
    } ?>

     <?= $form->field($model, 'file')->widget(FileInput::classname(), [
                               'pluginOptions'=>
                                    $model->dosya ? [
                                        'initialPreview'=>[
                                            \yii\helpers\Url::to(['/envcihazliste/download', 'id' => $model->id]),
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
        
    <?php /* $form->field($model, 'garanti_bitis')->widget(
        DatePicker::className(), [
        // inline too, not bad
         'inline' => true, 
         // modify template for custom rendering
        'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
        'clientOptions' => [
            'autoclose' => true,
            'format' => 'dd-mm-yyyy'
        ]
    ]);  */ ?>

  
    <?= $form->field($model, 'zimmet')->widget(Select2::classname(), [
        'data' => Yii::$app->params['giristipi']==1 ? 
        ArrayHelper::map(\Edvlerblog\Adldap2\model\UserDbLdap::find()->all(),'id',function($model) {
            //return $model['ad'].' '.$model['soyad'].' / '.$model['username'];
            return @Userbilgi::findOne(['kisi_id'=>$model['id']])->ad.' '.@Userbilgi::findOne(['kisi_id'=>$model['id']])->soyad.' / '.$model['username'];
            }) 
        :ArrayHelper::map(Userdb::find()->all(),'id',function($model) {                            
            return $model['ad'].' '.$model['soyad'].' / '.$model['username'];
            }) ,
        'options' => ['placeholder' => 'Zimmet Yapılacak',],
        'pluginOptions' => [
            'allowClear' => true
        ],
        ]); 
    ?>  

        <?= Html::submitButton('Kaydet', ['class' => 'btn btn-success btn-lg']) ?>
  
    <?php ActiveForm::end(); ?>

</div>

<?php
$catalogUrl = Url::to(['/envcihazliste/catalog-options']);
$typeInput = Html::getInputId($model, 'cihaz_turu_id');
$brandInput = Html::getInputId($model, 'marka_id');
$modelInput = Html::getInputId($model, 'model_id');
$assetInput = Html::getInputId($model, 'bgys_asset_id');
$this->registerJs(<<<JS
(function () {
    var type = $('#{$typeInput}');
    var brand = $('#{$brandInput}');
    var model = $('#{$modelInput}');
    var asset = $('#{$assetInput}');

    function toggleSoftwareFields(assetType) {
        $('#software-inventory-fields').toggle(assetType === 'software');
    }

    function fill(select, rows, selectedValue, emptyText) {
        var placeholder = (!rows || rows.length === 0) && emptyText ? emptyText : '';
        select.empty().append(new Option(placeholder, '', false, false));
        $.each(rows || [], function (_, row) {
            select.append(new Option(row.text, row.id, false, String(row.id) === String(selectedValue)));
        });
        select.trigger('change.select2');
    }

    function requestCatalog(brandId, selectedBrand, selectedModel, selectedAsset) {
        if (!type.val()) {
            fill(brand, [], null, 'Önce cihaz türü seçin');
            fill(model, [], null, 'Önce cihaz türü ve marka seçin');
            fill(asset, [], null, 'Önce cihaz türü seçin');
            return;
        }
        $.getJSON('{$catalogUrl}', {typeId: type.val(), brandId: brandId || ''}).done(function (data) {
            toggleSoftwareFields(data.assetType);
            if (selectedBrand !== false) {
                fill(brand, data.brands, selectedBrand, 'Bu cihaz türü için marka tanımlanmamış');
                fill(asset, data.assets, selectedAsset, 'Bu sınıfa uygun BGYS varlığı tanımlanmamış');
            }
            fill(model, data.models, selectedModel, brandId ? 'Bu marka için model tanımlanmamış' : 'Önce marka seçin');
        });
    }

    type.on('change', function () {
        toggleSoftwareFields(null);
        requestCatalog(null, null, null, null);
    });
    brand.on('change', function () {
        if (!brand.val()) { fill(model, [], null, 'Önce marka seçin'); return; }
        requestCatalog(brand.val(), false, null, false);
    });
}());
JS
);
?>
