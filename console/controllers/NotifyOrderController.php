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

    public function actionIndex()
    {
         $this->stdout('Yes, test cron connect' . PHP_EOL);
    }

    public function actionReminderPay(): bool
    {
        $orders = $this->repository->getNewAll();

        /** @var $order Order */
        if($orders){
            try{
                $this->orderService->notifyPay($orders);
                $this->stdout('Send email for notify pay' . PHP_EOL);
                exit;
            }catch(\DomainException $e){
                \Yii::$app->errorHandler->logException($e);
            }
        }

        $this->stdout('Outstanding orders not found' . PHP_EOL);
        return true;
    }
}