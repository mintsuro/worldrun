<?php

namespace frontend\controllers\auth;

use common\auth\Identity;
use cabinet\services\auth\NetworkService;
use rmrevin\yii\ulogin\AuthAction;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class NetworkController extends Controller
{
    public $enableCsrfValidation = false;

    private $service;

    public function __construct($id, $module, NetworkService $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['ulogin-sign', 'ulogin-auth'],
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
                'successCallback' => [$this, 'uloginAuthSuccessCallback'],
                'errorCallback' => function($data){
                    \Yii::error($data['error']);
                },
            ],
            'ulogin-sign' => [
                'class' => AuthAction::class,
                'successCallback' => [$this, 'uloginSignSuccessCallback'],
                'errorCallback' => function($data){
                    \Yii::error($data['error']);
                },
            ]
        ];
    }

    public function uloginAuthSuccessCallback($attributes)
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $email = ArrayHelper::getValue($attributes, 'email');

        try {
            $user = $this->service->login($email);
            Yii::$app->user->login(new Identity($user), Yii::$app->params['user.rememberMeDuration']);
            return $this->goBack();
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());

            return $this->goBack();
        }
    }

    public function uloginSignSuccessCallback($attributes)
    {
        $network = ArrayHelper::getValue($attributes, 'network');
        $identity = ArrayHelper::getValue($attributes, 'uid');
        $username = ArrayHelper::getValue($attributes, 'first_name');
        $email = ArrayHelper::getValue($attributes, 'email');
        $profileData = Json::encode($attributes);

        try {
            $this->service->sign($network, $identity, $username, $email, $profileData);
            Yii::$app->session->setFlash('success', 'Проверьте свою почту и следуйте дальнейшим инструкциям.');
            return $this->goHome();
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());

            return $this->redirect(['/auth/signup/request']);
        }
    }
}