<?php
use yii\helpers\Html;
use app\models\Bgysriskkabul;
use app\models\Bgysdiftalep;
use yii\helpers\bgys;
?>
<div class="col-lg-10">
	<span style="color:brown">Departman:</span><?= @$model->departman0->departman; ?><br>
	<span style="color:brown">Risk Nedeni:</span><?= @$model->risk_nedeni; ?><br>
	<span style="color:brown">Varlık Değeri:</span><?= @$model->varlik0->varlik_degeri; ?><br>	
	<span style="color:brown">Yüksek Risk Nedeni:</span><?= @$model->yuksek_riskin_sebebi; ?><br><br>	
	<div class="row">
		<div class="col-lg-6">
			<span style="display:block;"> <span style="color:brown;font-size: 18px;">Müdahale Öncesi Değerler</span></span>		
			<span style="display:block;"> <span style="color:brown">Olasılık:</span><?= @$model->olasilik_onceki.' ('.@$model->olasilikOnceki->deger.')'; ?></span>	
			<span style="display:block;"> <span style="color:brown">Gizlilik:</span><?= @$model->gizlilik_onceki.' ('.@$model->gizlilikOnceki->anlam.')'; ?></span>
			<span style="display:block;"> <span style="color:brown">Bütünlük:</span><?= @$model->butunluk_onceki.' ('.@$model->butunlukOnceki->anlam.')'; ?></span>
			<span style="display:block;"> <span style="color:brown">Erişilebilirlik:</span><?= @$model->erisilebilirlik_onceki.' ('.@$model->erisilebilirlikOnceki->anlam.')'; ?></span>
		</div>	

		<div class="col-lg-6">		
			<span style="display:block;"> <span style="color:brown;font-size: 18px;">Müdahale Sonrası Değerler</span></span>	
			<span style="display:block;"> <span style="color:brown">Olasılık:</span><?= @$model->olasilik_sonraki.' ('.@$model->olasilikSonraki->deger.')'; ?></span>			
			<span style="display:block;"> <span style="color:brown">Gizlilik:</span><?= @$model->gizlilik_sonraki.' ('.@$model->gizlilikSonraki->anlam.')'; ?></span>
			<span style="display:block;"> <span style="color:brown">Bütünlük:</span><?= @$model->butunluk_sonraki.' ('.@$model->butunlukSonraki->anlam.')'; ?></span>
			<span style="display:block;"> <span style="color:brown">Erişilebilirlik:</span><?= @$model->erisilebilirlik_sonraki.' ('.@$model->erisilebilirlikSonraki->anlam.')'; ?></span>		
		</div>	
	</div>	
	<div class="row">
		<?php 
			echo "<br>"; echo '<span style="color:brown;font-size: 18px;">Açılan Dif kaydı</span>';
			$iliskiler=json_decode($iliskiler);
			foreach ($iliskiler as $key => $value) {
				foreach ($value as $key2 => $value2) {
					if ($value2==$model->id) { //risk id si
						echo "<a href='/bgysdiftalep/view?id=".bgys::Difiddenid($key)."'><h4>Dif ".$key. "</h4></a>";
					}
				}
			} 
		?>
	</div>	
</div>
<div class="col-lg-2">
        <?php 
        $kabulmu=Bgysriskkabul::find()->where(['riskid'=>intval($model->id)])->one();
        //var_dump($kabulmu);
        if (is_null($kabulmu)) {
            if (\app\components\RecordAccess::hasDirectRole('BGYS_Yonetim_Temsilcisi')) {
                echo Html::a('<button class="btn btn-warning btn-sm" style="margin-bottom:5px" title="Risk Kabul"> Risk Kabul </button>', ['/bgysrisk/riskkabul', 'id'=>$model->id], ['data-pjax' => '0']);
            }
        }else{
        	?><span style="color:red">Risk zaten kabul edilmiş.</span> <?php

    	}
    ?>
</div>
