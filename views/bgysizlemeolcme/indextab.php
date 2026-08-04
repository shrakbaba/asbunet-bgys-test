<?php
use kartik\tabs\TabsX;
use yii\helpers\Url;

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;


$this->title = 'İzleme Ölçme Değerlendirme Analiz Listesi';
$this->params['breadcrumbs'][] = $this->title;
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php 
          echo Html::button('Ekle', ['value' => Url::to(['create']),'class' => 'btn btn-lg btn-success modalButton2' ,'style'=>"margin-bottom:5px;"]);  
          //echo Html::a('Create Bgysfarkindalikquiz', ['create'], ['class' => 'btn btn-success']);
        ?>
    </p>

    <?php
   // $a=22222;
    Modal::begin([
        //'header'=>"<h2>Özel İlgi Grubu veya Otorite Ekle</h2>",
        'id'=>'modal',
        'size'=>'modal-lg',
    ]);

        echo "<div id='modalContent'></div>";
    Modal::end();
    ?>

<?php 

$basla=2018;
$bitis=date('Y')+1;
$itemsfor=[];

for ($i=$bitis; $i>$basla; $i--) { 
  $a=[
        'label'=>'<i class="fas fa-balance-scale-left"></i>'.\Yii::t('app', $i),
        'options' => ['id' => $i],
        'encode'=>false,
        'content' => (Yii::$app->controller->renderPartial('index',['yil'=>$i])),
        //date('Y')==$i ? 'active'=> 'true' :''      
        // 'linkOptions'=>['data-url'=>Url::to(['/bgysizlemeolcme/index2?yil='.strval(date('Y'))] )]
    ];
  
  array_push($itemsfor, $a);
  
} 

?>

<?php echo TabsX::widget([
      'id' => 'tab-term-plan', 
      'enableStickyTabs' => false,
      'stickyTabsOptions' => [
          'selectorAttribute' => 'data-target',
          'backToTop' => true,
      ],
      'items' => $itemsfor, 
      'position' => TabsX::POS_ABOVE, 
      'encodeLabels' => false]);
?>
