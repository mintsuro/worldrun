<?php
namespace common\mail\services;

use cabinet\entities\cabinet\Race;
use cabinet\entities\cabinet\Track;
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
     * @return bool whether the email was sent
     */
    public function sendEmailRegRace(User $user, Race $race)
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
    public function emailNotifyPay(User $user, Order $order)
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


    /**
     * @param User $user
     * @param Order $order
     * @return bool whether the email was sent
     */
    public function sendEmailNotifySentOrder(User $user, Order $order)
    {
        return $this->mailer
            ->compose(
                ['html' => 'order/notify/notifySent-html', 'text' => 'order/notify/notifySent-text'],
                ['user' => $user, 'order' => $order]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
            ->setTo($user->email)
            ->setSubject('Оповещение об отправке заказа ' . Yii::$app->name)
            ->send();
    }

    /**
     * @param User $user
     * @param Race $race
     * @return \yii\mail\MessageInterface
     */
    public function emailNotifyStartRace(User $user, Race $race)
    {
        $this->mailer
            ->compose(
                ['html' => 'race/notify/startRace-html', 'text' => 'race/notify/startRace-text'],
                ['user' => $user, 'race' => $race]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
            ->setTo($user->email)
            ->setSubject('Напоминание о старте забега.');
    }

    /**
     * @param User $user
     * @param Race $race
     * @return \yii\mail\MessageInterface
     */
    public function emailNotifyEndRace(User $user, Race $race)
    {
        $this->mailer
            ->compose(
                ['html' => 'race/notify/endRace-html', 'text' => 'race/notify/endRace-text'],
                ['user' => $user, 'race' => $race]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
            ->setTo($user->email)
            ->setSubject('Напоминание о завершении забега.');
    }

    /**
     * @param User $user
     * @param Race $race
     * @return \yii\mail\MessageInterface
     */
    public function emailNotifyFinishRace(User $user, Race $race)
    {
        $this->mailer
            ->compose(
                ['html' => 'race/notify/finishRace-html', 'text' => 'race/notify/finishRace-text'],
                ['user' => $user, 'race' => $race]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
            ->setTo($user->email)
            ->setSubject('Уведомление о завершении забега.');
    }

    /**
     * @param User $user
     * @param Track $track
     * @return bool whether the email was sent
     * Send email about checking track screenshots
     */
    public function sendEmailNotifyResModTrack(User $user, Track $track)
    {
        $this->mailer
            ->compose(
                ['html' => 'track/notify/moderationTrack-html', 'text' => 'track/notify/moderationTrack-text'],
                ['user' => $user, 'track' => $track]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
            ->setTo($user->email)
            ->setSubject('Уведомление о модерации скриншота трека.')
            ->send();
    }
}