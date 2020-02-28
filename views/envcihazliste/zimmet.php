<?php

use yii\helpers\imdat;
use app\models\Userbilgi;

$this->title = "";
?>
<input style="text-align:center;margin-top:100px;"type="button" class="btn btn-info btn-lg hidden-print" value="Çıktı Al" onClick="window.print()">
<h1 style="text-align:center;margin-top:50px;">Zimmet Tutanağı</h1>

<div>
	<div style="margin-top:50px;">
		<span> 
			Aşağıda cihaz türü, markası, modeli, seri numarası tanımlanan cihaz imza karşılığında  <?= Yii::$app->params['giristipi']==1 ? @Userbilgi::findOne(['kisi_id'=>$model->zimmet])->ad.' '.@Userbilgi::findOne(['kisi_id'=>$model->zimmet])->soyad.' / '.@$model->zimmet0->username : @$model->zimmet0->ad." ".@$model->zimmet0->soyad." ".@$model->zimmet0->username 
			?>  'e zimmet tutanağı ile verilmiştir.
		</span>
	</div>
	<div style="margin-top:50px;">
		<span> 
			<b>Zimmet yapılacak cihazın özellikleri:</b>
			<hr>	  adlı kişinin zimmetine verilmiştir.
		</span>
	</div>

	<div style="margin-top:15px;"><b>Cihaz Türü:</b> <?= @$model->cihazTuru->cihaz_turu ?></div>

	<div style="margin-top:15px;"><b>Marka:</b> <?= @$model->marka->marka ?></div>

	<div style="margin-top:15px;"><b>Model:</b> <?= @$model->model->model ?></div>

	
	<div style="float:right;"">
		<div>
			<span>İmza</span>
		</div>

		<div >
			<span >
			<?= Yii::$app->params['giristipi']==1 ? @Userbilgi::findOne(['kisi_id'=>$model->zimmet])->ad.' '.@Userbilgi::findOne(['kisi_id'=>$model->zimmet])->soyad.' / '.@$model->zimmet0->username : @$model->zimmet0->ad." ".@$model->zimmet0->soyad." ".@$model->zimmet0->username 
			?> 
			</span>
		</div>
		<div >
			<span > <?= date('d/m/Y') ?> </span>
		</div>
	</div>	
</div>