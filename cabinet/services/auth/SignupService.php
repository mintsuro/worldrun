<?php

namespace cabinet\services\auth;

use Yii;
use cabinet\access\Rbac;
use common\mail\services\Email;
// use shop\dispatchers\EventDispatcher;
use cabinet\entities\user\User;
use cabinet\forms\auth\SignupForm;
use cabinet\repositories\UserRepository;
use cabinet\services\RoleManager;
use cabinet\services\TransactionManager;

class SignupService
{
    private $users;
    private $roles;
    private $transaction;
    private $email;

    public function __construct(
        UserRepository $users,
        RoleManager $roles,
        Email $email,
        TransactionManager $transaction
    )
    {
        $this->users = $users;
        $this->roles = $roles;
        $this->email = $email;
        $this->transaction = $transaction;
    }

    public function signup(SignupForm $form): void
    {
        /**
         * @example  \cabinet\entities\user\User $user['userObject']
         * @example   string $user['password']
         */
        $user = User::requestSignup(
            $form->username,
            $form->email
        );

        $this->transaction->wrap(function () use ($user) {
            $this->users->save($user['userObject']);
            $this->roles->assign($user['userObject']->id, Rbac::ROLE_USER);
            $this->email->sendEmailSignup($user['userObject'], $user['password']);
        });
    }



    public function confirm($token): User
    {
        if (empty($token)) {
            throw new \DomainException('Пустой токен для подтверждения.');
        }
        $user = $this->users->getByEmailConfirmToken($token);
        $user->confirmSignup();
        $this->users->save($user);

        return $user;
    }
}