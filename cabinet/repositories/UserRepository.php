<?php

namespace cabinet\repositories;

use cabinet\entities\user\User;


class UserRepository
{
    private $dispatcher;

    /* public function __construct(EventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    } */

    public function findByUsernameOrEmail($value): ?User
    {
        return User::find()->andWhere(['or', ['username' => $value], ['email' => $value]])->one();
    }

    public function findByNetworkIdentity($network, $identity): ?User
    {
        return User::find()->joinWith('networks n')->andWhere(['n.network' => $network, 'n.identity' => $identity])->one();
    }

    public function findByStrava($token): ?User
    {
        return User::find()->joinWith('strava s')->andWhere(['s.token' => $token])->one();
    }

    public function get($id): User
    {
        return $this->getBy(['id' => $id]);
    }

    public function getByEmailConfirmToken($token): User
    {
        return $this->getBy(['verification_token' => $token]);
    }

    public function getByEmail($email): User
    {
        return $this->getBy(['email' => $email]);
    }

    public function getByPasswordResetToken($token): User
    {
        return $this->getBy(['password_reset_token' => $token]);
    }

    public function existsByPasswordResetToken(string $token): bool
    {
        return (bool) User::findByPasswordResetToken($token);
    }

    public function save(User $user): void
    {
        if (!$user->save()) {
            throw new \RuntimeException('Ошибка сохранения.');
        }
        // $this->dispatcher->dispatchAll($user->releaseEvents());
    }

    public function remove(User $user): void
    {
        if (!$user->delete()) {
            throw new \RuntimeException('Ошибка удаления.');
        }
        // $this->dispatcher->dispatchAll($user->releaseEvents());
    }

    private function getBy(array $condition): User
    {
        if (!$user = User::find()->andWhere($condition)->limit(1)->one()) {
            throw new NotFoundException('Пользователь не найден.');
        }
        return $user;
    }
}