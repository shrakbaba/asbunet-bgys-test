<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

use miloschuman\highcharts\Highcharts;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EnvcihazlisteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Özet';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="envcihazliste-index">

	<h1><?= Html::encode($this->title) ?></h1>
<p class="bgys-env-nav">
        <?= Html::a('Cihaz Listesi', ['index'], ['class' => 'btn btn-success']) ?>

        <?= Html::button("Markalar",['class'=>'btn btn-warning',
                        'onclick'=>"window.location.href = '" . \Yii::$app->urlManager->createUrl(['/envmarka/index']) . "';"
                    ])
        ?>
        <?= Html::button("Modeller",['class'=>'btn btn-secondary',
                        'onclick'=>"window.location.href = '" . \Yii::$app->urlManager->createUrl(['/envmodel/index']) . "';"
                    ])
        ?>
        <?= Html::button("Cihaz Türleri",['class'=>'btn btn-danger',
                        'onclick'=>"window.location.href = '" . \Yii::$app->urlManager->createUrl(['/envcihazturu/index']) . "';"
                    ])
        ?>
    </p>
<?php
$toplamCihaz = 0;
foreach ((array)$tur as $turSatiri) {
	$toplamCihaz += (int)@$turSatiri[1];
}
?>
<div class="row" style="margin-top:15px;margin-bottom:15px;">
	<div class="col-sm-3">
		<div class="well well-sm"><strong>Toplam Cihaz Adedi</strong><br><?= $toplamCihaz ?></div>
	</div>
	<div class="col-sm-3">
		<div class="well well-sm"><strong>Cihaz Türü</strong><br><?= count((array)$tur) ?></div>
	</div>
	<div class="col-sm-3">
		<div class="well well-sm"><strong>Marka</strong><br><?= count((array)$markalardrill) ?></div>
	</div>
	<div class="col-sm-3">
		<div class="well well-sm"><strong>Model</strong><br><?= count((array)$model) ?></div>
	</div>
</div>
<div style="display:inline-block;width:50%; min-height:360px; vertical-align:top;">
	<?php if (empty($tur)) { ?>
		<p class="text-muted">Cihaz türü özeti için veri bulunamadı.</p>
	<?php } else { ?>
	<?php echo Highcharts::widget([
		'scripts' => [
			'modules/exporting',
		],
		'options' => [
			"chart" => [
				"type" => "pie",
				"height" => 330
			],
			'title' => ['text' => 'Cihaz Türleri'],
			'plotOptions' => [
				'pie' => [
					"size"=>"70%",
					'cursor' => 'pointer',
					'allowPointSelect'=> true,
					'dataLabels'=> [
						'enabled'=> true,
						'format'=> '<b>{point.name}</b>: {point.y} ',
					]
				],
			],
			'series' => [
            [ // new opening bracket
            'name' => 'Turler',
            'data' => $tur,
            ] // new closing bracket
        ],
    ],
]);
?>
	<?php } ?>
</div><div style="display:inline-block;width:50%; min-height:360px; vertical-align:top;">
<?php if (empty($markalardrill)) { ?>
	<p class="text-muted">Marka özeti için veri bulunamadı.</p>
<?php } else { ?>
<?php 
    /*
    echo Highcharts::widget([
    	'options' => [
	        'title' => ['text' => 'Markalara Göre'],
	        'plotOptions' => [
	            'pie' => [
	                'cursor' => 'pointer',
	                'allowPointSelect'=> true,
	                'dataLabels'=> [
	                	'enabled'=> true,
	                	'format'=> '<b>{point.name}</b>: {point.percentage:.1f} %',
	            	]
	            ],
	        ],
	        'series' => [
	            [ // new opening bracket
	                'type' => 'pie',
	                'name' => 'Elements',
	                'data' => $marka,
	            ] // new closing bracket
	        ],
    	],
	]);
	*/

	echo Highcharts::widget([
		'scripts' => [
			'modules/drilldown',
			'modules/exporting',
		],
		'options' => [
			"chart" => [
				"type" => "pie",
				"height" => 330
			],
			"title" => [
				"text" => "Ürün Dağılımları"
			],
			"subtitle" => [
				"text" => "Markalara Göre Ürün dağılımları"
			],
			"plotOptions" => [
				"pie" => [
					"size"=>"70%",
					'allowPointSelect'=> true,
				],
				"series" => [
					"dataLabels" => [
						"enabled" => true,
						"format" => "{point.name}: {point.y}"
					]
				]
			],
			"series" => [
				[
					"name" => "Urunler",
					"colorByPoint" => true,
					"data" => @$markalardrill
				]
			],
			"drilldown" => [
				"series" => @$modellerdrilldown
			]
		]
	]);

?>
<?php } ?>
</div>

<div style="display:block;width:100%; min-height:420px; vertical-align:top; margin-top:20px;">
<?php if (empty($model)) { ?>
	<p class="text-muted">Model özeti için veri bulunamadı.</p>
<?php } else { ?>
<?php
	echo Highcharts::widget([
		'scripts' => [
			'modules/exporting',
		],
		'options' => [
			"chart" => [
				"type" => "column",
				"height" => 420
			],
			"title" => [
				"text" => "Modellere Göre Dağılım"
			],
			"subtitle" => [
				"text" => "Cihaz model adetleri"
			],
			"xAxis" => [
				"type" => "category",
				"labels" => [
					"rotation" => -45,
					"style" => [
						"fontSize" => "11px"
					]
				]
			],
			"yAxis" => [
				"min" => 0,
				"title" => [
					"text" => "Adet"
				]
			],
			"legend" => [
				"enabled" => false
			],
			"tooltip" => [
				"pointFormat" => "Adet: <b>{point.y}</b>"
			],
			"plotOptions" => [
				"series" => [
					"dataLabels" => [
						"enabled" => true,
						"format" => "{point.y}"
					]
				]
			],
			"series" => [
				[
					"name" => "Modeller",
					"colorByPoint" => true,
					"data" => $model
				]
			],
		]
	]);
?>
<?php } ?>
</div>
