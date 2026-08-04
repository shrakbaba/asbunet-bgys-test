<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

use app\models\Userbilgi;

AppAsset::register($this);
?>
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>

</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        //'brandLabel' => Html::img('@web/asbu2.png', ['alt'=>Yii::$app->name,'class'=>"pull-left"]),
        //'brandLabel' => '<img src="@web/asbu2.png" class="pull-left"/>Car Management System',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
            if (Yii::$app->user->isGuest ) {
              //  $menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
                $menuItems[] = ['label' => 'Giriş', 'url' => ['/site/login']];                
            } 
            else {  
                $menuItems[] = ['label' => 'Dashboard', 'url' => ['/site/dashboard']]; 
                    if (Yii::$app->user->can('BGYS_ekip')) {
                        $menuItems[] = ['label' => 'Tedarikçi','items' => [
                            ['label' => 'Tedarikçiler', 'url' => ['/bgysfirmabilgi/index']],
                            ['label' => 'Tedarikçi Değerlendirme', 'url' => ['/bgysfirmadegerlendirme/index']],
                                ]
                            ];
                        $menuItems[] = ['label' => 'Kayıtlar','items' => [
                            ['label' => 'Olay Kayıt', 'url' => ['/bgysolaykayit/index']],
                            ['label' => 'Dif Talep', 'url' => ['/bgysdiftalep/index']],
                            ]];    
                        $menuItems[] = ['label' => 'Varlık ve Risk','items' => [
                            ['label' => 'Varlık', 'url' => ['/bgysvarlikenvanteri/index']],
                            ['label' => 'Risk', 'url' => ['/bgysrisk/index']],
                            ]];
                        $menuItems[] = ['label' => 'Envanter', 'url' => ['/envcihazliste/index']] ;
                        $menuItems[] = ['label' => 'Tanımlamalar','items' => [
                            ['label' => 'Kategoriler', 'url' => ['/bgyskategori/index']],
                            ['label' => 'Şiddet Tablosu', 'url' => ['/bgyssiddettablosu/index']],
                            ['label' => 'Olasılık', 'url' => ['/bgysolasilik/index']],
                            ['label' => 'Eylem Matrisi', 'url' => ['/bgyseylemmatrisi/index']],
                            ['label' => 'Bilgi Sınıfı', 'url' => ['/bgysbilgisinifi/index']],
                            ['label' => 'Departmanlar', 'url' => ['/bgysdepartman/index']],
                            ['label' => 'Lokasyonlar', 'url' => ['/bgyslokasyon/index']],
                            ['label' => 'Mail Hatırlatma', 'url' => ['/mailkapat/index']],
                            ['label' => 'Bilgiler', 'url' => ['/bgysvarlikenvanteri/bilgiler']],
                            ]
                        ];  
                    }
                    if (Yii::$app->user->can('BGYS_Super_Admin')) {

                        $menuItems[] = ['label' => 'RBAC','items' => [
                            ['label' => 'Nesneler', 'url' => ['/authitem/index']],
                            ['label' => 'Nesne Gruplandırma', 'url' => ['/authitemchild/index']],
                            ['label' => 'Rol Atama', 'url' => ['/authassignment/index']]
                            ]
                        ];
                        $menuItems[] = ['label' => 'Kullanıcılar','url' => ['/userdb/index']   ];
                    }

                $menuItems[] = [
                    'label' => 'Çıkış (' . Yii::$app->user->identity->username . ')',
                    'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post']
                ];
            }


      echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();

  
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p style="text-align: center">&copy; Bilgi İşlem Daire Başkanlığı <?= date('Y') ?></p>

    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
