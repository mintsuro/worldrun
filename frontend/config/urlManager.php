<?php

/** @var array $params */

return [
    'class' => 'yii\web\UrlManager',
    //'hostInfo' => $params['frontendHostInfo'],
    'baseUrl' => $params['frontendHostInfo'],
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'cache' => false,
    'rules' => [
        '' => 'site/index',
        'contact' => 'contact/index',
        'signup' => 'auth/signup/request',
        'signup/<_a:[\w-]+>' => 'auth/signup/<_a>',
        'reset/<_a:[\w-]+>' => 'auth/reset/<_a>',
        '<_a:login|logout>' => 'auth/auth/<_a>',

        'profile/<_a:[\w-]+>' => 'cabinet/profile/<_a>',
        'participation/<_a:[\w-]+>' => 'cabinet/participation/<_a>',
        'login/<service:google|facebook|etc>' => 'site/login',

        '<_c:[\w\-]+>' => '<_c>/index',
        '<_c:[\w\-]+>/<id:\d+>' => '<_c>/view',
        '<_c:[\w\-]+>/<_a:[\w-]+>' => '<_c>/<_a>',
        '<_c:[\w\-]+>/<id:\d+>/<_a:[\w\-]+>' => '<_c>/<_a>',
    ],
];