<?php
namespace frontend\controllers\auth;

use common\auth\Identity;
use cabinet\forms\auth\SignupForm;
use cabinet\services\auth\SignupService;
use cabinet\services\auth\NetworkService;
use Yii;
use yii\helpers\Json;
use yii\web\Controller;
use yii\filters\AccessControl;
use rmrevin\yii\ulogin\AuthAction;
use yii\helpers\ArrayHelper;


class SignupController extends Controller
{
    public $enableCsrfValidation = false;

    private $service;
    private $network;

    public function __construct($id, $module, SignupService $service, NetworkService $network, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
        $this->network = $network;
    }

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'ulogin-auth' => [
                'class' => AuthAction::class,
                'successCallback' => [$this, 'uloginSuccessCallback'],
                'errorCallback' => function($data){
                    \Yii::error($data['error']);
                },
            ]
        ];
    }

    public function uloginSuccessCallback($attributes)
    {
        $network = ArrayHelper::getValue($attributes, 'network');
        $identity = ArrayHelper::getValue($attributes, 'uid');
        $username = ArrayHelper::getValue($attributes, 'first_name');
        $email = ArrayHelper::getValue($attributes, 'email');
        $profileData = Json::encode($attributes);

        try {
             $this->network->auth($network, $identity, $username, $email, $profileData);
             Yii::$app->session->setFlash('success', 'Проверьте свою почту и следуйте дальнейшим инструкциям.');
             return $this->goHome();
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());

            return $this->redirect(['request']);
        }
    }

    /**
     * @return mixed
     */
    public function actionRequest()
    {
        $form = new SignupForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->signup($form);
                Yii::$app->session->setFlash('success', 'Проверьте свою почту и следуйте дальнейшим инструкциям.');
                return $this->goHome();
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('request', [
            'model' => $form,
        ]);
    }

    /**
     * @param $token
     * @return mixed
     */
    public function actionConfirm($token)
    {
        try {
            $this->service->confirm($token);
            Yii::$app->session->setFlash('success', 'Your email is confirmed.');
            return $this->redirect(['auth/auth/login']);
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->goHome();
    }
}
