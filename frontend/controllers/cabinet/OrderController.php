<?php

namespace frontend\controllers\cabinet;

use cabinet\readModels\shop\OrderReadRepository;
use cabinet\services\shop\OrderService;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class OrderController extends Controller
{
    public $layout = 'cabinet';
    private $orders;
    private $service;

    public function __construct($id, $module, OrderReadRepository $orders, OrderService $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->orders = $orders;
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
                        'roles' => ['participant'],
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
        $dataProvider = $this->orders->getOwm(\Yii::$app->user->id);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        if (!$order = $this->orders->findOwn(\Yii::$app->user->id, $id)) {
            throw new NotFoundHttpException('Запрашиваемые заказы не найдены.');
        }

        return $this->render('view', [
            'order' => $order,
        ]);
    }

    /**
     * @param $raceId
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionRace($raceId)
    {
        if (!$order = $this->orders->findByRace($raceId)) {
            throw new NotFoundHttpException('Запрашиваемый заказ не найдены.');
        }

        return $this->render('race', [
            'order' => $order,
        ]);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        try{
            $this->service->remove($id);
        }catch(\DomainException $e){
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        $this->redirect(['index']);
    }
}