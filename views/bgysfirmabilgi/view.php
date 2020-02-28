<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\bgys;

/* @var $this yii\web\View */
/* @var $model app\models\Firmabilgi */
?>
<div class="firmabilgi-view">


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'firmaadi',
            'yetkilikisi',
            'telefon',
            'faaliyet_alani',                 
            [
                'attribute'=>'tedarik_tipi',
                'value'=>function ($data)
                    {
                        return bgys::tedarikcitipi($data->tedarik_tipi);
                    }
            ],
            'mail',
            //'belge',  
        ],
    ]) ?>
    <?php if ($model->belge) {      ?>
                        <span class="btn btn-info col-md-2" onclick="window.open('/uploads/bgys/<?php echo md5("firma")."/".$model->belge ?>')" style="margin: 10px;color:white">Belge</span>
                <?php } ?>

</div>
