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
<p>
        <?= Html::a('Cihaz Listesi', ['index'], ['class' => 'btn btn-success']) ?>

        <?= Html::button("Modeller",['class'=>'btn btn-secondary',
                        'onclick'=>"window.location.href = '" . \Yii::$app->urlManager->createUrl(['/envmodel/index']) . "';"
                    ])
        ?>
        <?= Html::button("Markalar",['class'=>'btn btn-warning',
                        'onclick'=>"window.location.href = '" . \Yii::$app->urlManager->createUrl(['/envmarka/index']) . "';"
                    ])
        ?>
        <?= Html::button("Cihaz Türleri",['class'=>'btn btn-danger',
                        'onclick'=>"window.location.href = '" . \Yii::$app->urlManager->createUrl(['/envcihazturu/index']) . "';"
                    ])
        ?>
    </p>
<div style="display:inline-block;width:50%;">
	<?php echo Highcharts::widget([
		'scripts' => [
			'highcharts-3d',
			'modules/drilldown',
			'modules/exporting',
			'themes/sand-signika',
		],
		'options' => [
			"chart" => [
				"type" => "pie",
				"options3d" => [
					"enabled" => true,
					"alpha" => 35
				]
			],
			'title' => ['text' => 'Cihaz Türleri'],
			'plotOptions' => [
				'pie' => [
					"size"=>"50%",
					"innerSize" => 50,
					"depth" => 25,
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
</div><div style="display:inline-block;width:50%;">
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
		'options' => [
			"chart" => [
				"type" => "pie",
				"options3d" => [
					"enabled" => true,
					"alpha" => 35
				]
			],
			"title" => [
				"text" => "Ürün Dağılımları"
			],
			"subtitle" => [
				"text" => "Markalara Göre Ürün dağılımları"
			],
			"plotOptions" => [
				"pie" => [
					"size"=>"50%",
					"innerSize" => 50,
					"depth" => 25,
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

?></div>