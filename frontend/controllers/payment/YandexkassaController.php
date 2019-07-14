<?php

namespace frontend\controllers\payment;

use cabinet\repositories\NotFoundException;
use YandexCheckout\Client;
use cabinet\readModels\shop\OrderReadRepository;
use cabinet\services\shop\OrderService;
use cabinet\entities\shop\order\Order;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\filters\AccessControl;

class YandexkassaController extends Controller
{
    public $enableCsrfValidation = false;

    private $service;
    private $orders;
    private $client;

    public function __construct($id, $module,
        OrderService $service,
        OrderReadRepository $orders,
        Client $client,
        $config = []
    )
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
        $this->orders = $orders;
        $this->client = $client;
    }

    public function behaviors(){
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['participant', 'admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @param $id
     */
    public function actionInvoice($id)
    {
        $order = $this->loadModel($id);

        $client = $this->client;
        $params = Yii::$app->params;

        $client->setAuth(Yii::$app->params['yandexKassaKey'], $params['yandexKassaPassword']);
        $payment = $client->createPayment(
            [
                'amount' => array(
                    'value' => $order->cost,
                    'currency' => 'RUB',
                ),
                'confirmation' => array(
                    'type' => 'redirect',
                    'return_url' => Html::encode(Url::to(['/payment/yandexkassa/check-payment', 'id' => $order->id])),
                ),
                'capture' => true,
                'description' => 'Заказ №' . $order->id,
                'metadata' => [
                    'orderId' => $order->id,
                ]
            ],
            uniqid('', true)
        );

        $this->service->savePaymentId($order->id, $payment->id);

        $this->redirect($payment->confirmation->confirmation_url);
    }


    public function actionCheckPayment($id)
    {
        $client = $this->client;

        $params = Yii::$app->params;

        $client->setAuth(Yii::$app->params['yandexKassaKey'], $params['yandexKassaPassword']);

        $order = $this->loadModel($id);
        $paymentId = $order->payment_id;
        $payment = $client->getPaymentInfo($paymentId);

        if($payment->status === 'succeeded'){
            try{
                $this->service->pay($order->id, $payment->payment_method->getType());
                Yii::$app->session->setFlash('success', 'Заказ успешно оплачен. Спасибо за покупку!');

                return $this->redirect(['/cabinet/order/view', 'id' => $order->id]);
            }catch (\DomainException $e){
                Yii::$app->session->setFlash('error', $e->getMessage());
                Yii::$app->errorHandler->logException($e);

                return $this->redirect(['/cabinet/order/view', 'id' => $order->id]);
            }
        }elseif($payment->status === 'canceled'){
            try{
                $this->service->fail($order->id, $payment->getCancellationDetails()->getReason());
                Yii::$app->session->setFlash('error', 'Заказ не был оплачен.');

                return $this->redirect(['/cabinet/order/view', 'id' => $order->id]);
            }catch (\DomainException $e){
                Yii::$app->session->setFlash('error', $e->getMessage());
                Yii::$app->errorHandler->logException($e);

                return $this->redirect(['/cabinet/order/view', 'id' => $order->id]);
            }
        }

        return true;
    }

    /**
     * @param integer $id
     * @return Order model
     * @throws NotFoundHttpException
     */
    private function loadModel($id): Order
    {
        if (!$order = $this->orders->findOwn(\Yii::$app->user->id, $id)) {
            throw new NotFoundHttpException('Заказ по запросу не найден.');
        }
        return $order;
    }
}