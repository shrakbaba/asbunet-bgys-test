

<?php

use miloschuman\highcharts\Highcharts;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\web\JsExpression;

$this->title = 'Durum';
$riskAnaliziLinkleri = [
    'Düşük Risk' => Url::to(['/bgysrisk/index', 'BgysriskSearch' => ['risk_seviyesi' => 'dusuk']]),
    'Orta Risk' => Url::to(['/bgysrisk/index', 'BgysriskSearch' => ['risk_seviyesi' => 'orta']]),
    'Yüksek Risk' => Url::to(['/bgysrisk/index', 'BgysriskSearch' => ['risk_seviyesi' => 'yuksek']]),
];
$riskAnaliziData = [];
foreach ((array)$data['basvurusonuclari'] as $riskDilimi) {
    $ad = $riskDilimi[0] ?? '';
    $riskAnaliziData[] = [
        'name' => $ad,
        'y' => (int)($riskDilimi[1] ?? 0),
        'url' => $riskAnaliziLinkleri[$ad] ?? Url::to(['/bgysrisk/index']),
    ];
}
$riskDurumuLinkleri = [
    'Risk Azalmış' => Url::to(['/bgysrisk/index', 'BgysriskSearch' => ['ozetdurum' => 1]]),
    'Risk Artmış' => Url::to(['/bgysrisk/index', 'BgysriskSearch' => ['ozetdurum' => 2]]),
    'Değişim Yok' => Url::to(['/bgysrisk/index', 'BgysriskSearch' => ['ozetdurum' => 3]]),
    'Risk Kabul' => Url::to(['/bgysrisk/index', 'BgysriskSearch' => ['ozetdurum' => 4]]),
];
$riskDurumuData = [];
foreach ((array)$data['riskdegisim'] as $riskDurumu) {
    $ad = $riskDurumu[0] ?? '';
    $riskDurumuData[] = [
        'name' => $ad,
        'y' => (int)($riskDurumu[1] ?? 0),
        'url' => $riskDurumuLinkleri[$ad] ?? Url::to(['/bgysrisk/index']),
    ];
}
?>
<!--<link rel="stylesheet" href="<?php  //Yii::$app->request->baseUrl ?>/lte_css/bootstrap.min.css">-->
<!-- Font Awesome -->
<!--<link rel="stylesheet" href="<?php //Yii::$app->request->baseUrl ?>/lte_css/font-awesome.min.css">-->
<!-- Ionicons -->
<!--<link rel="stylesheet" href="<?php //Yii::$app->request->baseUrl ?>/lte_css/ionicons.min.css">-->
<!-- Theme style -->
<!--<link rel="stylesheet" href="<?php //Yii::$app->request->baseUrl ?>/lte_css/AdminLTE.min.css">-->
  <!-- AdminLTE Skins. Choose a skin from the css/skins
    folder instead of downloading all of them to reduce the load. -->
<!--<link rel="stylesheet" href="<?php //Yii::$app->request->baseUrl ?>/lte_css/_all-skins.min.css">
<link rel="stylesheet" href="<?php //Yii::$app->request->baseUrl ?>/lte_css/skin-red-light.min.css">-->

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

<!-- Google Font -->
<link rel="stylesheet"
href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

 <?php Pjax::begin(); ?>
<style type="text/css">
  .highcharts-credits{
    display: none;
  }
  .kalin{
    font-weight: bold;
  }
  .ana{
    font-size: 125%;
  }
  .box .box-title,
  .box .box-body h4,
  .info-box .info-box-text,
  .progress-group .progress-text {
    font-weight: 700;
  }
  .box.box-danger .box-title {
    font-weight: 800;
  }
</style>
<?php
$this->registerJs(<<<JS
$(document).off('click.egitimIstatistikAc', '#egitimIstatistikAc').on('click.egitimIstatistikAc', '#egitimIstatistikAc', function() {
    $('#egitimIstatistikModal').modal('show');
});
JS
);
?>

  <div class="row col-lg-12">
 
    <div class="col-lg-3">
      <div class="info-box">
        <span class="info-box-icon bg-red"><i class="fa fa-recycle"></i></span>

        <div class="info-box-content" style="padding-bottom: 0px;">
         <!-- <span class="info-box-text">Varlık</span>
          <span class="info-box-number"><a href="/bgysvarlikenvanteri/index"><?= $data['varlik'] ?> </a> </span> -->
          <span style="display: block;">
              <span class="ana">Varlık </span><span class="kalin ana"><a href="/bgysvarlikenvanteri/index"><?= $data['varlik'] ?> </a></span>
            </span>
          <?php 
          foreach ($data['varlikkategori'] as $key => $value) { ?>
              <span style="display: block;">
                <span ><?= $value['adi'] ?></span><span class="kalin"><?= " ".$value['value'] ?></span>
            </span>
          <?php } ?>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <div class="col-lg-3">
      <div class="info-box">
        <span class="info-box-icon bg-teal"><i class="fa fa-desktop"></i></span>
        <div class="info-box-content" style="padding-bottom: 0px;">
          <span style="display: block;">
              <span class="ana">Cihaz </span><span class="kalin ana"><a href="/envcihazliste/index"><?= $data['cihazliste'] ?> </a></span>
            </span>
          <?php 
          foreach ($data['cihazturu'] as $key => $value) { ?>
              <span style="display: block;">
                <span ><?= $value['cihaz_turu'] ?></span><span class="kalin"><?= " ".$value['value'] ?></span>
            </span>
          <?php } ?>

        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div> 
    <div class="col-lg-3">
      <div class="info-box">
        <span class="info-box-icon bg-orange"><i class="fa fa-cart-plus"></i></span>

        <div class="info-box-content" style="padding-bottom: 0px;">
          <!--<span class="info-box-text">Tedarikçi</span>
          <span class="info-box-number"><a href="/bgysfirmabilgi/index"><?= $data['tedarikci'] ?></a> <small>Firma</small> </span>
          <span class="info-box-number"><a href="/bgysfirmabilgi/index"><?= $data['tedarikcipersonel'] ?></a><small> Personel</small>  </span>-->

           <span style="display: block;">
              <span class="ana">Tedarikçi </span><span class="kalin ana"><a href="/bgysfirmabilgi/index"><?= $data['tedarikci'] ?> </a></span>
            </span>
          <?php 
          foreach ($data['tedarikcitipi'] as $key => $value) { ?>
              <span style="display: block;">
                <span ><?= $value['tip'] ?></span><span class="kalin"><?= " ".$value['value'] ?></span>
            </span>
          <?php } ?>

        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div> 
    <div class="col-lg-3">
      <div class="info-box">
        <span class="info-box-icon bg-green"><i class="fa fa-anchor"></i></span>

        <div class="info-box-content" style="padding-bottom: 0px;">
          <span class="info-box-text">Olay</span>
          <span class="info-box-number"><a href="/bgysolaykayit/index"><?= $data['olaykayit'] ?></a> <small></small> </span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div> 
  </div> 

  <div class="row col-lg-12">
    <div class="col-lg-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Farkındalık Eğitim Raporu</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-3">
              <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-graduation-cap"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Aktif Eğitim</span>
                  <span class="info-box-number"><?= (int)$data['egitimSayisi'] ?></span>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="info-box">
                <span class="info-box-icon bg-teal"><i class="fa fa-sign-in"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Eğitime Giriş</span>
                  <span class="info-box-number"><?= (int)$data['egitimGirisSayisi'] ?></span>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-check-square-o"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Çözülen Quiz</span>
                  <span class="info-box-number"><?= (int)$data['egitimQuizSayisi'] ?></span>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="info-box">
                <span class="info-box-icon bg-orange"><i class="fa fa-bar-chart"></i></span>
                <div class="info-box-content" id="egitimIstatistikAc" style="cursor:pointer;" title="İstatistik detayını görüntüle">
                  <span class="info-box-text">Ortalama Sonuç</span>
                  <span class="info-box-number"><?= $data['egitimOrtalamaPuan'] !== null ? round($data['egitimOrtalamaPuan'], 2) : 0 ?></span>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <h4>Eğitim Bazında Özet</h4>
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Eğitim</th>
                    <th>Giriş</th>
                    <th>Çözen</th>
                    <th>Ortalama</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($data['egitimIstatistikleri'])) { ?>
                    <?php foreach ($data['egitimIstatistikleri'] as $egitimIstatistik) { ?>
                      <tr>
                        <td><?= Html::encode($egitimIstatistik['baslik']) ?></td>
                        <td><?= (int)$egitimIstatistik['giris'] ?></td>
                        <td><?= (int)$egitimIstatistik['cozulen'] ?></td>
                        <td><?= $egitimIstatistik['ortalama'] !== null ? round($egitimIstatistik['ortalama'], 2) : 0 ?></td>
                      </tr>
                    <?php } ?>
                  <?php } else { ?>
                    <tr><td colspan="4">Eğitim kaydı bulunmuyor.</td></tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
            <div class="col-md-6">
              <h4>Son Quiz Sonuçları</h4>
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Kişi</th>
                    <th>Eğitim</th>
                    <th>Puan</th>
                    <th>Tarih</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($data['sonEgitimSonuclari'])) { ?>
                    <?php foreach ($data['sonEgitimSonuclari'] as $egitimSonucu) { ?>
                      <tr>
                        <td><?= Html::encode($egitimSonucu->cevaplayanAdSoyad) ?></td>
                        <td><?= Html::encode($egitimSonucu->egitim ? $egitimSonucu->egitim->baslik : '(Veri Yok)') ?></td>
                        <td><?= Html::encode($egitimSonucu->puan) ?></td>
                        <td><?= Yii::$app->formatter->asDatetime($egitimSonucu->cevaplamatarihi, 'php:d/m/Y H:i') ?></td>
                      </tr>
                    <?php } ?>
                  <?php } else { ?>
                    <tr><td colspan="4">Quiz sonucu bulunmuyor.</td></tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php
  Modal::begin([
      'id' => 'egitimIstatistikModal',
      'size' => 'modal-lg',
      'header' => '<h3>Farkındalık Eğitim İstatistikleri</h3>',
  ]);
  ?>
    <h4>Eğitim Bazında</h4>
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>Eğitim</th>
          <th>Giriş</th>
          <th>Çözen</th>
          <th>Ortalama</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ((array)$data['egitimIstatistikleri'] as $egitimIstatistik) { ?>
          <tr>
            <td><?= Html::encode($egitimIstatistik['baslik']) ?></td>
            <td><?= (int)$egitimIstatistik['giris'] ?></td>
            <td><?= (int)$egitimIstatistik['cozulen'] ?></td>
            <td><?= $egitimIstatistik['ortalama'] !== null ? round($egitimIstatistik['ortalama'], 2) : 0 ?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>

    <h4>Birim Bazında</h4>
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>Birim</th>
          <th>Çözen</th>
          <th>Ortalama</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ((array)$data['egitimBirimIstatistikleri'] as $birimIstatistik) { ?>
          <tr>
            <td><?= Html::encode($birimIstatistik['birim']) ?></td>
            <td><?= (int)$birimIstatistik['cozulen'] ?></td>
            <td><?= $birimIstatistik['ortalama'] !== null ? round($birimIstatistik['ortalama'], 2) : 0 ?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>

    <h4>Kişi Bazında Son Sonuçlar</h4>
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>Kişi</th>
          <th>Eğitim</th>
          <th>Puan</th>
          <th>Tarih</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ((array)$data['sonEgitimSonuclari'] as $egitimSonucu) { ?>
          <tr>
            <td><?= Html::encode($egitimSonucu->cevaplayanAdSoyad) ?></td>
            <td><?= Html::encode($egitimSonucu->egitim ? $egitimSonucu->egitim->baslik : '(Veri Yok)') ?></td>
            <td><?= Html::encode($egitimSonucu->puan) ?></td>
            <td><?= Yii::$app->formatter->asDatetime($egitimSonucu->cevaplamatarihi, 'php:d/m/Y H:i') ?></td>
          </tr>
        <?php } ?>
      </tbody>
  </table>
  <?php Modal::end(); ?>
  
  <div class="row col-lg-12">
    <div class="col-lg-6">
      <div class="box box-info">
        <div class="box-header with-border">
          <h3 class="box-title">Hatırlatmalar</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="table-responsive">
            <table class="table no-margin">
              <thead>
                <tr>
                  <th>Link</th>
                  <th>Olay</th>
                  <!--<th>Durum</th>-->
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($data['hatirlatmalar'])) { ?>
                  <?php foreach ($data['hatirlatmalar'] as $hatirlatma) { ?>
                    <tr>
                      <td>
                        <?= Html::a('Güncelle', ['/bgysolaykayit/update', 'id' => $hatirlatma->id], ['class' => 'btn btn-warning btn-xs', 'data-pjax' => '0']) ?>
                      </td>
                      <td>
                        <?= Html::encode($hatirlatma->konu) ?>
                        <?php if ($hatirlatma->mudahaletarihi) { ?>
                          <small>(Müdahale tarihi: <?= Yii::$app->formatter->asDate($hatirlatma->mudahaletarihi, 'php:d/m/Y') ?>)</small>
                        <?php } ?>
                      </td>
                    </tr>
                  <?php } ?>
                <?php } else { ?>
                  <tr>
                    <td colspan="2">Gösterilecek hatırlatma bulunmuyor.</td>
                  </tr>
                <?php } ?>
                <?php /*
                  $yy=$data['degerlendirilmeyenler'];
                  // var_dump($yy);exit;
                  if ($yy) {
                  foreach ($yy as $key => $value) 
                    { ?>
                      <tr>
                        <td>
                          <?php echo Html::button($value, ['value' => Url::to(['bgysfirmadegerlendirme/create']),'class' => 'modalButton3 btn btn-warning btn-sm']) ?>                        
                        </td>
                        <td>Değerlendirilmemiş Tedarikçi</td>
                        <!--<td><span class="label label-success">Görüldü</span></span></td>-->
                      </tr>
                    <?php }  } */ ?>
              </tbody>
            </table>
          </div>
          <!-- /.table-responsive -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer clearfix">
          <!--<a href="javascript:void(0)" class="btn btn-sm btn-info btn-flat pull-left">Place New Order</a>
          <a href="javascript:void(0)" class="btn btn-sm btn-default btn-flat pull-right">View All Orders</a>-->
        </div>
        <!-- /.box-footer -->
      </div>
    </div>

    <div class="col-lg-6">
      <div class="box box-info">
        <div class="box-header with-border">
          <h3 class="box-title">Oranlar</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
          </div>
        </div>
          <div class="box-body">
            <div class="progress-group">
                        <span class="progress-text">Kapatılan Dif Talep / Açılan Dif Talep</span>
                        <span class="progress-number"><b><?= $data['diftalepkapali'] ?> </b>/<?= $data['diftalep'] ?> </span>
                        <?php if ($data['diftalep']!=0)    
                            $a=(($data['diftalepkapali']/$data['diftalep'])*100)."%";
                            else $a=0;
                        ?>
                        <div class="progress sm">
                          <div class="progress-bar progress-bar-aqua" style="width: <?= $a ?>"></div>
                        </div>
            </div>
            <!-- /.progress-group -->
            <div class="progress-group">
                        <span class="progress-text">Onaylı Dif Takip / Açılan Dif Takip</span>
                        <span class="progress-number"><b><?= $data['diftakiponayli'] ?> </b>/<?= $data['diftakip'] ?> </span>

                        <?php 
                         if ($data['diftakip']!=0)    
                            $a=(($data['diftakiponayli']/$data['diftakip'])*100)."%";
                            else $a=0;
                        ?>
                        <div class="progress sm">
                          <div class="progress-bar progress-bar-red" style="width: <?= $a ?>"></div>
                        </div>
            </div>
            <!-- /.progress-group -->
            <div class="progress-group">
                        <span class="progress-text">Risk Kabul / Toplam Risk</span>
                        <span class="progress-number"><b><?= $data['riskkabul'] ?></b>/<?= $data['risk'] ?></span>
                         <?php 
                         if ($data['risk']!=0)    
                            $a=(($data['riskkabul']/$data['risk'])*100)."%";
                            else $a=0;
                        ?>
                        
                        <div class="progress sm">
                          <div class="progress-bar progress-bar-green" style="width: <?= $a ?>"></div>
                        </div>
            </div>
            <!-- /.progress-group -->
            <div class="progress-group">
                        <span class="progress-text">Kritik Varlık / Toplam Varlık</span>
                        <span class="progress-number"><b><?= $data['varlikkritik'] ?></b>/<?= $data['varlik'] ?></span>

                         <?php 
                         if ($data['varlik']!=0)    
                            $a=(($data['varlikkritik']/$data['varlik'])*100)."%";
                            else $a=0;
                        ?>
                        <div class="progress sm">
                          <div class="progress-bar progress-bar-yellow" style="width: <?= $a ?>"></div>
                        </div>
            </div>
            <div class="progress-group">
                        <span class="progress-text">Onaylanmış Tedarikçi Değerlendirme / Tedarikçi Değerlendirme</span>
                        <span class="progress-number"><b><?= $data['degerlendirmeonayli'] ?></b>/<?= $data['degerlendirme'] ?></span>

                         <?php 
                         if ($data['degerlendirme']!=0)    
                            $a=(($data['degerlendirmeonayli']/$data['degerlendirme'])*100)."%";
                            else $a=0;
                        ?>
                        
                        <div class="progress sm">
                          <div class="progress-bar progress-bar-brown" style="width: <?= $a ?>"></div>
                        </div>
            </div>
            <!-- /.progress-group -->
          </div> 
          <div class="box-footer clearfix">
            <!--<a href="javascript:void(0)" class="btn btn-sm btn-info btn-flat pull-left">Place New Order</a>
            <a href="javascript:void(0)" class="btn btn-sm btn-default btn-flat pull-right">View All Orders</a>-->
          </div>
      </div>  
    </div>    
  </div>
  <div class="row col-lg-12">
    <div class="col-lg-6">
      <div class="box box-danger">
        <div class="box-header with-border">
            <h3 class="box-title">Risk Analizi</h3>
            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
              </button>
              <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
        </div>
        <div class="box-body">
            <?php 
            echo Highcharts::widget([
              'scripts' => ['highcharts-3d'],
              'options' => [
	                "chart" => [
	                  "type" => "pie",
	                  "height" => 300,
	                  "spacingTop" => 0,
	                  "spacingBottom" => 0,
	                  "options3d" => [
                    "enabled" => true,
                    "alpha" => 35
                  ]
                ],
                "title" => [
                  "text" => ""
                ],
                "subtitle" => [
                  "text" => "Risk Değerine Göre"
                ],
                "plotOptions" => [
	                  "pie" => [
	                    "size"=>"50%",
	                    "center" => ["50%", "45%"],
	                    "innerSize" => 50,
	                    "depth" => 25,
	                    'allowPointSelect'=> true,
	                  ],
	                  "series" => [
	                    "cursor" => "pointer",
	                    "point" => [
	                      "events" => [
	                        "click" => new JsExpression('function () { if (this.options.url) { window.location.href = this.options.url; } }')
	                      ]
	                    ],
	                    "dataLabels" => [
	                      "enabled" => true,
	                      "format" => "{point.name}: {point.y}"
	                    ]
	                  ]
                ],
                "series" => [
                  [
	                    "name" => "Karar Sayıları",
	                    "colorByPoint" => true,
	                    "data" => $riskAnaliziData,
	                  ]
	                ],
	              ]
	            ]);
	            ?>
	            <small>Dilimlere tıklayınca ilgili risk kayıtları listelenir.</small>
	        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    </div>

    <div class="col-lg-6">
             <div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title">Risk İyileştirmeleri</h3>

                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div>
                <div class="box-body">
                  <?php 
                  echo Highcharts::widget([
                    'scripts' => ['highcharts-3d'],
                    'options' => [
	                      "chart" => [
	                        "type" => "pie",
	                        "height" => 300,
	                        "spacingTop" => 0,
	                        "spacingBottom" => 0,
	                        "options3d" => [
                          "enabled" => true,
                          "alpha" => 35
                        ]
                      ],
                      "title" => [
                        "text" => " "
                      ],
                      "subtitle" => [
                        "text" => "Risk Özet Durumuna Göre"
                      ],
                      "plotOptions" => [
	                        "pie" => [
	                          "size"=>"50%",
	                          "center" => ["50%", "45%"],
	                          "innerSize" => 50,
	                        "depth" => 25,
	                        'allowPointSelect'=> true,
	                      ],
	                      "series" => [
	                        "cursor" => "pointer",
	                        "point" => [
	                          "events" => [
	                            "click" => new JsExpression('function () { if (this.options.url) { window.location.href = this.options.url; } }')
	                          ]
	                        ],
	                        "dataLabels" => [
	                          "enabled" => true,
	                          "format" => "{point.name}: {point.y}"
	                        ]
	                      ]
                      ],
                      "series" => [
                        [
	                          "name" => "Karar Sayıları",
	                          "colorByPoint" => true,
	                          "data" => $riskDurumuData,
	                        ]
	                      ],
	                    ]
	                  ]);
	                  ?>
	                  <small>Risk İyileştirmeleri, önceki ve sonraki risk değerleri karşılaştırılarak oluşur. Dilime tıklayınca ilgili riskler listelenir.</small>
	                </div>
                <!-- /.box-body -->
              </div>
              <!-- /.box -->    
    </div>

  </div>

    <div class="row col-lg-12">
             <div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title">Risk Haritası</h3>
                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div>
                <div class="box-body">
                  <?php 
                  //echo "<pre>";print_r($data['riskhatirasi']);
                  echo Highcharts::widget([
                    'scripts' => ['highcharts-more'],
                    'options' => [
                        "chart" => [
                          "type" => "packedbubble",
                          'height'=>'25%'
                        ],
                        "title" => [
                          "text" => "Risk Haritası "
                        ],
                        'tooltip'=> [
                            'useHTML'=> true,
                            'pointFormat'=> '<b>{point.name}:</b><br> 
                                  <hr>
                                  <span style="font-size:13px">Risk Değeri: </span>{point.value}<br>
                                  <span style="font-size:13px">Olasılık: </span>{point.olasilik}<br>
                                  <span style="font-size:13px">GBE: </span>{point.gbe}<br>
                                  <span style="font-size:13px">Varlık Değeri: </span>{point.varlik}'
                        ],
                        'plotOptions'=> [
                          'packedbubble'=> [
                              'minSize'=> '60%',
                              'maxSize'=> '420%',
                              'zMin'=> 0,
                              'zMax'=> 1000,
                              'layoutAlgorithm'=> [
                                  'splitSeries'=> false,
                                  'gravitationalConstant'=> 0.02
                              ],
                              'dataLabels'=> [
                                  'enabled'=> true,
                                  'format'=> '{point.name}',
                                  'filter'=> [
                                      'property'=> 'y',
                                      'operator'=> '>',
                                      'value'=> 250
                                  ],
                                  'style'=> [
                                      'color'=> 'black',
                                      'textOutline'=> 'none',
                                      'fontWeight'=> 'normal'
                                  ]
                              ]
                          ]
                        ],
                        "series" =>  $data['riskhatirasi'],                         
                        
                      ]
                    ]);
                  ?>
                </div>
              </div>   
    </div>


       <?php
Modal::begin([
    'id'=>'modal',
    'size'=>'modal-lg',
]);

    echo "<div id='modalContent'></div>";
Modal::end();
?>
<?php $this->registerJs(
'function init_click_handlers(){
    $(".modalButton3").click(function() {
        //var fID = $(this).closest("tr").data("key");
        alert(fID);
            $.get(
                "bgysfirmadegerlendirme/create",
                //{  id: fID   },
                function (data)
                {
                    $("#modal").find(".modal-body").html(data);
                    $(".modal-body").html(data);
                    $("#modal").modal("show");              
                }    
            );    
    });
};

init_click_handlers(); //first run
$("#some_pjax_id").on("pjax:success", function() {
  init_click_handlers(); //reactivate links in grid after pjax update
});

');?>
    <?php Pjax::end(); ?>
