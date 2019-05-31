<?php

namespace frontend\controllers\cabinet;

use cabinet\entities\cabinet\Race;
use cabinet\readModels\cabinet\RaceReadRepository;
use cabinet\services\cabinet\ParticipationService;
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
        ParticipationService $service,
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
                'only' => ['index'],
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
    public function actionIndex(){
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
     * @throws NotFoundHttpException
     * @return mixed
     */
    public function actionAll()
    {
        if(!$user = $this->users->find(Yii::$app->user->identity->getId())){
            throw new NotFoundHttpException('Запрашиваемая страница не найдена');
        }

        $dataProvider = $this->races->getAll($user);

        return $this->render('all', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
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
        $model = Race::findOne($raceId);
        $users = $model->getUsers()->orderBy('id')->all();

        return $this->render('users', [
            'users' => $users,
        ]);
    }
}