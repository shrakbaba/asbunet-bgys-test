<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Userbilgi;
use app\models\Bgysizlemesonucu;
use yii\helpers\imdat;

/* @var $this yii\web\View */
/* @var $model app\models\Bgysyedeklemelistesi */
\yii\web\YiiAsset::register($this);
?>
<div class="bgysyedeklemelistesi-view">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'yedekalinacak',
            [
                'attribute'=>'sorumlu',
                'value'=>function ($data)
                    {
                        return Userbilgi::findOne(['kisi_id'=>$data->sorumlu])->ad.' '.@Userbilgi::findOne(['kisi_id'=>$data->sorumlu])->soyad.' / '.@$data->sorumlu0->username ;
                    },
                'label'=>'Sorumlu',
            ],
            [
                'attribute'=>'yedeklemesekli',               
                'value'=>function ($data)
                    {
                        return 
                            $data->yedeklemesekli==1 ? "Full-Incremental"
                            : ( $data->yedeklemesekli==2 ?  "Differantial" 
                                : ( $data->yedeklemesekli==3 ? "Full"
                                    :  ""                                    
                                ) 
                            )
                        ; 
                    }
            ],
            'yedekleme_yontemi',
            [
                'attribute'=>'periyodu',
                'value'=>function ($data)
                    {
                        return 

                            $data->periyodu==1 ? "2 Saatlik"
                                : ( $data->periyodu==2 ? "Günlük"
                                    :( $data->periyodu==3 ? "Haftalık" 
                                            :( $data->periyodu==4 ? "Aylık" 
                                                :( $data->periyodu==5 ? "Tek yedek" 
                                                    : ""
                                                )
                                            )
                                        )
                                    )
                        ; 
                    }
            ],
            'yedeklemeyeri',
            [//array(1 =>"08:00-17:00",2 =>"Haftaiçi günler",3 =>"Haftanın her günü",4 =>"Her ayın ilk günü",5 =>"Her çarşamba",6 =>"Tek yedek")
                'attribute'=>'yedeklemezamani',
                'value'=>function ($data)
                    {
                        return 
                            $data->yedeklemezamani==1 ? "08:00-17:00"
                            : ( $data->yedeklemezamani==2 ?  "Haftaiçi günler" 
                                : ( $data->yedeklemezamani==3 ? "Haftanın her günü"
                                    :( $data->yedeklemezamani==4 ? "Her ayın ilk günü" 
                                        :( $data->yedeklemezamani==5 ? "Her çarşamba" 
                                            :( $data->yedeklemezamani==6 ? "Tek yedek" 
                                                :""
                                            )
                                        )
                                    )
                                ) 
                            )
                        ; 
                    }
            ],
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
