<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Bgysfarkindalikquiz;

/* @var $this yii\web\View */
/* @var $model app\models\Bgysfarkindalikquiz */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Farkındalık Eğitim Sınavları', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="bgysfarkindalikquiz-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php //echo Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php /*echo Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) */ ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'cevaplayan',
            'ip',
            //'cevaplamatarihi',
            [
                'attribute' => 'cevaplamatarihi',
                'format' => ['date', 'php:d/m/Y H:i:s']
            ],
            'puan'
            //'cevaplar',
        ],
    ]) ?>
<div  class="col-md-12">
    <div class="col-md-4 h4">Soru</div>
    <div class="col-md-4 h4">Kullanıcı Cevabı</div>
    <div class="col-md-4 h4" style="color:green">Doğru Cevap</div>
</div>

<?php //echo $model->cevaplar; 
$cevaplar=json_decode($model->cevaplar);
foreach ($cevaplar as $key => $value) {
    $soru = @(new Bgysfarkindalikquiz())->attributeLabels()[$key]; // soru1 in sorusu
    $dogru=@((new Bgysfarkindalikquiz())->dogrular)[$key];  //soru1 in doğru cevap indexi

   // $dogru=((new Bgysfarkindalikquiz())->($key."data"))[$key];  //soru1 in doğru cevap indexi

    if (is_array($value)) {
        echo "<br>";
        $deger=null;
        foreach ($value as $key2 => $value2) {
            $deger=$value2." ; ".$deger;
        }
    }
    else{
        $deger=$value;  //soru1 kullanıcı cevabı
    }
    $var=$key."data";
    $cevapsikki=@((new Bgysfarkindalikquiz())->$var)[$deger];  //soru1 in doğru cevap indexi

    if (is_array($dogru)) {
        echo "<br>";
        $deger2=null;
        foreach ($dogru as $key3 => $value3) {
            $deger2=$value3." ; ".$deger2;
        }
    }
    else{
         $deger2=$dogru;  //soru1 doğru cevabı
    }
    $var=$key."data";
    $dogrusikki=@((new Bgysfarkindalikquiz())->$var)[$deger2];  //soru1 in doğru cevap indexi

    //echo "<pre>";var_dump($cevapsikki);exit;
    ?>
    <div  class="col-md-12">
        <div class="col-md-4"><?= $soru ?></div>
        <?php if($deger==$deger2) {?>
            <div class="col-md-4" style="color: green;"><?= ($cevapsikki) ?></div>
        <?php }else{ ?>
            <div class="col-md-4"  style="color: red;"><?= ($cevapsikki) ?></div>
        <?php } ?>
    <div class="col-md-4" style="color:green"><?= ($dogrusikki) ?></div>
    </div> <?php

}

?> 

</div>
