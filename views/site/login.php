<style type="text/css">
    img {
        position:relative;  
        width: 80%;
    }
    .img{
        text-align: center;
        padding-bottom: 10px;
    }
    .login-page, .register-page{
        background: #ffffff !important; 
    }
</style>
<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Giriş Yap';

$fieldOptions1 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
];

$fieldOptions2 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];
?>

<div class="login-box">
    <div class="login-logo">
        <div class="img"><img src="/logo.png" ></div>
    </div>
    <!-- /.login-logo -->

    <?php
    foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
        echo '<div class="alert alert-' . $key . '">' . $message . '</div>';
    }
    ?>
    <div class="login-box-body">

        <?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => false]); ?>

        <?= $form
            ->field($model, 'username', $fieldOptions1)
            ->label(false)
            ->textInput(['placeholder' => $model->getAttributeLabel('Kullanıcı Adı')]) ?>

        <?= $form
            ->field($model, 'password', $fieldOptions2)
            ->label(false)
            ->passwordInput(['placeholder' => $model->getAttributeLabel('Şifre')]) ?>

        <div class="row">
            <!--<div class="col-xs-8">
                <?= $form->field($model, 'rememberMe')->checkbox()->label($model->getAttributeLabel('Beni Hatırla')) ?>
            </div>-->
            <!-- /.col -->
            <div class="col-xs-4">
                <?= Html::submitButton('Giriş Yap', ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button']) ?>
            </div>
            <!-- /.col -->
        </div>


        <?php ActiveForm::end(); ?>

        <!--<a href="userdb/forgot">Şifremi Unuttum</a><br>-->

    </div>
    <!-- /.login-box-body -->
</div><!-- /.login-box -->
