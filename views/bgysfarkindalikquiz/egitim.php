<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use kartik\select2\Select2;


/* @var $this yii\web\View */
/* @var $searchModel app\models\BgysfarkindalikquizSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Farkındalık Eğitimi';
$this->params['breadcrumbs'][] = $this->title;
?>

<head>
  <link href="https://vjs.zencdn.net/7.4.1/video-js.css" rel="stylesheet">

  <!-- If you'd like to support IE8 (for Video.js versions prior to v7) -->
  <script src="https://vjs.zencdn.net/ie8/ie8-version/videojs-ie8.min.js"></script>
</head>
     <?php /*echo Html::button("Modeller",['class'=>'btn btn-secondary',
                        'onclick'=>"window.location.href = '" . \Yii::$app->urlManager->createUrl(['/bgysfarkindalikquiz/create']) . "';"
                    ]) */
        ?>
<body>

	<?php Pjax::begin(); ?>

    <?php
    Modal::begin([
        'id'=>'modal',
        'size'=>'modal-lg',
    ]);

        echo "<div id='modalContent'></div>";
    Modal::end();
    ?>
<div class="center">
  <video id="my-video" class="video-js" controls preload="auto" width="800" height="400"
  poster="MY_VIDEO_POSTER.jpg" data-setup="{}">
    <source src="/uploads/bgys/farkindalik.mp4" type='video/mp4'>
    <p class="vjs-no-js">
      To view this video please enable JavaScript, and consider upgrading to a web browser that
      <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
    </p>
  </video>
</div>
  <script src="https://vjs.zencdn.net/7.4.1/video.js"></script>
    <?php Pjax::end(); ?>

     <br><p>
        <?= $a==0 ? Html::button('Testi Doldur', ['value' => Url::to(['bgysfarkindalikquiz/create']),'class' => 'btn btn-success btn-lg','id'=>'modalButton']) : null ?>
    </p>
</body>
