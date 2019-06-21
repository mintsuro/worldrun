<?php

namespace frontend\controllers\cabinet;

use cabinet\entities\cabinet\Track;
use cabinet\entities\cabinet\Race;
use cabinet\entities\user\User;
use cabinet\readModels\UserReadRepository;
use cabinet\repositories\UserRepository;
use cabinet\services\auth\StravaService;
use cabinet\services\cabinet\TrackService;
use cabinet\forms\cabinet\DownloadScreenForm;
use cabinet\forms\cabinet\DownloadTrackForm;
use Strava\API\OAuth;
use Strava\API\Exception;
use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;

class TrackController extends Controller
{
    public $layout = 'cabinet';

    public $enableCsrfValidation = false;

    private $stravaService;
    private $service;
    private $race;
    private $users;

    public function __construct(string $id, $module,
        UserRepository $users,
        StravaService $stravaService,
        TrackService $service,
        Race $race,
        $config = []
    )
    {
        parent::__construct($id, $module, $config);
        $this->stravaService = $stravaService;
        $this->service = $service;
        $this->race = $race;
        $this->users = $users;
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'add' => ['post'],
                    'change-strava-account' => ['get'],
                    'download' => ['post'],
                ],
            ]
        ];
    }

    /**
     * @param  integer $raceId
     * @return string|\yii\web\Response || void
     * @throws NotFoundHttpException
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     */
    public function actionIndex($raceId){
        $race = $this->findRace($raceId);
        $dataProvider = new ActiveDataProvider([
            'query' => $race->getTracks()
                ->andWhere(['user_id' => \Yii::$app->user->identity->getId()])
        ]);
        $user = User::findOne(Yii::$app->user->identity->getId());
        $urlOAuth = '';
        $options = [
            'clientId'     => Yii::$app->params['stravaClientId'],
            'clientSecret' => Yii::$app->params['stravaClientSecret'],
            'redirectUri'  => Url::to(['/cabinet/track/index', 'raceId' => $raceId])
        ];
        $screenForm = new DownloadScreenForm();
        $oAuth = new OAuth($options);

        try{
            if($screenForm->load(Yii::$app->request->post()) && $screenForm->validate()){
                $this->service->addFromScreen($raceId, $screenForm);
                Yii::$app->session->setFlash('success', 'Скриншот отправлен на модерацию.');
                return $this->redirect(['index', 'raceId' => $raceId]);
            }

            if(!isset($_GET['code'])){
                $urlOAuth = $oAuth->getAuthorizationUrl([
                    'scope' => [
                        'public',
                        // 'write',
                        // 'view_private',
                    ]
                ]);
            }else{
                $token = $oAuth->getAccessToken('authorization_code', [
                    'code' => $_GET['code']
                ]);

                if($this->users->findByStrava($token)){
                    \Yii::$app->session->setFlash('error', 'Такой пользователь Strava уже зарегистрирован.');
                    return $this->redirect(['index', 'raceId' => $raceId]);
                }

                $this->stravaService->attach(\Yii::$app->user->identity->getId(), $token->getToken());

                return $this->redirect(['index', 'raceId' => $raceId]);
            }
        }catch(Exception $e){
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'urlOAuth' => $urlOAuth,
            'user' => $user,
            'screenForm' => $screenForm,
            'race' => $race,
        ]);
    }

    /**
     * @param $raceId
     * @throws NotFoundHttpException
     */
    public function actionAdd($raceId){
        $race = $this->findRace($raceId);
        $form = new DownloadTrackForm();

        if(Yii::$app->request->post('strava-track')) {
            try {
                $athleteData = $this->stravaService->getAthleteData($race);
                $this->service->add($athleteData, $race->id, Yii::$app->request->post('strava-track'));
                return $this->redirect(['index', 'raceId' => $race->id]);
            } catch (\Exception $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }else{
            Yii::$app->session->setFlash('error', 'Выберите трек из Strava для загрузки.');
        }

        return $this->redirect(['index', 'raceId' => $race->id]);
    }

    /**
     * @param $raceId
     * @throws NotFoundHttpException
     */
    public function actionDownload($raceId)
    {
        $race = $this->findRace($raceId);
        $form = new DownloadTrackForm();

        if(Yii::$app->request->isAjax){
            try {
                $athleteData = $this->stravaService->getAthleteData($race);
                $tracks = $this->service->download($athleteData);
                return $this->renderPartial('_download', [
                    'tracks' => $tracks,
                    'raceId' => $raceId,
                    'model'  => $form,
                ]);
            }catch(\Exception $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->redirect(['index', 'raceId' => $race->id]);
    }

    /**
     * @param $raceId
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     */
    public function actionChangeStravaAccount($raceId){
        $race = $this->findRace($raceId);
        $options = [
            'clientId'     => Yii::$app->params['stravaClientId'],
            'clientSecret' => Yii::$app->params['stravaClientSecret'],
            'redirectUri'  => Url::to(['/cabinet/track/index', 'raceId' => $race->id])
        ];
        $oAuth = new OAuth($options);

        try{
            if(!isset($_GET['code'])){
                $urlOAuth = $oAuth->getAuthorizationUrl([
                    'scope' => [
                        'public',
                        // 'write',
                        // 'view_private',
                    ]
                ]);
            }else{
                $token = $oAuth->getAccessToken('authorization_code', [
                    'code' => $_GET['code']
                ]);

                if($this->users->findByStrava($token)){
                    \Yii::$app->session->setFlash('error', 'Такой пользователь Strava уже зарегистрирован.');
                    return $this->redirect(['index', 'raceId' => $raceId]);
                }

                $this->stravaService->change(\Yii::$app->user->identity->getId(), $token->getToken());

                return $this->redirect(['index', 'raceId' => $race->id]);
            }
        }catch(Exception $e){
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
    }

    public function actionAll(){
        $dataProvider = new ActiveDataProvider([
            'query' => Track::find(),
        ]);

        return $this->render('all', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $id
     * @return Race|null
     * @throws NotFoundHttpException
     */
    public function findRace($id): ?Race
    {
        if(($race = Race::findOne($id)) !== null){
            return $race;
        }else{
            throw new NotFoundHttpException('Забег по запросу не найден.');
        }
    }
}