<?php
namespace console\controllers;

use cabinet\entities\shop\order\Order;
use cabinet\readModels\shop\OrderReadRepository;
use yii\console\Controller;
use common\mail\services\Email;
use cabinet\services\manage\shop\OrderManageService;

class NotifyOrderController extends Controller
{
    private $orderService;
    private $repository;

    public function __construct($id, $module,
        OrderManageService $order,
        OrderReadRepository $repository,
        array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->orderService = $order;
        $this->repository = $repository;
    }


    // Уведомление о напоминании об оплате
    public function actionReminderPay(): bool
    {
        $orders = $this->repository->getNewAll();
        $result = 'Not new orders';

        /** @var $order Order */
        if($orders){
            try{
                $result = $this->orderService->notifyPay($orders);
                $this->stdout($result . PHP_EOL);
                exit;
            }catch(\DomainException $e){
                \Yii::$app->errorHandler->logException($e);
                $this->stdout($e->getMessage() . PHP_EOL);
            }
        }

        /** @var $result string */
        $this->stdout($result . PHP_EOL);
        return true;
    }
}