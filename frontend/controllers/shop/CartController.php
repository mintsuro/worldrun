<?php

namespace frontend\controllers\shop;

use Yii;
//use cabinet\forms\shop\AddToCartForm;
use cabinet\readModels\shop\ProductReadRepository;
use cabinet\services\shop\CartService;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class CartController extends Controller
{
    //public $layout = 'blank';

    private $products;
    private $service;

    public function __construct($id, $module, CartService $service, ProductReadRepository $products, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->products = $products;
        $this->service = $service;
    }

    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'remove' => ['POST'],
                    'add' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $cart = $this->service->getCart();

        return $this->render('index', [
            'cart' => $cart,
        ]);
    }

    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionAdd($id)
    {
        $this->layout = 'cabinet';

        if (!$product = $this->products->find($id)) {
            throw new NotFoundHttpException('Запрашиваемый подарок не найден.');
        }

        try {
            $this->service->add($product->id);
            Yii::$app->session->setFlash('success', 'Подарок добавлен.');
            return $this->redirect(Yii::$app->request->referrer);
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function actionRemove($id)
    {
        try {
            $this->service->remove($id);
            Yii::$app->session->setFlash('success', 'Подарок удален');
            return $this->redirect(Yii::$app->request->referrer);
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
    }
}