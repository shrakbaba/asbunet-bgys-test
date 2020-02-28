<?php

$this->title = 'Durum';
?>

<!--<link rel="stylesheet" href="<?= Yii::$app->request->baseUrl ?>/lte_css/bootstrap.min.css">-->
<!-- Font Awesome -->
<!--<link rel="stylesheet" href="<?= Yii::$app->request->baseUrl ?>/lte_css/font-awesome.min.css">-->
<!-- Ionicons -->
<!--<link rel="stylesheet" href="<?= Yii::$app->request->baseUrl ?>/lte_css/ionicons.min.css">-->
<!-- Theme style -->
<!--<link rel="stylesheet" href="<?= Yii::$app->request->baseUrl ?>/lte_css/AdminLTE.min.css">-->
  <!-- AdminLTE Skins. Choose a skin from the css/skins
    folder instead of downloading all of them to reduce the load. -->
<!--<link rel="stylesheet" href="<?= Yii::$app->request->baseUrl ?>/lte_css/_all-skins.min.css">
<link rel="stylesheet" href="<?= Yii::$app->request->baseUrl ?>/lte_css/skin-red-light.min.css">-->

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
  <div class="row col-lg-12">
    <div class="col-lg-3">
      <div class="info-box">
        <span class="info-box-icon bg-orange"><i class="glyphicon glyphicon-file"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Risk</span>
          <span class="info-box-number"><a href="/bgysrisk/index"><?= $data['risk'] ?> </a></span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <div class="col-lg-3">
      <div class="info-box">
        <span class="info-box-icon bg-red"><i class="glyphicon glyphicon-cog"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">Varlık</span>
          <span class="info-box-number"><a href="/bgysvarlikenvanteri/index"><?= $data['varlik'] ?> </a> </span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <div class="col-lg-3">
      <div class="info-box">
        <span class="info-box-icon bg-yellow"><i class="glyphicon glyphicon-align-left"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">Dif</span>
          <span class="info-box-number"><a href="/bgysdiftalep/index"><?= $data['diftalep'] ?></a><small> Dif  Talebi</small></span>
          <span class="info-box-number"><a href="/bgysdiftalep/index"><?= $data['diftakip'] ?></a><small> Dif  Takibi</small></span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div> 


  <!--  <div class="col-lg-2">
      <div class="info-box">
        <span class="info-box-icon bg-teal"><i class="glyphicon glyphicon-align-left"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">Cihaz</span>
          <span class="info-box-number"><a href="/envcihazliste/index"><?php //echo $data['cihazliste'] ?></a><small> Cihaz</small></span>
        </div> -->
        <!-- /.info-box-content -->
    <!--  </div> -->
      <!-- /.info-box -->
   <!-- </div> -->

     <div class="col-lg-3">
      <div class="info-box">
        <span class="info-box-icon bg-teal"><i class="glyphicon glyphicon-align-left"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">Tedarikçi</span>
          <span class="info-box-number"><a href="/bgysfirmabilgi/index"><?= $data['tedarikci'] ?></a> <small>Firma</small> </span>
          <span class="info-box-number"><a href="/bgysfirmabilgi/index"><?= $data['tedarikcipersonel'] ?></a><small> Firma Personeli</small>  </span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div> 

   
  </div> 
  <div class="row"> 
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
                    <?php $a=(($data['diftalepkapali']/$data['diftalep'])*100)."%";?>
                    <div class="progress sm">
                      <div class="progress-bar progress-bar-aqua" style="width: <?= $a ?>"></div>
                    </div>
                  </div>
                  <!-- /.progress-group -->
                  <div class="progress-group">
                    <span class="progress-text">Onaylı Dif Takip / Açılan Dif Takip</span>
                    <span class="progress-number"><b><?= $data['diftakiponayli'] ?> </b>/<?= $data['diftakip'] ?> </span>

                    <?php $a=(($data['diftakiponayli']/$data['diftakip'])*100)."%";?>
                    <div class="progress sm">
                      <div class="progress-bar progress-bar-red" style="width: <?= $a ?>"></div>
                    </div>
                  </div>
                  <!-- /.progress-group -->
                  <div class="progress-group">
                    <span class="progress-text">Risk Kabul / Toplam Risk</span>
                    <span class="progress-number"><b><?= $data['riskkabul'] ?></b>/<?= $data['risk'] ?></span>

                    <?php $a=(($data['riskkabul']/$data['risk'])*100)."%";?>
                    <div class="progress sm">
                      <div class="progress-bar progress-bar-green" style="width: <?= $a ?>"></div>
                    </div>
                  </div>
                  <!-- /.progress-group -->
                  <div class="progress-group">
                    <span class="progress-text">Kritik Varlık / Toplam Varlık</span>
                    <span class="progress-number"><b><?= $data['varlikkritik'] ?></b>/<?= $data['varlik'] ?></span>

                    <?php $a=(($data['varlikkritik']/$data['varlik'])*100)."%";?>
                    <div class="progress sm">
                      <div class="progress-bar progress-bar-yellow" style="width: <?= $a ?>"></div>
                    </div>
                  </div>

                  <div class="progress-group">
                    <span class="progress-text">Onaylanmış Tedarikçi Değerlendirme / Tedarikçi Değerlendirme</span>
                    <span class="progress-number"><b><?= $data['degerlendirmeonayli'] ?></b>/<?= $data['degerlendirme'] ?></span>

                    <?php $a=(($data['degerlendirmeonayli']/$data['degerlendirme'])*100)."%";?>
                    <div class="progress sm">
                      <div class="progress-bar progress-bar-brown" style="width: <?= $a ?>"></div>
                    </div>
                  </div>
                  <!-- /.progress-group -->
             </div> 
                 </div>  
                 </div>    
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
              <th>Durum</th>
            </tr>
          </thead>
          <tbody>
            <?php 
              $yy=$data['degerlendirilmeyenler'];
              // var_dump($yy);exit;
              if ($yy) {
              foreach ($yy as $key => $value) 
                { ?>
                  <tr>
                    <td><a href="bgysfirmadegerlendirme/create"><?= $value ?></a></td>
                    <td>Değerlendirilmemiş Tedarikçi</td>
                    <td><span class="label label-success">Shipped</span></td>
                  </tr>
                <?php }  }  ?>
            <tr>
              <td><a href="bgysfirmadegerlendirme/create"></a></td>
              <td>Call of Duty IV</td>
              <td><span class="label label-success">Shipped</span></td>
              <td>
                <div class="sparkbar" data-color="#00a65a" data-height="20"><canvas style="display: inline-block; width: 34px; height: 20px; vertical-align: top;" width="34" height="20"></canvas></div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <!-- /.table-responsive -->
    </div>
    <!-- /.box-body -->
    <div class="box-footer clearfix">
      <a href="javascript:void(0)" class="btn btn-sm btn-info btn-flat pull-left">Place New Order</a>
      <a href="javascript:void(0)" class="btn btn-sm btn-default btn-flat pull-right">View All Orders</a>
    </div>
    <!-- /.box-footer -->
  </div>
</div>
</div>
<div class="row">
<div class="col-lg-6">
  
             <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">Envanter Türleri</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <canvas id="pieChartTur" style="height:250px"></canvas>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
          
</div>
<div class="col-lg-6">
  
             <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">Envanter Markaları</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <canvas id="pieChartMarka" style="height:250px"></canvas>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
          
</div>

</div>

    <div class="col-lg-12">
      <!-- Info Boxes Style 2 -->

      <div class="col-lg-6">
        <div class="info-box bg-yellow">
          <span class="info-box-icon"><i class="glyphicon glyphicon-check"></i></span>

          <div class="info-box-content">
            <span class="info-box-text">Olay Kayıt</span>
            <span class="info-box-number"><?= $data['olaykayit'] ?></span>

            <div class="progress">
              <div class="progress-bar" style="width: 50%"></div>
            </div>
            <span class="progress-description">
              Son ayda açılan olay kaydı <?= $data['yeniolaykayit'] ?>
            </span>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
        <div class="info-box bg-green">
          <span class="info-box-icon"><i class="glyphicon glyphicon-flag"></i></span>

          <div class="info-box-content">
            <span class="info-box-text">Mentions</span>
            <span class="info-box-number">92,050</span>

            <div class="progress">
              <div class="progress-bar" style="width: 20%"></div>
            </div>
            <span class="progress-description">
              20% Increase in 30 Days
            </span>
          </div>
          <!-- /.info-box-content -->
        </div>

      </div>

      <div class="col-lg-6">
        <!-- /.info-box -->
        <div class="info-box bg-red">
          <span class="info-box-icon"><i class="glyphicon glyphicon-download"></i></span>

          <div class="info-box-content">
            <span class="info-box-text">Downloads</span>
            <span class="info-box-number">114,381</span>

            <div class="progress">
              <div class="progress-bar" style="width: 70%"></div>
            </div>
            <span class="progress-description">
              70% Increase in 30 Days
            </span>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
        <div class="info-box bg-aqua">
          <span class="info-box-icon"><i class="glyphicon glyphicon-signal"></i></span>

          <div class="info-box-content">
            <span class="info-box-text">Direct Messages</span>
            <span class="info-box-number">163,921</span>

            <div class="progress">
              <div class="progress-bar" style="width: 40%"></div>
            </div>
            <span class="progress-description">
              40% Increase in 30 Days
            </span>
          </div>
          <!-- /.info-box-content -->
        </div>
      </div>

    </div>

<script src="<?= Yii::$app->request->baseUrl ?>/lte_js/Chart.js"></script>
<script src="<?= Yii::$app->request->baseUrl ?>/lte_js/jquery.min.js"></script>
  
    <script>
      $(function () {
    var pieChartCanvasTur = $('#pieChartTur').get(0).getContext('2d')
    var pieChartTur       = new Chart(pieChartCanvasTur)
    var PieDataTur        = <?php echo $data['PieDataTur']?>

    var pieOptionsTur     = {
          //Boolean - Whether we should show a stroke on each segment
          segmentShowStroke    : true,
          //String - The colour of each segment stroke
          segmentStrokeColor   : '#fff',
          //Number - The width of each segment stroke
          segmentStrokeWidth   : 2,
          //Number - The percentage of the chart that we cut out of the middle
          percentageInnerCutout: 50, // This is 0 for Pie charts
          //Number - Amount of animation steps
          animationSteps       : 100,
          //String - Animation easing effect
          animationEasing      : 'easeOutBounce',
          //Boolean - Whether we animate the rotation of the Doughnut
          animateRotate        : true,
          //Boolean - Whether we animate scaling the Doughnut from the centre
          animateScale         : false,
          //Boolean - whether to make the chart responsive to window resizing
          responsive           : true,
          // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
          maintainAspectRatio  : true,
          //String - A legend template
         
      }

    var pieChartCanvasMarka = $('#pieChartMarka').get(0).getContext('2d')
    var pieChartMarka       = new Chart(pieChartCanvasMarka)
    var PieDataMarka        = <?php echo $data['PieDataMarka']?>
    
    var pieOptionsMarka     = {
      //Boolean - Whether we should show a stroke on each segment
      segmentShowStroke    : true,
      //String - The colour of each segment stroke
      segmentStrokeColor   : '#fff',
      //Number - The width of each segment stroke
      segmentStrokeWidth   : 2,
      //Number - The percentage of the chart that we cut out of the middle
      percentageInnerCutout: 50, // This is 0 for Pie charts
      //Number - Amount of animation steps
      animationSteps       : 100,
      //String - Animation easing effect
      animationEasing      : 'easeOutBounce',
      //Boolean - Whether we animate the rotation of the Doughnut
      animateRotate        : true,
      //Boolean - Whether we animate scaling the Doughnut from the centre
      animateScale         : false,
      //Boolean - whether to make the chart responsive to window resizing
      responsive           : true,
      // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
      maintainAspectRatio  : true,
      //String - A legend template
     
  }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    pieChartTur.Doughnut(PieDataTur, pieOptionsTur)
    pieChartMarka.Doughnut(PieDataMarka, pieOptionsMarka)
})
</script>