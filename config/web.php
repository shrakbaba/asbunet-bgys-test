<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            'cookieValidationKey' => 'bgys-temp-key-change-this',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => $params['giristipi'] == 1
                ? 'Edvlerblog\Adldap2\model\UserDbLdap'
                : 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@app/mail',
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['guest'],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [],
        ],
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'sourcePath' => null,
                    'js' => [
                        'https://code.jquery.com/jquery-3.6.0.min.js',
                    ],
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'sourcePath' => null,
                    'css' => [
                        'https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css',
                    ],
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'sourcePath' => null,
                    'js' => [
                        'https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js',
                    ],
                    'depends' => ['yii\web\JqueryAsset'],
                ],
                'yii\widgets\PjaxAsset' => [
    'sourcePath' => null,
    'js' => [
        'https://cdn.jsdelivr.net/npm/yii2-pjax@2.0.8/jquery.pjax.min.js',
    ],
    'depends' => ['yii\web\JqueryAsset'],
],
            ],
        ],
    ],
    'params' => $params,
];

if ($params['giristipi'] == 1) {
    $config['components']['ad'] = [
        'class' => 'Edvlerblog\Adldap2\Adldap2Wrapper',
        'providers' => [
            'default' => [
                'autoconnect' => true,
                'config' => [
                    'account_suffix' => $params['ldapAccountSuffix'],
                    'domain_controllers' => $params['ldapDomainControllers'],
                    'base_dn' => $params['ldapBaseDn'],
                    'admin_username' => $params['ldapAdminUsername'],
                    'admin_password' => $params['ldapAdminPassword'],
                    'port' => $params['ldapPort'],
                    'use_ssl' => $params['ldapUseSsl'],
                    'use_tls' => $params['ldapUseTls'],
                ],
            ],
        ],
    ];
}

return $config;
