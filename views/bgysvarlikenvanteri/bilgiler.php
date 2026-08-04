<?php

use yii\helpers\Url;
use yii\helpers\imdat;
use yii\helpers\bgys;
use yii\helpers\Bgysolasilik;

$this->title = '';
//echo "<pre>";var_dump($bgysolasilik);exit;
?>

	<h2 style="text-align:center">BGYS Sabitleri</h2>

	<div class="col-lg-12">
		<div class="col-lg-12"> 	
			<span style="font-size: 18px"><b>Olasılık Değerleri</b>	</span>
			<hr style="height:5px;background-color:#8a3939;"/>
		</div>	

		<div> 
			<?php
			foreach ($bgysolasilik as $key => $value) { ?>
			<div class="col-lg-12">
				<div class="col-lg-4"><?= $value['deger'] ?></div>
				<div class="col-lg-8"><?= $value['basamak'] ?></div>
			</div>	
			<?php }
			?>
		</div>
	</div>

		<div class="col-lg-12" style="margin-top: 35px">
	
		<div class="col-lg-12"> 	
			<span style="font-size: 18px"><b>	Eylem Matrisi Değerleri</b>	</span>
			<hr style="height:5px;background-color:#8a3939;"/>
		</div>	
		<div class="col-lg-12"> 
		<?php
		foreach ($bgyseylemmatrisi as $key => $value) { ?>
			<div class="col-lg-12">
				<div class="col-lg-2"><?= $value['altdeger'] ?><?= "-" ?><?= $value['ustdeger'] ?></div>
				<div class="col-lg-4"><?= $value['eylem'] ?></div>
				<div class="col-lg-6"><?= $value['aciklama'] ?></div> 
			</div>
		<?php	}	?>
			
		</div>
	</div>

	<div class="col-lg-12" style="margin-top: 35px">
		<div class="col-lg-12"> 	
			<span style="font-size: 18px"><b>	Şiddet Tablosu Değerleri</b>	</span>
			<hr style="height:5px;background-color:#8a3939;"/>
		</div>	
		<div class="col-lg-12"> 
		<?php
		foreach ($bgyssiddettablosu as $key => $value) { ?>
			<div class="col-lg-12">
				<div class="col-lg-3"><?= $value['anlam'] ?></div>
				<div class="col-lg-3"><?= $value['gizlilik'] ?></div>
				<div class="col-lg-3"><?= $value['butunluk'] ?></div>
				<div class="col-lg-3"><?= $value['erisilebilirlik'] ?></div>
			</div>
		<?php	}	?>
			
		</div>
	</div>

	<div class="col-lg-12" style="margin-top: 35px">
		<div class="col-lg-12"> 	
			<span style="font-size: 18px"><b>	Bilgi Sınıfı Değerleri</b>	</span>
			<hr style="height:5px;background-color:#8a3939;"/>
			
		<?php
		foreach ($bgysbilgisinifi as $key => $value) { ?>
			<div class="row"> 
				<div class="col-lg-2"><?= $value['adi'] ?></div>
				<div class="col-lg-2"><?= $value['aciklama'] ?></div>
				<div class="col-lg-2"><?= $value['erisimhaklari'] ?></div>
				<div class="col-lg-2"><?= $value['saklama'] ?></div>
				<div class="col-lg-2"><?= $value['iletim'] ?></div>
				<div class="col-lg-2"><?= $value['imha'] ?></div>
			</div>
		
		<?php	}	?>
			
	</div>	

	</div>
