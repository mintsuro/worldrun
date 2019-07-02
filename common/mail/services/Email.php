<?php
namespace common\mail\services;

use cabinet\entities\cabinet\Race;
use cabinet\entities\user\User;
use cabinet\entities\shop\order\Order;
use Yii;
use yii\mail\MailerInterface;

class Email
{
    public $mailer;

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

    /**
     * Sends registration race to user
     * @param User $user user model to with email should be send
     * @param Race $race user participation in the race
     */
    public function sendEmailRegRace($user, $race)
    {
        return $this->mailer
            ->compose(
                ['html' => 'race/registration/confirm-html', 'text' => 'race/registration/confirm-text'],
                ['user' => $user, 'race' => $race]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
            ->setTo($user->email)
            ->setSubject('Регистрация ' . lcfirst($race->name))
            ->send();
    }

    /**
     * @param User $user
     * @param Order $order
     * @return \yii\mail\MessageInterface
     */
    public function emailNotifyPay($user, $order)
    {
        return $this->mailer
            ->compose(
                ['html' => 'order/notify/notifyPay-html', 'text' => 'order/notify/notifyPay-text'],
                ['user' => $user, 'order' => $order]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
            ->setTo($user->email)
            ->setSubject('Напоминание об оплате заказа');
    }
}