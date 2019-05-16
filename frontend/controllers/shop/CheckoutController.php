<?php

namespace frontend\controllers\shop;

use cabinet\entities\shop\product\Product;
use cabinet\forms\shop\order\OrderForm;
use cabinet\readModels\UserReadRepository;
use cabinet\readModels\shop\ProductReadRepository;
use cabinet\services\cabinet\ParticipationService;
use cabinet\services\shop\OrderService;
use cabinet\cart\Cart;
use cabinet\entities\cabinet\Race;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class CheckoutController extends Controller
{
    public $layout = 'cabinet';

    private $service;
    private $participation;
    private $cart;
    private $products;
    private $users;

    public function __construct(string $id, $module,
        OrderService $service,
        ParticipationService $participation,
        Cart $cart,
        ProductReadRepository $products,
        UserReadRepository $users,
        array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
        $this->participation = $participation;
        $this->cart = $cart;
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
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['participant'],
                    ],
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

        /* @var Product $dataProvider */
        $dataProvider = $this->products->getAll();
        $user = $this->users->findActiveById(\Yii::$app->user->id);


        if($form->load(Yii::$app->request->post()) && $form->validate()){
            try{
                $this->service->checkout(Yii::$app->user->id, $form);
                $this->participation->registrationUser(Yii::$app->user->id, $raceId);
                return $this->redirect(['/cabinet/participation/index', 'userId' => Yii::$app->user->id]);
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
        ]);
    }
}
