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
            [
                'attribute'=>'faaliyet_alani',
                'format'=>'raw',
                'value'=>function ($data)
                    {
                        $degerler = [];
                        foreach ((array) json_decode($data->faaliyet_alani) as $value) {
                            if (trim((string)$value) !== '') {
                                $degerler[] = Html::encode($value);
                            }
                        }

                        return implode('<br>', $degerler);
                    }
            ],
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
                        <?= Html::a(Html::encode($model->belge), ['belge', 'id' => $model->id], ['target' => '_blank', 'data-pjax' => '0', 'style' => 'margin: 10px;color:#337ab7;text-decoration:underline;display:inline-block;']) ?>
                <?php } ?>

</div>
