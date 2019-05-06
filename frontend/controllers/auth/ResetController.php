<?php
namespace frontend\controllers\auth;

use cabinet\forms\auth\PasswordResetRequestForm;
use cabinet\services\auth\PasswordResetService;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

class ResetController extends Controller
{

    private $service;

    public function __construct($id, $module, PasswordResetService $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    /**
     * @return mixed
     */
    public function actionRequest()
    {
        $form = new PasswordResetRequestForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->request($form);
                Yii::$app->session->setFlash('success', 'Проверьте свою электронную почту для дальнейших инструкций.');
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
     * @param $password
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionConfirm($token, $password)
    {
        try {
            $this->service->validateToken($token);
        } catch (\DomainException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        /* $form = new ResetPasswordForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->reset($token, $form);
                Yii::$app->session->setFlash('success', 'New password saved.');
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
            return $this->goHome();
        } */

        try {
            $this->service->reset($token, $password);
            Yii::$app->session->setFlash('success', 'Новый пароль сохранен.');
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->goHome();
    }
}
