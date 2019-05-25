<?php

namespace frontend\controllers\shop;

use cabinet\repositories\NotFoundException;
use cabinet\readModels\shop\ProductReadRepository;
use cabinet\services\shop\CartService;
use cabinet\cart\CartItem;
use cabinet\entities\shop\product\Product;
use cabinet\forms\shop\order\PromoCodeForm;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use cabinet\helpers\PriceHelper;
use yii\helpers\Url;

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
     * @param Product[id] $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionAdd($id)
    {
        if (!$product = $this->products->find($id)) {
            throw new NotFoundHttpException('Запрашиваемый подарок не найден.');
        }

        if(Yii::$app->request->isAjax){
            try {
                $this->service->add($product->id);
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::decode($this->service->ajaxCalculateTotal(
                    Yii::$app->request->post('product_id')
                ));
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
            }
        }

        $this->service->add($product->id);
        Yii::$app->session->setFlash('success', 'Подарок добавлен');
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * @param CartItem['id'] $id
     * @return mixed
     */
    public function actionRemove($id)
    {
        if(Yii::$app->request->isAjax) {
            try {
                $this->service->remove($id);
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::decode($this->service->ajaxCalculateTotal(
                    Yii::$app->request->post('product_id')
                ));
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
            }
        }

        $this->service->remove($id);
        Yii::$app->session->setFlash('success', 'Подарок удален');
        return $this->redirect(Yii::$app->request->referrer);
    }
}