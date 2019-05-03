<?php

namespace cabinet\services\auth;

use cabinet\entities\user\User;
use cabinet\repositories\UserRepository;
use cabinet\services\TransactionManager;
use cabinet\access\Rbac;
use cabinet\services\RoleManager;
use common\mail\services\Email;

class NetworkService
{
    private $users;
    private $transaction;
    private $roles;
    private $email;

    public function __construct(UserRepository $users,
        TransactionManager $transaction,
        RoleManager $roles,
        Email $email)
    {
        $this->users = $users;
        $this->transaction = $transaction;
        $this->roles = $roles;
        $this->email = $email;
    }

    public function auth($network, $identity, $username, $email, $profileData)
    {
        if($this->users->findByUsernameOrEmail($email)){
            throw new \DomainException('Пользователь с таким email уже существует!');
        }

        /**
         * @var $user['userObject']  \cabinet\entities\user\User
         * @var $user['password'] string
         */
        $user = User::signupByNetwork($network, $identity, $username, $email, $profileData);

        $this->transaction->wrap(function () use ($user) {
            $this->users->save($user['userObject']);
            $this->roles->assign($user['userObject']->id, Rbac::ROLE_USER);
            $this->email->sendEmailSignup($user['userObject'], $user['password']);
        });
    }

    public function attach($id, $network, $identity): void
    {
        if ($this->users->findByNetworkIdentity($network, $identity)) {
            throw new \DomainException('Такая социальная сеть уже зарегистрирована.');
        }
        $user = $this->users->get($id);
        $user->attachNetwork($network, $identity);
        $this->users->save($user);
    }
}