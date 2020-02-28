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
    <p>
        <?php 
          echo Html::button('Ekle', ['value' => Url::to(['create']),'class' => 'btn btn-lg btn-success modalButton2' ,'style'=>"margin-bottom:5px;"]);  
          //echo Html::a('Create Bgysfarkindalikquiz', ['create'], ['class' => 'btn btn-success']);
        ?>
    </p>

    <h1><?= Html::encode($this->title) ?></h1>

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
 
$items = 
[
    [
        'label'=>'<i class="fas fa-balance-scale-left"></i>'.\Yii::t('app', date('Y')),
        'options' => ['id' => date('Y')],
        'encode'=>false,
        'content' => (Yii::$app->controller->renderPartial('index',['yil'=>date('Y')])),
        'active'=>true        
        // 'linkOptions'=>['data-url'=>Url::to(['/bgysizlemeolcme/index2?yil='.strval(date('Y'))] )]
    ],
    [
        'label'=>'<i class="fas fa-balance-scale-left"></i> '.\Yii::t('app', date('Y')-1),
        'options' => ['id' => date('Y')-1],
        'encode'=>false,
        'content' => (Yii::$app->controller->renderPartial('index',['yil'=>date('Y')-1])),
        //'linkOptions'=>['data-url'=>Url::to(['/bgysizlemeolcme/index2?yil='.strval(date('Y')-1)] )]
    ],
    [
        'label'=>'<i class="fas fa-balance-scale-left"></i> '.\Yii::t('app', date('Y')-2),
        'options' => ['id' => date('Y')-2],
        'encode'=>false,
        //'content' => $a,
        'content' => (Yii::$app->controller->renderPartial('index',['yil'=>date('Y')-2])),        
         //'linkOptions'=>['data-url'=>Url::to(['/site/fetch?fkid='.$fkid.'&tab='.$m.'&yil='.strval(date('Y'))])]
    ],
];
?>

<?php echo TabsX::widget([
      'id' => 'tab-term-plan', 
      'enableStickyTabs' => true,
      'stickyTabsOptions' => [
          'selectorAttribute' => 'data-target',
          'backToTop' => true,
      ],
      'items' => $items, 
      'position' => TabsX::POS_ABOVE, 
      'encodeLabels' => false]);
?>


<?php $this->registerJs(
'function init_click_handlers(){
       $(".modalButton2").click(function() {
        //alert(fID);
            $.get(
                "create",
                function (data)
                {
                    $("#modal").find(".modal-body").html(data);
                    $(".modal-body").html(data);
                    $("#modal").modal("show");              
                }    
            );    
    }); $(".modalButton3").click(function() {
        var fID = $(this).closest("tr").data("key");
        //alert(fID);
            $.get(
                "update",
                {  id: fID   },
                function (data)
                {
                    $("#modal").find(".modal-body").html(data);
                    $(".modal-body").html(data);
                    $("#modal").modal("show");              
                }    
            );    
    });
    $(".modalButton4").click(function() {
        var fID = $(this).closest("tr").data("key");
        //alert(fID);
            $.get(
                "view",
                {  id: fID   },
                function (data)
                {
                    $("#modal").find(".modal-body").html(data);
                    $(".modal-body").html(data);
                    $("#modal").modal("show");              
                }    
            );    
    });
    $(".modalButton5").click(function() {
        var fID = $(this).closest("tr").data("key");
        //alert(fID);
            $.get(
                "kayitgir",
                {  id: fID   },
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