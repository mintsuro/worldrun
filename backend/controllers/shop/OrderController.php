<?php

namespace backend\controllers\shop;

use cabinet\services\manage\shop\OrderManageService;
use cabinet\entities\shop\order\Order;
use cabinet\entities\shop\order\Status;
use backend\forms\shop\OrderSearch;
use cabinet\forms\manage\shop\order\OrderEditForm;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class OrderController extends Controller
{
    private $service;

    public function __construct($id, $module, OrderManageService $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'export' => ['post'],
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $request = Yii::$app->request;
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if($req = $request->get('OrderSearch')){
            Yii::$app->session->set('order_date', $req);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @return mixed
     */
    public function actionExport()
    {
        $session = Yii::$app->session;

        if($session->has('order_date')) {
            $orderSearch = $session->get('order_date');
            $query = Order::find()
                ->andWhere(['current_status' => STATUS::PAID])
                ->andWhere(['>=', 'created_at', strtotime($orderSearch['date_from'])])
                ->andWhere(['<=', 'created_at', strtotime($orderSearch['date_to'])])
                ->orderBy(['id' => SORT_DESC]);

            try {
                $file = $this->service->export($query);
                $session->remove('order_date');
                return Yii::$app->response->sendFile($file, 'report.xlsx');
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                $session->setFlash('error', $e->getMessage());
            }
        }

        $session->setFlash('error', 'Выберите товары в фильтре даты заказа');
        return $this->redirect('index');
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'order' => $this->findModel($id),
        ]);
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $order = $this->findModel($id);

        $form = new OrderEditForm($order);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->edit($order->id, $form);
                return $this->redirect(['view', 'id' => $order->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('update', [
            'model' => $form,
            'order' => $order,
        ]);
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        try {
            $this->service->remove($id);
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['index']);
    }

    /**
     * @param integer $id
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id): Order
    {
        if (($model = Order::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}