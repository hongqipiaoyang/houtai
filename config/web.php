<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    //设置默认控制器
    'defaultRoute' => 'index',

    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'urlManager' =>[

            'enablePrettyUrl'=>TRUE,
            'showScriptName' => FALSE,
//            'urlSuffix'=>'html',
            'rules' =>[

            ],
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'wkUSs6Fdiz-nYtbdFLj9_wUIqOn4NJZ2',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'base/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
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
        // 'db' => require(__DIR__ . '/db.php'),
        'db' =>[
           'class' => 'yii\db\Connection',
           // 'dsn' => 'mysql:host=221.228.229.221;dbname=caipiao',
           'dsn' => 'mysql:host=127.0.0.1;dbname=user',
           // 'username' => 'clientUser',
           'username' => 'root',
           // 'password' => '123456',
           'password' => 'root',
           'charset' => 'utf8',
       //     'tablePrefix'  => 'sl_',
            ],
        'db2'=>[
            'class' => 'yii\db\Connection',
            'dsn'=>'mysql:host=221.228.229.221;dbname=Match',
            'username' => 'clientUser',
            'password' => '123456',
            'charset' => 'utf8',
            ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
