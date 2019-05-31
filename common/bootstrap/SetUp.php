<?php

namespace common\bootstrap;

use cabinet\services\ContactService;
use cabinet\cart\Cart;
use cabinet\cart\cost\calculator\DynamicCost;
use cabinet\cart\cost\calculator\SimpleCost;
use cabinet\cart\storage\HybridStorage;
use Strava\API\Oauth;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\ErrorHandler;
use yii\caching\Cache;
use yii\di\Container;
use yii\di\Instance;
use yii\helpers\Url;
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

        $container->setSingleton(Cart::class, function() use ($app){
            return new Cart(
                new HybridStorage($app->get('user'), 'cart', 3600 * 24,  $app->db),
                new DynamicCost(new SimpleCost())
            );
        });
    }
}