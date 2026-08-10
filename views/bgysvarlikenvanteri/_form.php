<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Bgysbilgisinifi;
use app\models\Bgyskategori;
use app\models\Bgysolasilik;
use app\models\Bgyssiddettablosu;
use app\models\Bgysdepartman;
use app\models\Bgyslokasyon;
use app\models\Bgysvarlikenvanteri;
use app\models\Userbilgi;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Bgysvarlikenvanteri */
/* @var $form yii\widgets\ActiveForm */

$categoryGroups = [];
$categoryOptionsByType = [];
$categoryNamesByType = Bgysvarlikenvanteri::categoryNamesByAssetType();
foreach (Bgysvarlikenvanteri::assetTypeOptions() as $type => $typeLabel) {
    $categoryOptions = ArrayHelper::map(
        Bgyskategori::find()->where(['adi' => $categoryNamesByType[$type]])->orderBy(['adi' => SORT_ASC])->all(),
        'id',
        'adi'
    );
    $categoryGroups[$typeLabel] = $categoryOptions;
    $categoryOptionsByType[$type] = $categoryOptions;
}
?>

<div class="bgysvarlikenvanteri-form">

    <?php $form = ActiveForm::begin(); ?>


<div class="col-lg-12">
    <?= $form->field($model, 'varlik_adi')->textInput(['maxlength' => true]) ?>
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
<div class="col-lg-6">
                            <?= $form->field($model, 'lokasyon')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(Bgyslokasyon::find()->all(),'id','lokasyon'),
        'options' => ['placeholder' => 'Lokasyon',],
        'pluginOptions' => [
            'allowClear' => true
        ],
        ]); 
    ?>
</div> 
<div class="col-lg-6">
      <?= $form->field($model, 'bilgi_sinifi')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(Bgysbilgisinifi::find()->all(),'id','adi'),
        'options' => ['placeholder' => 'Bilgi Sınıfı',],
        'pluginOptions' => [
            'allowClear' => true
        ],
        ]); 
    ?>
</div>  
<div class="col-lg-6">
      <?= $form->field($model, 'asset_type')->widget(Select2::classname(), [
        'data' => Bgysvarlikenvanteri::assetTypeOptions(),
        'options' => ['placeholder' => 'Varlık türü seçin'],
        'pluginOptions' => ['allowClear' => true],
        ]);
    ?>
</div>
<div class="col-lg-6">
      <?= $form->field($model, 'kategori')->widget(Select2::classname(), [
        'data' => $categoryGroups,
        'options' => ['placeholder' => 'Varlık türüne uygun kategori seçin'],
        'pluginOptions' => [
            'allowClear' => true
        ],
        ]); 
    ?>
</div> 
<div class="col-lg-4">
      <?= $form->field($model, 'gizlilik')->widget(Select2::classname(), [
        'data' => (array(1 =>"Düşük" ,2=>"Orta",3=>'Yüksek',4=>'Çok Yüksek')),
        'options' => ['placeholder' => 'Gizlilik Seviyesi',],
        'pluginOptions' => [
            'allowClear' => true
        ],
        ]); 
    ?>
</div>  
<div class="col-lg-4">

      <?= $form->field($model, 'butunluk')->widget(Select2::classname(), [
        'data' => (array(1 =>"Düşük" ,2=>"Orta",3=>'Yüksek',4=>'Çok Yüksek')),
        'options' => ['placeholder' => 'Bütünlük Seviyesi',],
        'pluginOptions' => [
            'allowClear' => true
        ],
        ]); 
    ?>
</div>  
<div class="col-lg-4">
      <?= $form->field($model, 'erisilebilirlik')->widget(Select2::classname(), [
        'data' => (array(1 =>"Düşük" ,2=>"Orta",3=>'Yüksek',4=>'Çok Yüksek')),
        'options' => ['placeholder' => 'Erişilebilirlik Seviyesi',],
        'pluginOptions' => [
            'allowClear' => true
        ],
        ]); 
    ?>
</div> 

    <?= $form->field($model, 'aciklama')->textInput(['maxlength' => true]) ?>

    <div class="col-lg-4">
        <?= $form->field($model, 'owner_type')->dropDownList(
            Bgysvarlikenvanteri::ownerTypeOptions(),
            ['prompt' => 'Sahip türü seçin']
        ) ?>
    </div>

    <div class="col-lg-8" id="asset-owner-unit">
        <?= $form->field($model, 'owner_unit')->dropDownList(
            Bgysvarlikenvanteri::unitOptions(),
            ['prompt' => 'Şube müdürlüğü seçin']
        ) ?>
    </div>

    <div class="col-lg-8" id="asset-owner-user">
        <?= $form->field($model, 'owner_user_id')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(
                Userbilgi::find()->orderBy(['ad' => SORT_ASC, 'soyad' => SORT_ASC])->all(),
                'kisi_id',
                function ($user) {
                    $name = trim($user->ad . ' ' . $user->soyad);
                    return $user->email ? $name . ' / ' . $user->email : $name;
                }
            ),
            'options' => ['placeholder' => 'Kullanıcı seçin'],
            'pluginOptions' => ['allowClear' => true],
        ]) ?>
    </div>

    <?= $form->field($model, 'varlik_sahibi')->textInput([
        'maxlength' => true,
        'readonly' => true,
    ])->hint('Seçilen şube müdürlüğü veya kullanıcıya göre sistem tarafından oluşturulur. Eski kayıtlardaki değer korunur.') ?>

    <div class="form-group">
        <?= Html::submitButton('Kaydet', ['class' => 'btn btn-success btn-lg']) ?>
    </div>

    <?php ActiveForm::end(); ?>

<?php
$ownerTypeInputId = Html::getInputId($model, 'owner_type');
$assetTypeInputId = Html::getInputId($model, 'asset_type');
$categoryInputId = Html::getInputId($model, 'kategori');
$categoryOptionsJson = json_encode($categoryOptionsByType, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
$this->registerJs(<<<JS
(function () {
    var ownerType = $('#{$ownerTypeInputId}');
    var assetType = $('#{$assetTypeInputId}');
    var category = $('#{$categoryInputId}');
    var categoryOptions = {$categoryOptionsJson};
    var initialCategory = category.val();

    function toggleOwnerFields() {
        var isUnit = ownerType.val() === 'unit';
        var isUser = ownerType.val() === 'user';
        $('#asset-owner-unit').toggle(isUnit);
        $('#asset-owner-user').toggle(isUser);
    }

    ownerType.on('change', toggleOwnerFields);
    toggleOwnerFields();

    function refreshCategories(preserveSelection) {
        var selectedType = assetType.val();
        var selectedCategory = preserveSelection ? category.val() : null;
        var options = categoryOptions[selectedType] || {};

        category.empty().append(new Option('', '', false, false));
        $.each(options, function (id, label) {
            category.append(new Option(label, id, false, String(id) === String(selectedCategory)));
        });
        category.trigger('change.select2');
    }

    assetType.on('change', function () {
        refreshCategories(false);
    });
    category.val(initialCategory);
    refreshCategories(true);
}());
JS
);
?>

</div>
