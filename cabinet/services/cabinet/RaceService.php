<?php

namespace cabinet\services\cabinet;

use cabinet\entities\cabinet\Race;
use cabinet\entities\user\User;
use cabinet\repositories\cabinet\RaceRepository;
use cabinet\repositories\UserRepository;
use cabinet\services\TransactionManager;
use common\mail\services\Email;

class RaceService
{
    private $races;
    private $users;
    private $transaction;
    private $email;

    public function __construct(
        RaceRepository $races,
        UserRepository $users,
        TransactionManager $transaction,
        Email $email
    )
    {
        $this->races = $races;
        $this->users = $users;
        $this->transaction = $transaction;
        $this->email = $email;
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

        $race->assignUser($user->id, $race->id);
        // $this->email->sendEmailRegRace($user, $race);
    }
}