<?php

namespace common\bootstrap;

use cabinet\services\ContactService;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\ErrorHandler;
use yii\caching\Cache;
use yii\di\Container;
use yii\di\Instance;
use yii\mail\MailerInterface;
use yii\rbac\ManagerInterface;

class SetUp implements BootstrapInterface
{
    public function bootstrap($app): void
    {
        $container = \Yii::$container;

        $container->setSingleton(MailerInterface::class, function() use ($app){
            return $app->mailer;
        });

        $container->setSingleton(ErrorHandler::class, function() use ($app){
            return $app->errorHandler;
        });

        $container->setSingleton(Cache::class, function() use ($app){
            return $app->cache;
        });

        $container->setSingleton(ManagerInterface::class, function() use ($app){
            return $app->authManager;
        });

        $container->setSingleton(ContactService::class, [], [
            $app->params['adminEmail']
        ]);
    }
}