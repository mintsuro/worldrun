<?php

namespace cabinet\services\auth;

use cabinet\forms\auth\PasswordResetRequestForm;
// use cabinet\forms\auth\ResetPasswordForm;
use cabinet\repositories\UserRepository;
use Yii;
use yii\mail\MailerInterface;

class PasswordResetService
{
    private $mailer;
    private $users;

    public function __construct(UserRepository $users, MailerInterface $mailer)
    {
        $this->mailer = $mailer;
        $this->users = $users;
    }

    public function request(PasswordResetRequestForm $form): void
    {
        $user = $this->users->getByEmail($form->email);

        if (!$user->isActive()) {
            throw new \DomainException('Пользователь не активирован.');
        }

        $user->requestPasswordReset();
        $this->users->save($user);

        $generatePassword =  (string) mt_rand(1000, 9999);

        $sent = $this->mailer
            ->compose(
                ['html' => 'auth/reset/confirm-html', 'text' => 'auth/reset/confirm-text'],
                ['user' => $user, 'password' => $generatePassword]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($user->email)
            ->setSubject('Password reset for ' . Yii::$app->name)
            ->send();

        if (!$sent) {
            throw new \RuntimeException('Ошибка отправки.');
        }
    }

    public function validateToken($token): void
    {
        if (empty($token) || !is_string($token)) {
            throw new \DomainException('Токен сброса пароля не может быть пустым.');
        }
        if (!$this->users->existsByPasswordResetToken($token)) {
            throw new \DomainException('Неверный токен сброса пароля.');
        }
    }

    public function reset(string $token, string $password): void
    {
        $user = $this->users->getByPasswordResetToken($token);
        $user->resetPassword($password);
        $this->users->save($user);
    }
}