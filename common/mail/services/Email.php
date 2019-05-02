<?php
namespace common\mail\services;

use cabinet\entities\user\User;
use Yii;

class Email
{
    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @param string generate password
     * @return bool whether the email was sent
     */
    public function sendEmailSignup($user, $password)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user],
                ['password' => $password]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($user->email)
            ->setSubject('Регистрация аккаунта на ' . Yii::$app->name)
            ->send();
    }
}