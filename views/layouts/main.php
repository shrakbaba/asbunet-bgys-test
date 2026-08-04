<?php
/* Sayfa düzeninin asıl dosyası*/
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */


//if (Yii::$app->controller->action->id === 'login') { 
/**
 * Do not use this code in your template. Remove it. 
 * Instead, use the code  $this->layout = '//main-login'; in your controller.
 */
//    echo $this->render(
//        'main-login',
//        ['content' => $content]
//    );
//} else {

    if (class_exists('backend\assets\AppAsset')) {
        backend\assets\AppAsset::register($this);
    } else {
        app\assets\AppAsset::register($this);
    }

    dmstr\web\AdminLteAsset::register($this);

    $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
    ?>
    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
        
        <link rel="stylesheet" type="text/css" href="/css/site.css">
        <link rel="stylesheet" type="text/css" href="/css/sayfa.css">
        <style>
            .content-wrapper h1,
            .content h1 {
                font-size: 36px;
                line-height: 1.2;
                margin-top: 20px;
                margin-bottom: 10px;
            }

            .content-wrapper .modalButton2.btn,
            .content-wrapper #modalButton.btn,
            .content .modalButton2.btn,
            .content #modalButton.btn {
                font-size: 14px !important;
                line-height: 1.3333333 !important;
                padding: 10px 16px !important;
                margin-top: 0 !important;
                margin-bottom: 12px !important;
                border-radius: 4px;
            }

            .content-wrapper .bgys-env-nav .modalButton2.btn,
            .content .bgys-env-nav .modalButton2.btn,
            .content-wrapper .bgys-env-nav .btn,
            .content .bgys-env-nav .btn {
                min-width: 102px !important;
                height: 36px !important;
                padding: 7px 12px !important;
                margin-top: 0 !important;
                margin-bottom: 0 !important;
                font-size: 14px !important;
                line-height: 20px !important;
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
            }
        </style>
    </head>
    <body class="hold-transition skin-red sidebar-mini">
    <?php $this->beginBody() ?>
    <div class="wrapper">

        <?= $this->render(
            'header.php',
            ['directoryAsset' => $directoryAsset]
        ) ?>

        <?= $this->render(
            'left.php',
            ['directoryAsset' => $directoryAsset]
        )
        ?>

        <?= $this->render(
            'content.php',
            ['content' => $content, 'directoryAsset' => $directoryAsset]
        ) ?>

    </div>

    <div class="modal fade" id="ortak-silme-onayi" tabindex="-1" role="dialog" aria-labelledby="ortak-silme-onayi-baslik">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header ortak-silme-onayi-baslik">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Kapat"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="ortak-silme-onayi-baslik">
                        <span class="glyphicon glyphicon-warning-sign"></span> Silme Onayı
                    </h4>
                </div>
                <div class="modal-body">
                    <p id="ortak-silme-onayi-mesaj"></p>
                    <p class="text-muted" id="ortak-silme-onayi-aciklama">Bu işlem geri alınamaz.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Vazgeç</button>
                    <button type="button" class="btn btn-danger" id="ortak-silme-onayi-tamam">Evet, Sil</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        #ortak-silme-onayi .ortak-silme-onayi-baslik {
            background-color: #772043;
            color: #fff;
        }
        #ortak-silme-onayi .close {
            color: #fff;
            opacity: .8;
        }
    </style>

    <?php
    $this->registerJs(<<<JS
yii.confirm = function (message, ok, cancel) {
    var modal = $('#ortak-silme-onayi');
    var onaylandi = false;
    var ayarlar = window.bgysConfirmOptions || {};
    var mesaj = String(message).toLocaleLowerCase('tr-TR');
    if (!window.bgysConfirmOptions) {
        if (mesaj.indexOf('pasife') !== -1) {
            ayarlar = {
                title: '<span class="glyphicon glyphicon-warning-sign"></span> Eğitim Durumu Onayı',
                description: '',
                okText: 'Evet, Pasife Al',
                okClass: 'btn-warning'
            };
        } else if (mesaj.indexOf('aktif') !== -1) {
            ayarlar = {
                title: '<span class="glyphicon glyphicon-warning-sign"></span> Eğitim Durumu Onayı',
                description: '',
                okText: 'Evet, Aktif Et',
                okClass: 'btn-primary'
            };
        }
    }
    var baslik = ayarlar.title || '<span class="glyphicon glyphicon-warning-sign"></span> Silme Onayı';
    var aciklama = ayarlar.description || 'Bu işlem geri alınamaz.';
    var tamamMetni = ayarlar.okText || 'Evet, Sil';
    var tamamClass = ayarlar.okClass || 'btn-danger';

    $('#ortak-silme-onayi-baslik').html(baslik);
    modal.find('.modal-body').html('<p id="ortak-silme-onayi-mesaj"></p><p class="text-muted" id="ortak-silme-onayi-aciklama"></p>');
    $('#ortak-silme-onayi-mesaj').text(message);
    $('#ortak-silme-onayi-aciklama').text(aciklama).toggle(aciklama !== '');
    $('#ortak-silme-onayi-tamam')
        .removeClass('btn-danger btn-warning btn-primary btn-success btn-info')
        .addClass(tamamClass)
        .text(tamamMetni);
    $('#ortak-silme-onayi-tamam').off('click.ortakSilmeOnayi').one('click.ortakSilmeOnayi', function () {
        onaylandi = true;
        modal.modal('hide');
        if (ok) { ok(); }
    });
    modal.off('hidden.bs.modal.ortakSilmeOnayi').one('hidden.bs.modal.ortakSilmeOnayi', function () {
        window.bgysConfirmOptions = null;
        $('#ortak-silme-onayi-baslik').html('<span class="glyphicon glyphicon-warning-sign"></span> Silme Onayı');
        $('#ortak-silme-onayi-aciklama').text('Bu işlem geri alınamaz.');
        $('#ortak-silme-onayi-tamam')
            .removeClass('btn-warning btn-primary btn-success btn-info')
            .addClass('btn-danger')
            .text('Evet, Sil');
        if (!onaylandi && cancel) { cancel(); }
    });
    modal.modal('show');
};

function bgysAramaKutulariniHazirla() {
    $('.grid-view thead :input[name]').not(':hidden').attr('autocomplete', 'off');
    var aktifSekme = window.location.hash;
    if (aktifSekme && aktifSekme.charAt(0) === '#') {
        var sekmeLinki = $('a[href="' + aktifSekme + '"][data-toggle="tab"], a[href="' + aktifSekme + '"][data-toggle="pill"]');
        if (sekmeLinki.length) {
            sekmeLinki.tab('show');
        }
    }
}

bgysAramaKutulariniHazirla();
$(document).off('pjax:end.bgysArama').on('pjax:end.bgysArama', bgysAramaKutulariniHazirla);

$(document).off('click.bgysConfirmOptions', '[data-bgys-confirm-title], [data-bgys-confirm-description], [data-bgys-confirm-ok]').on('click.bgysConfirmOptions', '[data-bgys-confirm-title], [data-bgys-confirm-description], [data-bgys-confirm-ok]', function () {
    window.bgysConfirmOptions = {
        title: $(this).data('bgys-confirm-title') || undefined,
        description: $(this).data('bgys-confirm-description') || '',
        okText: $(this).data('bgys-confirm-ok') || undefined,
        okClass: $(this).data('bgys-confirm-class') || undefined
    };
});

if (!window.bgysGridAramaHazir) {
    window.bgysGridAramaHazir = true;
    var bgysGridAramaYap = function (event) {
        if (!$(event.target).is('.grid-view thead :input[name]') || $(event.target).is(':hidden')) {
            return;
        }

        event.preventDefault();
        event.stopImmediatePropagation();

        var grid = $(event.target).closest('.grid-view');
        var aktifSekme = $('.nav-tabs li.active a[data-toggle="tab"], .nav-tabs li.active a[data-toggle="pill"]').attr('href') || window.location.hash;
        var adres = window.location.pathname;
        var mevcutSorgu = new URLSearchParams(window.location.search);
        var filtreAlanlari = grid.find('thead :input[name]').map(function () {
            return this.name;
        }).get();
        var filtreler = grid.find('thead :input[name]').serializeArray().filter(function (alan) {
            return String(alan.value).trim() !== '';
        });
        filtreAlanlari.forEach(function (alanAdi) {
            mevcutSorgu.delete(alanAdi);
        });
        filtreler.forEach(function (alan) {
            mevcutSorgu.set(alan.name, alan.value);
        });
        var sorgu = mevcutSorgu.toString();
        var sekme = aktifSekme && aktifSekme.charAt(0) === '#' ? aktifSekme : '';

        window.location.replace(adres + (sorgu ? '?' + sorgu : '') + sekme);
    };

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            bgysGridAramaYap(event);
        }
    }, true);
    document.addEventListener('change', bgysGridAramaYap, true);
}
JS
    );
    ?>

    <?php $this->endBody() ?>
    </body>
    </html>
    <?php $this->endPage() ?>
<?php // } ?>
