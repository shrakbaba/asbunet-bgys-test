<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\imdat;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use app\models\Userbilgi;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FirmadegerlendirmeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tedarikçi Değerlendirme';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
.rowData{
    font-size: 12px;
}
td {
    padding-top: 2px !important;
    padding-bottom: 2px !important;    
    vertical-align: middle !important;
}
</style>
<div class="firmadegerlendirme-index">

        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <h1><?= Html::encode($this->title) ?></h1>

        <p>
        <?= Html::button('Değerlendirme Yap', ['value' => Url::to(['bgysfirmadegerlendirme/create']),'class' => 'btn btn-success btn-lg modalButton2']) ?>
        </p> 
    <div> 
        <div class="alert alert-danger small" style="margin-bottom: 5px;">
            0-66 Kurumda tedarikçi ile ilgili yeterli memnuniyet oluşmamıştır. 
        </div >
        <div class="alert alert-info small" style="margin-bottom: 5px;">
            67-83  Kurumdaki tedarikçi memnuniyet artışı için tedarikçi uyarılabilir.
        </div>
        <div class="alert alert-success small" style="margin-bottom: 5px;">
            84-100 Kurum tedarikçi ile çalışmaktan memnundur.
        </div>
    </div>

       <?php Pjax::begin(); ?>
       <?php
Modal::begin([
    'id'=>'modal',
    'size'=>'modal-lg',
]);

    echo "<div id='modalContent'></div>";
Modal::end();
?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions'=>function($model){
            if ($model->toplam >= 84 and $model->toplam <= 100)  {
              return ['class'=>'success'];
            }else if ($model->toplam >= 67 and $model->toplam <= 83)  {
              return ['class'=>'info'];
            }else if ($model->toplam < 66)  {
              return ['class'=>'danger'];
            }
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

           // 'id',
           // 'firmaid',
            [
                'attribute'=>'firmaid',
                'format'=>'raw',
                'value'=>'firmabilgi.firmaadi',
            ],
            //'degerlendiren',
            [
                'attribute'=>'degerlendiren',
                'format'=>'raw',
                //'value'=>'user.username',
               /* 'value'=>function ($data)
                    {
                        return $data->user->username." / ".$data->user->ad." ".$data->user->soyad;
                    }*/
                'value'=>function ($model) {  
                       //return @$model->zimmet0->ad." ".@$model->zimmet0->soyad;
                       return Yii::$app->params['giristipi']==1 ? @Userbilgi::findOne(['kisi_id'=>$model->degerlendiren])->ad.' '.@Userbilgi::findOne(['kisi_id'=>$model->degerlendiren])->soyad.' / '.@$model->user->username : @$model->user->ad." ".@$model->user->soyad." ".@$model->user->username; 
                },
            ],
            'toplam',
            [
                'attribute'=>'onay',
                'format'=>'raw',
                'value'=>function ($data)
                    {  return imdat::onaydurumu($data->onay);  }
            ],
            'degerlendirilenyil',
            /*'kriter1',
            'kriter2',
            'kriter3',
            'kriter4',
            'kriter5',
            'kriter6',
            'kriter7',
            'kriter8',
            'kriter9',
            'kriter10',
            'kriter11',
            'kriter12',*/

            ['class' => 'yii\grid\ActionColumn',
                'header'=>'İşlemler',
                'headerOptions' => ['style' => 'width:10%'],
                'template' =>'{view} {update} {delete} {onay} {onaykaldir}',
                'buttons' => [                                  
                    'view' => function ($url,$model) {
                        return  ( 
                            // Html::a('<span class="glyphicon glyphicon-eye-open">', ['view','id'=>$model->id], ['class' => 'btn btn-success','title'=>"İncele"] )

                            Html::button('<span class="glyphicon glyphicon-eye-open">', ['value' => Url::to(['view','id'=>$model->id]),'class' => 'modalButton4 btn btn-success btn-xs' ,'title'=>"İncele"])                      
                            );
                         },
                    'update' => function($url, $model) {   //hertürlü
                        return (!$model->onay) 
                            ?                            
                            Html::button('<span class="glyphicon glyphicon-pencil">', ['value' => Url::to(['update','id'=>$model->id]),'class' => 'modalButton3 btn btn-warning btn-xs' ,'title'=>"Güncelle"]) 
                            :
                            null;
                    },
                    'delete' => function($url, $model) {   //onaylanmamışsa ve kesin başvuru yapmamışsa
                        return (!$model->onay) 
                            ? 
                            Html::a('<span class="glyphicon glyphicon-trash"></span>', 
                                                ['delete', 'id'=>$model->id] ,
                                                [   'class' => 'btn btn-danger btn-xs',
                                                    'data-pjax' => '0',
                                                    'title'=>"Sil",
                                                    'data' => [
                                                        'confirm' => 'Bu kaydı silmek istediğinizden emin misiniz?',
                                                        'method' => 'post',
                                                    ]
                                                ]) 
                            :
                            null;
                    }, 
                   'onay' => function ($url, $model, $key) {  //kurs başvuru onaylanmış ve kesin kayıt yapılmamışsa
                        return  
                            (Yii::$app->user->can('BGYS_Yonetim_Temsilcisi') and (!$model->onay) )
                            ? 
                                Html::a(' Onayla', ['onay', 'i'=>$model->id] ,['class'=>'btn btn-info btn-xs' ])
                            : 
                                null;
                    },
                    'onaykaldir' => function ($url, $model, $key) {  //kurs başvuru onaylanmış ve kesin kayıt yapılmamışsa
                        return  
                            (Yii::$app->user->can('BGYS_Yonetim_Temsilcisi') and $model->onay) 
                            ? 
                                Html::a(' İptal', ['onaykaldir', 'i'=>$model->id] ,['class'=>'btn btn-danger btn-xs' ])
                            : 
                                null;
                    },   
                ],

            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
    
