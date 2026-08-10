<?php
use yii\helpers\Url;
?>
<aside class="main-sidebar">

    <section class="sidebar">
        <!-- Sidebar user panel -->
        <?php if (!Yii::$app->user->isGuest) { 
            $yol=Yii::getAlias('@webroot').'/user.png';
            //var_dump($directoryAsset);exit;
        ?>
      
       <!-- <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/avatar04.png" class="user-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?php // @Yii::$app->user->identity->ad." ".@Yii::$app->user->identity->soyad ?></p>
            </div>
        </div> -->

        <?php  } ?>

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                    ['visible' => !Yii::$app->user->isGuest,'label' => 'Moduller', 'options' => ['class' => 'header']],
                    ['visible' => Yii::$app->user->can('BGYS_Ekip_Uyesi'),'icon' => 'dashboard','label' => 'Dashboard', 'url' => ['/site/dashboard']],
                    ['visible' => Yii::$app->user->can('BGYS_Ekip_Uyesi'),'icon' => 'database','label' => 'Tanımlamalar',
                        'items' => [
                            ['label' => 'Bilgi Sınıfı', 'url' => ['/bgysbilgisinifi/index']],
                            ['label' => 'Eylem Matrisi', 'url' => ['/bgyseylemmatrisi/index']],
                            ['label' => 'Olasılık', 'url' => ['/bgysolasilik/index']],
                            ['label' => 'Şiddet Tablosu', 'url' => ['/bgyssiddettablosu/index']],
                            ['label' => 'Lokasyonlar', 'url' => ['/bgyslokasyon/index']],
                            ['label' => 'Departmanlar', 'url' => ['/bgysdepartman/index']],
                            ['label' => 'Kategoriler', 'url' => ['/bgyskategori/index']],
                            ['label' => 'Cihaz Turu', 'url' => ['/envcihazturu/index']],
                            ['label' => 'Marka', 'url' => ['/envmarka/index']],
                            ['label' => 'Model', 'url' => ['/envmodel/index']],                            
                            ['label' => 'Bilgiler', 'url' => ['/bgysvarlikenvanteri/bilgiler']]
                        ]
                    ],
                    ['visible' => Yii::$app->user->can('BGYS_Ekip_Uyesi'),'icon' => 'cart-plus','label' => 'Tedarikçi Yönetimi',
                        'items' => [
                            ['label' => 'Tedarikçiler', 'url' => ['/bgysfirmabilgi/index']],
                            ['label' => 'Tedarikçi Değerlendirme', 'url' => ['/bgysfirmadegerlendirme/index']],
                        ]
                    ],
                    ['visible' => Yii::$app->user->can('BGYS_Ekip_Uyesi'),'icon' => 'desktop','label' => 'Cihaz ve Zimmet Yönetimi', 'url' => ['/envcihazliste/index']],
                    ['visible' => Yii::$app->user->can('BGYS_Ekip_Uyesi'),'icon' => 'file','label' => 'Listeler',
                        'items' => [
                            ['visible' => Yii::$app->user->can('BGYS_Ekip_Uyesi'),'icon' => 'file','label' => 'İş Sürekliliği ve Kritik Süreçler', 'url' => ['/bgyskritiksurecler/index']],
                            ['visible' => Yii::$app->user->can('BGYS_Ekip_Uyesi'),'icon' => 'file','label' => 'Dış Kaynaklı Dokümanlar', 'url' => ['/bgysdiskaynaklidokuman/index']],
                            ['visible' => Yii::$app->user->can('BGYS_Ekip_Uyesi'),'icon' => 'file','label' => 'Özel İlgi Grup ve Otoriteler', 'url' => ['/bgysilgigrup/index']],
                            ['visible' => Yii::$app->user->can('BGYS_Ekip_Uyesi'),'icon' => 'file','label' => 'İzleme Ölçme ', 'url' => ['/bgysizlemeolcme/index']],
                            ['visible' => Yii::$app->user->can('BGYS_Ekip_Uyesi'),'icon' => 'file','label' => 'Yedekleme Listesi', 'url' => ['/bgysyedeklemelistesi/index']],
                        ]
                    ],
                    ['visible' => Yii::$app->user->can('BGYS_Ekip_Uyesi'),'icon' => 'recycle','label' => 'Varlık Yönetimi',
                        'items' => [
                            ['label' => 'BGYS Varlık Envanteri', 'url' => ['/bgysvarlikenvanteri/index']],
                        ]
                    ], 
                    ['visible' => Yii::$app->user->can('BGYS_Ekip_Uyesi'),'icon' => 'recycle','label' => 'Risk Yönetimi',
                        'items' => [
                            ['label' => 'Risk', 'url' => ['/bgysrisk/index']],
                            ['visible' => Yii::$app->user->can('BGYS_Ekip_Lideri'),'label' => 'Risk Kabul', 'url' => ['/bgysrisk/riskkabuller']],
                            ['visible' => Yii::$app->user->can('BGYS_Ekip_Uyesi'),'icon' => 'eyedropper','label' => 'Düzeltici Faaliyetler', 'url' => ['/bgysdiftalep/index'],
                        ]
                    ],
                    ],
                    ['visible' => Yii::$app->user->can('BGYS_Ekip_Uyesi'),'icon' => 'anchor','label' => 'Olay Yönetimi',
                        'items' => [
                            ['label' => 'Olay Kayıt', 'url' => ['/bgysolaykayit/index']],
                        ]
                    ],                     
                    ['visible' => Yii::$app->user->can('BGYS_Ekip_Uyesi'),'icon' => 'commenting-o','label' => 'Eğitim Yönetimi',
                        'items' => [
                            ['label' => 'Farkındalık Eğitimi ', 'url' => ['/bgysfarkindalikquiz/egitim']],
                            ['visible' => Yii::$app->user->can('BGYS_Super_Admin'),'label' => 'Farkındalık Eğitimi Sonuçları', 'url' => ['/bgysfarkindalikquiz/index']],
                        ]
                    ],     
                    ['visible' => Yii::$app->user->can('BGYS_Ekip_Uyesi'),'icon' => 'cogs','label' => 'Bakım Yönetimi', 'url' => ['/bgyscihazbakim/index']],              
                    ['visible' => Yii::$app->user->can('BGYS_Ekip_Uyesi'),'icon' => 'paper-plane-o','label' => 'Hatırlatmalar',
                        'items' => [
                            ['label' => 'Mail Hatırlatma', 'url' => ['/mailkapat/index']],
                            ['label' => 'Sunucu Hatırlatma', 'url' => ['/yenihostbildir/index']],
                        ]
                    ],            
                   // ['visible' => Yii::$app->user->can('BGYS_Yonetim_Temsilcisi'),'icon' => 'male','label' => 'Kullanıcılar','url' => ['/userdb/index']   ],
                    ['visible' => Yii::$app->user->can('BGYS_Super_Admin'),'icon' => 'puzzle-piece','label' => 'Yetkilendirme',
                        'items' => [
                            ['visible' => Yii::$app->user->can('BGYS_Super_Admin'),'label' => 'Kullanıcı Bilgileri', 'url' => ['/userbilgi/index']],
                            ['visible' => Yii::$app->user->can('BGYS_Super_Admin'),'label' => 'Rol Atama', 'url' => ['/authassignment/index']],
                            ['visible' => Yii::$app->user->can('BGYS_Super_Admin'),'label' => 'Roller', 'url' => ['/authitem/index']],
                            ['visible' => Yii::$app->user->can('BGYS_Super_Admin'),'label' => 'Rol İlişkileri', 'url' => ['/authitemchild/index']],
                            ['visible' => Yii::$app->user->can('BGYS_Super_Admin'),'label' => 'Hareket Kayıtları', 'url' => ['/bgyslogs/index']]
                        ]
                    ],            
                   // ['visible' => Yii::$app->user->can('BGYS_Yonetim_Temsilcisi'),'icon' => 'male','label' => 'Hareket Kayıtları','url' => ['/bgyslogs/index']   ],
                    
                    //['visible' => Yii::$app->user->can('BGYS_Super_Admin'),'label' => 'Gii', 'icon' => 'puzzle-piece', 'url' => ['/gii']],
                    //['visible' => Yii::$app->user->can('BGYS_Super_Admin'),'label' => 'Debug', 'icon' => 'puzzle-piece', 'url' => ['/debug']],
                    /*['visible' => Yii::$app->user->can('BGYS_Super_Admin'),'label' => 'Test Menus','icon' => 'puzzle-piece','url' => '#',
                        'items' => [
                            ['label' => 'asdsadadasd', 'icon' => 'file-code-o', 'url' => ['/gii'],],
                            ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug'],],
                            [
                                'label' => 'Level One','icon' => 'circle-o','url' => '#',
                                'items' => [
                                    ['label' => 'Level Two', 'icon' => 'circle-o', 'url' => '#',],
                                    [
                                        'label' => 'Level Two','icon' => 'circle-o','url' => '#',
                                        'items' => [
                                            ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
                                            ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],*/
                    ['label' => 'Giriş Yap', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                    //['label' => 'Şifremi Unuttum', 'url' => ['userdb/forgot'], 'visible' => Yii::$app->user->isGuest],
                ],
            ]
        ) ?>

    </section>

</aside>
