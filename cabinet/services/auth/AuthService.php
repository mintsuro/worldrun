<?php

namespace cabinet\services\auth;

use cabinet\entities\user\User;
use cabinet\forms\auth\LoginForm;
use cabinet\repositories\UserRepository;

class AuthService
{
    private $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    public function auth(LoginForm $form): User
    {
        $user = $this->users->findByUsernameOrEmail($form->username);
        if (!$user || !$user->isActive() || !$user->validatePassword($form->password)) {
            throw new \DomainException('Неопределенный пользователь или пароль.');
        }
        return $user;
    }
}