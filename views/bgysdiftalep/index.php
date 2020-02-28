<?php

use yii\helpers\Html;
use yii\grid\GridView;

use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\helpers\imdat;
use app\models\Userbilgi;
use app\models\Bgysrisk;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BgysdiftalepSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Dif Talep Listesi';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bgysdiftalep-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::button('Dif Ekle', ['value' => Url::to(['/bgysdiftalep/create']),'class' => 'btn btn-success btn-lg','id'=>'modalButton']) ?>
        <?php // echo Html::a('Dif Ekle', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php
    Modal::begin([
        'header'=>"<h2>Dif Ekle</h2>",
        'id'=>'modal',
        'size'=>'modal-lg',
    ]);

        echo "<div id='modalContent'></div>";
    Modal::end();
    ?>

    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'dif_no',
            [
                'attribute' => 'planlanan_tarih',
                'format' => ['date', 'php:d/m/Y']
            ], 
            'talep_eden',
            'dif_konusu',
            //'durum',
            [
                'attribute'=>'durum',
                'format'=>'raw',                
                'filter'=>array(0=>'Devam Ediyor',1=>'Kapatıldı'),
                'value'=>function ($data)
                    {
                        return $data->durum==1 ? "Kapatıldı":"Devam Ediyor"; 
                    }
            ],
            //'planlanan_tarih',
            [
                'attribute'=>'sorumlu',
                'format'=>'raw',
                'value'=>function ($model) {  
                           //return @$model->zimmet0->ad." ".@$model->zimmet0->soyad;
                           return Yii::$app->params['giristipi']==1 ? @Userbilgi::findOne(['kisi_id'=>$model->sorumlu])->ad.' '.@Userbilgi::findOne(['kisi_id'=>$model->sorumlu])->soyad.' / '.@$model->sorumlu0->username : @$model->sorumlu0->ad." ".@$model->sorumlu0->soyad." ".@$model->sorumlu0->username; 
                    },
            ],
            [
                'attribute'=>'risk_iliskisi',
                'format'=>'raw',                
                'value'=>function ($data)
                {   $a=null;
                    if (json_decode($data->risk_iliskisi)) {
                        foreach (json_decode($data->risk_iliskisi) as $key => $value) {
                            $a=$a." <b>Risk ".$value."</b> ".@Bgysrisk::find()->where(['id'=>$value])->one()->risk."<br>";                            
                        } 
                    }
                    return $a; 
                }
            ],

            ['class' => 'yii\grid\ActionColumn',

                'template' =>'{view} {update} {delete} {difformac} {onaylidifform} {difform} ',

                'buttons' => [
                    'view' => function($url, $model) {   //hertürlü
                        //echo "<pre>";var_dump(Basvurukesinkayit::find()->where(['kurskayitid' => $model->id])->one());echo "</br>";
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => Yii::t('app', 'View')]);
                    },
                    'update' => function($url, $model) {   //hertürlü
                        return (1==1 and !imdat::difformonaydurumu($model->id) ) 
                            ? 
                            Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['title' => Yii::t('app', 'Update')])
                            :
                            null;
                    },
                    'delete' => function($url, $model) {   //onaylanmamışsa ve kesin başvuru yapmamışsa
                        return (1==1 and !imdat::difformonaydurumu($model->id)) 
                            ? 
                            Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                'title' => Yii::t('app', 'Delete'),
                                'data' => [
                                    'confirm' => 'Bu kayıdı silmek istediğinizden emin misiniz?',
                                    'method' => 'post',
                                ],
                            ])
                            :
                            null;
                    },  
                   'difformac' => function ($url, $model, $key) {  //kurs başvuru onaylanmış ve kesin kayıt yapılmamışsa
                        return  
                            ((!imdat::difform($model->id)) and !imdat::difformonaydurumu($model->id)  )
                            ?                                 
                                Html::a('Aksiyon Tanımla', ['difformuac', 'i'=>$model->id] ,['class'=>'btn btn-info btn-xs' ])
                            : 
                                null;
                    },   
                   'difform' => function ($url, $model, $key) {  //kurs başvuru onaylanmış ve kesin kayıt yapılmamışsa
                        return  
                            ((imdat::difform($model->id)) and !imdat::difformonaydurumu($model->id) )
                            ?                                 
                                Html::a('Tanımlanan Aksiyon', ['difform', 'i'=>$model->id, 'a'=>0] ,['class'=>'btn btn-warning btn-xs' ])
                            : 
                                null;
                    },
                    'onaylidifform' => function ($url, $model, $key) {  //kurs başvuru onaylanmış ve kesin kayıt yapılmamışsa
                        return  
                            (imdat::difform($model->id) and imdat::difformonaydurumu($model->id) )
                            ?                                 
                                Html::a('Onaylı Dif Formu', ['difform', 'i'=>$model->id, 'a'=>1] ,['class'=>'btn btn-success btn-xs' ])
                            : 
                                null;
                    }, 
                ],

            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>

