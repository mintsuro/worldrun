<?php

namespace frontend\controllers\cabinet;

use cabinet\entities\cabinet\Race;
use cabinet\readModels\cabinet\RaceReadRepository;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use cabinet\readModels\UserReadRepository;

class ParticipationController extends Controller
{
    public $layout = 'cabinet';

    private $races;
    private $users;

    public function __construct(
        $id,
        $module,
        RaceReadRepository $races,
        UserReadRepository $users,
        $config = []
    )
    {
        parent::__construct($id, $module, $config);
        $this->races = $races;
        $this->users = $users;
    }

    public function behaviors(){
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index'],
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
     * @param $userId
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionIndex($userId){
        if(!$user = $this->users->find($userId)){
            throw new NotFoundHttpException('Запрашиваемая страница не найдена');
        }

        $dataProvider = $this->races->getAllByUser($user);

        return $this->render('index', [
            'user' => $user,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @return mixed
     */
    public function actionAll()
    {
        $dataProvider = $this->races->getAll();

        return $this->render('all', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAdd(): void
    {

    }
}