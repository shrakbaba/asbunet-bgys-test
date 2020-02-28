<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Olaykayit */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Olay Kayıt', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="olaykayit-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Güncelle', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Sil', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Bu veriyi silmek istediğinizden emin misiniz?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
           // 'id',
           //'userid',
            [
                'value'=>$model->user->username,
                'label'=>'Kayıt Eden Kullanıcı',
            ],
            //'user.username',
            'konu',
            [
                'attribute' => 'olaytarihi',
                'format' => ['date', 'php:d/m/Y']
            ], 
            [
                'attribute' => 'mudahaletarihi',
                'format' => ['date', 'php:d/m/Y']
            ], 
            'mudahaleeden',
            'yapilanmudahale',
            'sonuc',
            'onlem'
        ],
    ]) ?>
<?php if ($model->belge) {      ?>
                        <span class="btn btn-info col-md-2" onclick="window.open('/uploads/bgys/<?php echo md5("olay")."/".$model->belge ?>')" style="margin: 10px;color:white">Belge</span>
                <?php } ?>
</div>
