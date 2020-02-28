<?php
use yii\widgets\Breadcrumbs;
use dmstr\widgets\Alert;
use yii\widgets\Pjax;

?>
    <?php Pjax::begin(); ?>
<div class="content-wrapper" style="background-color: #ffffff">
    <section class="content-header">
        <?php if (isset($this->blocks['content-header'])) { ?>
            <h1><?= $this->blocks['content-header'] ?></h1>
        <?php } else { ?>
            <h1>
                <?php
                if ($this->title !== null) {
                   // echo \yii\helpers\Html::encode($this->title);
                } else {
                    echo \yii\helpers\Inflector::camel2words(
                        \yii\helpers\Inflector::id2camel($this->context->module->id)
                    );
                    echo ($this->context->module->id !== \Yii::$app->id) ? '<small>Module</small>' : '';
                } ?>
            </h1>
        <?php } ?>

        <?=
        Breadcrumbs::widget(
            [
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]
        ) ?>
    </section>

    <section class="content">
        <?= Alert::widget() ?>
        <?= $content ?>
    </section>
</div>

<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>Version</b> 1.0
    </div>
    <strong>Copyright &copy; 2018 <a href="http://bidb.asbu.edu.tr">Asbü Bidb</a></strong>
</footer>

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
    });
    $(".modalButton3").click(function() {
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
};

init_click_handlers(); //first run
$("#some_pjax_id").on("pjax:success", function() {
  init_click_handlers(); //reactivate links in grid after pjax update
});

');?>
<?php Pjax::end(); ?>