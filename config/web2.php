<?php
//@ob_start();
//session_start();
$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

    Yii::setAlias('webroot', dirname(dirname(__DIR__)) . '/opengrc/web');
    Yii::setAlias('env_dosya', '@webroot/uploads/');
    
    Yii::setAlias('env_dosya_goster',@Yii::$app->request->baseUrl.'/uploads');
$config = [
    'id' => 'basic',
    'name'=>'OpenGRC',
    'language'=>'tr',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset'
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'NFv63W1SMFtKJEjpWy3lSWEZ_wQfeuRb',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => ($params['giristipi'] ==1) ? 'Edvlerblog\Adldap2\model\UserDbLdap' :'app\models\Userdb' ,
            'enableAutoLogin' => false,
            'authTimeout' => 60*30,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com',
                'username' => 'bimteknik@kastamonu.edu.tr',
                'password' => '',
                'port' => '587',
                'encryption' => 'tls',
            ],
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
        'authManager'=>[
            'class'=>'yii\rbac\DbManager',
            'defaultRoles'=>['guest'],
        ],

        'ad' => [
            'class' => 'Edvlerblog\Adldap2\Adldap2Wrapper',         
            'providers' => [
                
                'default' => [ 
                    'autoconnect' => true,
                    'config' => [
                    // Your account suffix, for example: matthias.maderer@example.lan
                    'account_suffix'        => '@asbu.edu.tr',
                    'domain_controllers'    => ['DCMA.asbu.edu.tr'],
                    'base_dn'               => 'dc=asbu,dc=edu,dc=tr',
                   
                    'admin_username'        => 'adentegrasyon',
                    'admin_password'        => 'Asbu@2018*',
                                    // To enable SSL/TLS read the docs/SSL_TLS_AD.md and uncomment
                                    // the variables below
                                    //'port' => 636,
                                    //'use_ssl' => true,
                                    //'use_tls' => true,                                
                    ]
                ],
            ], // close providers array
        ], //close ad
        
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [    
                '<alias:\w+>' => 'site/<alias>',    
            ],
        ],
        'i18n' => [
        'translations' => [
            'app' => [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@app/messages',
            ],
            'kvgrid' => [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@app/messages',
            ],
        ]]
        
    ],
    'params' => $params,
    'modules' => [
       'gridview' =>  [
            'class' => '\kartik\grid\Module'
        ],
        'dynagrid'=>[
            'class'=>'\kartik\dynagrid\Module',
            // other settings (refer documentation)
        ],
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
