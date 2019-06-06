<?php

namespace cabinet\services\cabinet;

use cabinet\entities\cabinet\Race;
use cabinet\entities\user\User;
use cabinet\repositories\cabinet\RaceRepository;
use cabinet\repositories\UserRepository;
use cabinet\services\TransactionManager;

class RaceService
{
    private $races;
    private $users;
    private $transaction;

    public function __construct(
        RaceRepository $races,
        UserRepository $users,
        TransactionManager $transaction
    )
    {
        $this->races = $races;
        $this->users = $users;
        $this->transaction = $transaction;
    }

    /**
     * @param integer $userId
     * @param integer $raceId
     * @return mixed
     */
    public function registrationUser($userId, $raceId): void
    {
        $race = $this->races->get($raceId);
        $user = $this->users->get($userId);

        $this->transaction->wrap(function() use ($race, $user){
            $race->assignUser($user->id, $race->id);
            $race->createStartNumber($race->id, $user->id);
        });
    }
}