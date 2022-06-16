<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'EcnabcpHu2jQgpacRcc5TFiTavEEkZNG',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],            
        ],
        'assetManager' => [
            'appendTimestamp' => true,
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'cms/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure transport
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
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'baseUrl' => '/',
            'rules' => [
                'av/<id:\d+>' => 'article/view',
                'ac/<id:\d+>' => 'article/viewc',
                'a/<limit:\d+>' => 'article/list',
                'a' => 'article/index',
                'cat/<id:\d+>' => 'article-category/view',
                'cat' => 'article-category/index',
                'cont/<id:\d+>' => 'article-content/view',
                'article/<id:\d+>' => 'site/index',
                'category/<id:\d+>' => 'site/index',
                'article/undefined' => 'site/ok',
                'privacy' => 'site/index',
                '<controller:trap>/<action:\w+>' => '<controller>/<action>',
                '<controller:logc>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:logc>/<action:\w+>' => '<controller>/<action>',
                '<controller:logc>' => 'logc/index',
                '<controller:cms>/<action:\w+>' => '<controller>/<action>',
                '<controller:cms>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:cms>' => '<controller>',
                '<controller:hook>/<action>' => '<controller>/<action>',
                '/' => 'site/index',
            ],
        ],
    ],
    'params' => $params,
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
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
