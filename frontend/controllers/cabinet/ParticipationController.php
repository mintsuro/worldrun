<?php

namespace frontend\controllers\cabinet;

use cabinet\entities\cabinet\Race;
use cabinet\readModels\cabinet\RaceReadRepository;
use cabinet\services\cabinet\RaceService;
use cabinet\readModels\UserReadRepository;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

class ParticipationController extends Controller
{
    public $layout = 'cabinet';

    private $races;
    private $users;
    private $service;

    public function __construct(
        $id,
        $module,
        RaceReadRepository $races,
        UserReadRepository $users,
        RaceService $service,
        $config = []
    )
    {
        parent::__construct($id, $module, $config);
        $this->races = $races;
        $this->users = $users;
        $this->service = $service;
    }

    public function behaviors(){
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
     * @throws NotFoundHttpException
     */
    public function actionIndex()
    {
        if(!$user = $this->users->find(Yii::$app->user->identity->getId())){
            throw new NotFoundHttpException('Запрашиваемая страница не найдена');
        }

        $dataProvider = $this->races->getAllByUser($user);

        return $this->render('index', [
            'user' => $user,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $id
     * @throws NotFoundHttpException
     * @return mixed
     */
    public function actionView($id)
    {
        $race = $this->findModel($id);
        $users = $race->getUsers()->orderBy('id')->all();

        return $this->render('view', [
            'race' => $race,
            'users' => $users,
        ]);
    }

    /**
     * @throws NotFoundHttpException
     * @return mixed
     */
    public function actionAll()
    {
        if(!$user = $this->users->find(Yii::$app->user->identity->getId())){
            throw new NotFoundHttpException('Запрашиваемая страница не найдена');
        }

        $dataProvider = $this->races->getAllByStartReg();

        return $this->render('all', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param integer $raceId
     * @return mixed
     */
    public function actionAdd($raceId)
    {
        if(Yii::$app->request->get()){
            try{
                $this->service->registrationUser(Yii::$app->user->identity->getId(), $raceId);
                Yii::$app->session->setFlash('success', 'Вы успешно зарегистрировались.');
                return $this->redirect(['index', 'userId' => Yii::$app->user->identity->getId()]);
            }catch(\DomainException $e){
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->redirect(['all']);
    }

    /**
     * @return mixed
     */
    public function actionUsers($raceId)
    {
        $race = Race::findOne($raceId);
        $users = $race->getUsers()->orderBy('id')->all();

        return $this->render('users', [
            'users' => $users,
            'race' => $race,
        ]);
    }

    /**
     * @param $id
     * @return Race|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if(!$model = Race::findOne($id)){
            throw new NotFoundHttpException('Запись забега не найдена.');
        }
        return $model;
    }
}