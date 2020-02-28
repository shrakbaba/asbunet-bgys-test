<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Userbilgi;
use app\models\Bgysizlemesonucu;
use yii\helpers\imdat;

/* @var $this yii\web\View */
/* @var $model app\models\Bgysizlemeolcme */

\yii\web\YiiAsset::register($this);
?>
<style type="text/css">
    td a span {
        color: #f9fafc !important;
    }
</style>
<div class="bgysizlemeolcme-view">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'yil',
            'kontrol',
            'hedef_degeri',
            //'olcum_sikligi',
             [
                'attribute'=>'olcum_sikligi',
                'format'=>'raw',                
                //'filter'=>array(1 =>"Yılda 1", 2 =>"6 Ayda bir", 3 =>"3 Ayda 1", 4 =>"Ayda 1"),
                'value'=>function ($data)
                    {
                        return 
                            $data->olcum_sikligi==1 
                            ? "Yılda 1"
                            : ( $data->olcum_sikligi==2 
                                ?  "6 Ayda bir" 
                                : ( $data->olcum_sikligi==3 
                                    ? "3 Ayda 1"
                                    :( $data->olcum_sikligi==4 
                                        ? "Ayda 1" 
                                        : ""
                                    )
                                ) 
                            )
                        ; 
                    }
            ],
            'planlanan_tarihi',
            //'olcum_sonucu',
            'kontrol_kriteri',
            //'sorumlu',
            [
                'value'=>function ($data)
                    {
                        return Userbilgi::findOne(['kisi_id'=>$data->sorumlu])->ad.' '.@Userbilgi::findOne(['kisi_id'=>$data->sorumlu])->soyad.' / '.@$data->sorumlu0->username ;
                    },
                'label'=>'Sorumlu',
            ],
            //'olusturma_tarihi',
            [
                'attribute'=>'olusturma_tarihi',
                'value'=>function ($data)
                    {
                        return date("d-m-Y H:i", strtotime($data->olusturma_tarihi));
                    }
            ],
        ],
    ]) ?>

</div>

<?php 

foreach ($sonuclar as $key => $value) {
    $sec = strtotime($value->olusturma_tarihi);
    $sec = date("d-m-Y H:i", $sec);
   $a= '
        <div class="row">
            <div class="col-lg-2" style="text-align: center;">'.Html::a('<span class="glyphicon glyphicon-trash"></span>', 
                                                ['kayitsil', 'id'=>$value->id] ,
                                                [   'class' => 'btn btn-danger',
                                                    'data-pjax' => '0',
                                                    'title'=>"Sil",
                                                    'data' => [
                                                        'confirm' => 'Bu kaydın dosyasını silmek istediğinizden emin misiniz?',
                                                        'method' => 'post',
                                                    ]
                                                ]).'</div>
            <div class="col-lg-10">
                <span class="baslik">Kontrol Sonucu</span>
                <span>'.$value->olcumsonucu.'</span><br> 
                <span class="baslik">Gerçekleştirme Oranı</span>
                <span>'.$value->hedeforani.'</span> <br> 
                <span class="baslik">Kayıt Tarihi</span>
                <span>'. $sec.' </span>
            </div>
        </div>
        <hr>';
    echo $a;
}

?>
<style type="text/css">
    .baslik{
        color:brown;font-size:17px;font-weight: bolder;
    }
</style>
<div>
    <div class="col-lg-2"></div>
    <div class="col-lg-10"></div>
</div>