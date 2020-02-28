<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\helpers\imdat;
use yii\helpers\bgys;
use yii\helpers\ArrayHelper;
use app\models\Bgysbilgisinifi;
use app\models\Bgyskategori;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BgysvarlikenvanteriSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Varlık Envanteri';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bgysvarlikenvanteri-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
     <p>
        <?= Html::button('Varlık Kaydı', ['value' => Url::to(['/bgysvarlikenvanteri/create']),'class' => 'btn btn-success btn-lg modalButton2']) ?>
        <?php // echo Html::a('Dif Ekle', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

     <?php
    Modal::begin([
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
        'rowOptions'=>function($model){
            if ($model->varlik_degeri ==1)  {
                return ['class'=>'success'];
            }else if ($model->varlik_degeri ==2)  {
                return ['class'=>'info'];
            }else if ($model->varlik_degeri ==3)  {
                return ['class'=>'warning'];
            }else if ($model->varlik_degeri ==4)  {
                return ['class'=>'danger'];
            }
            },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'departman',
            'varlik_adi',                         
            [
                'attribute'=>'bilgi_sinifi',
                'format'=>'raw',
                'filter'=>ArrayHelper::map(Bgysbilgisinifi::find()->all(),'id','adi'),
                'value'=>'bilgiSinifi.adi',
            ],  
            //'lokasyon',
            //'kategori',
            [
                'attribute'=>'kategori',
                'format'=>'raw',
                'filter'=>ArrayHelper::map(Bgyskategori::find()->all(),'id','adi'),
                'value'=>function ($data)
                    {
                        return @Bgyskategori::find()->where(['id'=>$data->kategori])->one()->adi;
                    }
            ],
            //'varlik_sahibi',
            /*[
                'attribute'=>'gizlilik',
                'format'=>'raw',
                'filter'=>array(1 =>"Düşük" ,2=>"Orta",3=>'Yüksek',4=>'Çok Yüksek'),
                'value'=>function ($data)
                    {
                        return bgys::varlikdegeri($data->gizlilik);
                    }
            ],
            [
                'attribute'=>'butunluk',
                'format'=>'raw',
                'filter'=>array(1 =>"Düşük" ,2=>"Orta",3=>'Yüksek',4=>'Çok Yüksek'),
                'value'=>function ($data)
                    {
                        return bgys::varlikdegeri($data->butunluk);
                    }
            ],
            [
                'attribute'=>'erisilebilirlik',
                'format'=>'raw',
                'filter'=>array(1 =>"Düşük" ,2=>"Orta",3=>'Yüksek',4=>'Çok Yüksek'),
                'value'=>function ($data)
                    {
                        return bgys::varlikdegeri($data->erisilebilirlik);
                    }
            ],*/
            [
                'attribute'=>'varlik_degeri',
                'format'=>'raw',
                'filter'=>array(1 =>"Düşük" ,2=>"Orta",3=>'Yüksek',4=>'Çok Yüksek'),
                'value'=>function ($data)
                    {
                        return bgys::varlikdegeri($data->varlik_degeri);
                    }
            ],

            ['class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['style' => 'width:8%'],
                'template' => '{view}{update}{delete}' ,  
                'buttons' => [                                      
                    'view' => function ($url,$model) {
                        return  ( 
                            // Html::a('<span class="glyphicon glyphicon-eye-open">', ['view','id'=>$model->id], ['class' => 'btn btn-success','title'=>"İncele"] )

                            Html::button('<span class="glyphicon glyphicon-eye-open">', ['value' => Url::to(['view','id'=>$model->id]),'class' => 'modalButton4 btn btn-success' ,'title'=>"İncele"])                      
                            );
                         },
                    'update' => function ($url,$model) {
                        return  ( 
                            Html::button('<span class="glyphicon glyphicon-pencil">', ['value' => Url::to(['update','id'=>$model->id]),'class' => 'modalButton3 btn btn-warning' ,'title'=>"Güncelle"])                         
                            );
                         },
                    'delete' => function ($url,$model) {
                        return  (  
                            Html::a('<span class="glyphicon glyphicon-trash"></span>', 
                                                ['delete', 'id'=>$model->id] ,
                                                [   'class' => 'btn btn-danger',
                                                    'data-pjax' => '0',
                                                    'title'=>"Sil",
                                                    'data' => [
                                                        'confirm' => 'Bu kaydın dosyasını silmek istediğinizden emin misiniz?',
                                                        'method' => 'post',
                                                    ]
                                                ])                     
                            );
                         },
                ]
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
