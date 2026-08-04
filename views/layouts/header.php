<?php
use yii\helpers\Html;
use app\models\Userbilgi;

/* @var $this \yii\web\View */
/* @var $content string */
?>
<style type="text/css">
     #logo {
        width:75%
    }
    
  @media (max-width : 767px) {
    #logo {
        width:35%
    }

</style>
<header class="main-header">

    <?= Html::a('<span class="logo-mini">'.'<img src="/logo2.png" style="width:75%" >'  .'</span><span class="logo-lg">' . 
        'AsbüBGYS' . '</span>', '/site/dashboard', ['class' => 'logo','target'=>'_blank', 'style'=>"background-color: #772043 !important;"]) ?>

    <nav class="navbar navbar-static-top" role="navigation" style="background-color: #772043 !important;">

        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Aç Kapa</span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">
            <?php if (!Yii::$app->user->isGuest) { ?>
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                     <img src="<?= $directoryAsset ?>/img/avatar04.png" class="user-image" alt="User Image"/>
            
                        <span class="hidden-xs"><?= @Userbilgi::findOne(['kisi_id'=>Yii::$app->user->identity->id])->ad." ".@Userbilgi::findOne(['kisi_id'=>Yii::$app->user->identity->id])->soyad ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header" style="background-color: #772043 !important;">
                            <img src="<?= $directoryAsset ?>/img/avatar04.png" class="img-circle"
                                 alt="User Image"/>
                            <p>
                               <?= @Yii::$app->user->identity->username ?>
                               <small><?= @Userbilgi::findOne(['kisi_id'=>Yii::$app->user->identity->id])->email ?></small>
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <?php if(Yii::$app->params['giristipi']==0){ ?>

                                <div class="col-sm-4">
                                    <?= Html::a('Güncelle', ['userdb/update', 'id' => @Yii::$app->user->identity->id], ['class' => 'btn btn-default btn-xs']) ?>
                                </div>
                                <div class="col-sm-4">
                                    <?= Html::a('Şifre Değiştir', ['userdb/updatepasswd', 'id' => @Yii::$app->user->identity->id], ['class' => 'btn btn-default btn-xs']) ?>
                                </div>                            
                                <div class="col-sm-4">
                                    <?= Html::a(
                                        'Çıkış Yap',
                                        ['/site/logout'],
                                        ['data-method' => 'post', 'class' => 'btn btn-default btn-xs']
                                    ) ?>
                                </div>
                            <?php }else{ ?>
                                <div class="col-sm-6">
                                    <?= Html::a('Bilgi Güncelle', ['userbilgi/update', 'id' => @Yii::$app->user->identity->id], ['class' => 'btn btn-default btn-xs']) ?>
                                </div>
                                <div class="col-sm-6" style="text-align:center">
                                    <?= Html::a(
                                        'Çıkış Yap',
                                        ['/site/logout'],
                                        ['data-method' => 'post', 'class' => 'btn btn-alert btn-xs']
                                    ) ?>
                                </div>
                            <?php } ?>

                        </li>
                    </ul>
                </li>
            <?php } /*else{ ?>
                        <div class="btn btn-success" style="margin-top:2px;margin-right:5px;"> <a href="/login">
                                <span style="color:white;font-size: 150%;">Giriş Yap</span></a> 
                        </div>
            <?php } */?>
            
            </ul>
        </div>
    </nav>
</header>
