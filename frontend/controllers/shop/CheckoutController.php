<?php

namespace frontend\controllers\shop;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use cabinet\forms\shop\order\OrderForm;
use cabinet\services\shop\OrderService;

class CheckoutController extends Controller
{
    public $layout = 'cabinet';

    private $service;

    public function __construct(string $id, $module, OrderService $service, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $form = new OrderForm($this->cart->getWeight());

        if($form->load(Yii::$app->request->post()) && $form->validate()){
            try{
                $order = $this->service->checkout(Yii::$app->user->id, $form);
                return $this->redirect(['/cabinet/order/view', 'id' => $order->id]);
            } catch(\DomainException $e){
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('index', [
            'cart' => $this->cart,
            'model' => $form
        ]);
    }
}