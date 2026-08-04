<?php

use yii\helpers\Html;
use yii\grid\GridView;

use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;

use yii\helpers\bgys;
/* @var $this yii\web\View */
/* @var $searchModel app\models\FirmabilgiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tedarikçiler';
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
<div class="firmabilgi-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::button('Tedarikçi Ekle', ['value' => Url::to(['bgysfirmabilgi/create']),'class' => 'btn btn-success btn-lg modalButton2']) ?>
    </p>

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
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'firmaadi',
            'yetkilikisi',
            'telefon',
            [
                'attribute'=>'tedarik_tipi',
                'format'=>'raw',
                'filter'=>array(1 =>"Hizmet" ,2=>"Malzeme",3=>'Servis',4=>'Yüksek Teknoloji'),
                'value'=>function ($data)
                    {
                        return bgys::tedarikcitipi($data->tedarik_tipi);
                    }
            ],            
            [
                'attribute'=>'faaliyet_alani',
                'format'=>'raw',                
                'value'=>function ($data)
                    {
                        $deger=null;
                        foreach ((array) json_decode($data->faaliyet_alani) as $key => $value) {
                            $deger= $deger.$value."<br>";
                        }
                        return   $deger;
                    }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'İşlemler',
                'headerOptions' => ['style' => 'width:8%'],
                'template' => '{view}{update}{delete}' ,  
                'buttons' => [                                      
                    'view' => function ($url,$model) {
                        return  ( 
                            // Html::a('<span class="glyphicon glyphicon-eye-open">', ['view','id'=>$model->id], ['class' => 'btn btn-success','title'=>"İncele"] )

                            Html::button('<span class="glyphicon glyphicon-eye-open">', ['value' => Url::to(['view','id'=>$model->id]),'class' => 'modalButton4 btn btn-success btn-xs' ,'title'=>"İncele"])                      
                            );
                         },
                    'update' => function ($url,$model) {
                        return  ( 
                            Html::button('<span class="glyphicon glyphicon-pencil">', ['value' => Url::to(['update','id'=>$model->id]),'class' => 'modalButton3 btn btn-warning btn-xs' ,'title'=>"Güncelle"])                         
                            );
                         },
                    'delete' => function ($url,$model) {
                        return  (  
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
                            );
                         },
                ]
            ],
        ],
    ]); ?>

<?php Pjax::end(); ?>

</div>
