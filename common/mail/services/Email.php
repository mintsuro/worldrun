<?php
namespace common\mail\services;

use cabinet\entities\user\User;
use Yii;
use yii\mail\MailerInterface;

class Email
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @param string generate password
     * @return bool whether the email was sent
     */
    public function sendEmailSignup($user, $password)
    {
        return $this->mailer
            ->compose(
                ['html' => 'auth/signup/emailVerify-html', 'text' => 'auth/signup/emailVerify-text'],
                ['user' => $user, 'password' => $password]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
            ->setTo($user->email)
            ->setSubject('Регистрация аккаунта на ' . Yii::$app->name)
            ->send();
    }
}