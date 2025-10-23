<?php

use yii\web\View;

$params       = require __DIR__ . '/params.php';
$paramsLocal  = is_file(__DIR__ . '/params-local.php') ? require __DIR__ . '/params-local.php' : [];
$params       = array_replace_recursive($params, $paramsLocal);

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
       'view' => [
            'on ' . View::EVENT_END_BODY => function () {
            // muat guard HANYA saat user SUDAH login
            if (Yii::$app->user->isGuest) {
                return; // jangan load di halaman login/guest
            }

            Yii::$app->view->registerJsVar(
                'appLoginUrl',
                \yii\helpers\Url::to(['site/login'], true),
                View::POS_HEAD
            );

            Yii::$app->view->registerJsFile('@web/js/bfcache-guard.js', [
                'position' => View::POS_END
            ]);
            },
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'nullDisplay' => 'Tidak ada',
            'defaultTimeZone' => 'Asia/Jakarta',
        ],
        'request' => [
            'cookieValidationKey' => 'Iw4cYD9Z7e8DQZfIQgEUtyrJY3WkjbjD',
        ],
        'phpMailer' => [
            'class'     => \app\components\PhpMailer::class,
            'fromEmail' => $params['fromEmail'],
            'smtpConfig'=> $params['smtp_prod'],   
        ],  
        'reportService' => [
            'class' => \app\components\ReportService::class,
            'dompdfPath' => '@vendor/dompdf/dompdf',
        ],
        'cache' => ['class' => 'yii\caching\FileCache'],
        'user' => ['identityClass' => 'app\models\User', 'enableAutoLogin' => true],
        'errorHandler' => ['errorAction' => 'site/error'],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            'useFileTransport' => true, 
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [[ 'class' => 'yii\log\FileTarget', 'levels' => ['error','warning','info'] ]],
        ],
        'db' => $db,
    ],
    'params' => array_merge($params, ['bsVersion' => '5.x']),
];

if (YII_ENV_DEV) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = ['class' => 'yii\debug\Module'];
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = ['class' => 'yii\gii\Module'];
}
return $config;