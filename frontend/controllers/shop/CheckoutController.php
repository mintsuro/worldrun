<?php

namespace frontend\controllers\shop;

use cabinet\entities\shop\Discount;
use cabinet\entities\shop\product\Product;
use cabinet\forms\shop\order\OrderForm;
use cabinet\forms\shop\order\PromoCodeForm;
use cabinet\readModels\UserReadRepository;
use cabinet\readModels\shop\ProductReadRepository;
use cabinet\services\cabinet\RaceService;
use cabinet\services\shop\CartService;
use cabinet\services\shop\OrderService;
use cabinet\cart\Cart;
use cabinet\entities\cabinet\Race;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\Json;
use yii\web\Response;

class CheckoutController extends Controller
{
    public $layout = 'cabinet';

    private $service;
    private $race;
    private $cart;
    private $cartService;
    private $products;
    private $users;

    public function __construct(string $id, $module,
        OrderService $service,
        RaceService $race,
        Cart $cart,
        CartService $cartService,
        ProductReadRepository $products,
        UserReadRepository $users,
        array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
        $this->race = $race;
        $this->cart = $cart;
        $this->cartService = $cartService;
        $this->products = $products;
        $this->users = $users;
    }

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['participant'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'code' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @param integer $raceId
     * @throws NotFoundHttpException
     * @return mixed
     */
    public function actionIndex($raceId)
    {
        if(($race = Race::findOne($raceId)) === null){
            throw new NotFoundHttpException('Запрашиваемый забег не найден.');
        }

        $form = new OrderForm();
        $formCode = new PromoCodeForm();
        $discountCode = Discount::find()->active()
            ->where(['type' => Discount::TYPE_PROMO_CODE])
            ->andWhere(['<=', 'from_date', date('Y-m-d H:i:s')])
            ->andWhere(['>=', 'to_date', date('Y-m-d H:i:s')])
            ->all();

        /* @var Product[] $dataProvider */
        $dataProvider = $this->products->getAll($race->id);
        $user = $this->users->findActiveById(\Yii::$app->user->id);

        if($form->load(Yii::$app->request->post()) && $form->validate()){
            try{
                $order = $this->service->checkout($race->id, Yii::$app->user->id, $form);
                $this->race->registrationUser(Yii::$app->user->id, $raceId);
                return $this->redirect(['/cabinet/order/view', 'id' => $order->id]);
            } catch(\DomainException $e){
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('index', [
            'cart' => $this->cart,
            'model' => $form,
            'race' => $race,
            'dataProvider' => $dataProvider,
            'user' => $user,
            'modelCode' => $formCode,
            'discountCode' => $discountCode,
        ]);
    }

    // Активация промокода
    public function actionCode()
    {
        $code = Yii::$app->request->post('code');

        if (Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return Json::decode($this->service->resultPromoCode($code));
        }
    }

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        /* if($action->id === 'index') {
            $session = Yii::$app->session;
            if (isset($session['promo_code'])) unset($session['promo_code']);
        } */

        return true;
    }
}
