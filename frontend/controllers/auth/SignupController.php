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
                'only' => ['request', 'confirm'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
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
            $user = $this->service->confirm($token);
            Yii::$app->session->setFlash('success', 'Ваш email подтвержден.');
            Yii::$app->user->login(new Identity($user), Yii::$app->params['user.rememberMeDuration']);
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->goHome();
    }
}
