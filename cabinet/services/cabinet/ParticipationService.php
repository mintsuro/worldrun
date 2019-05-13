<?php

namespace cabinet\services\cabinet;

use cabinet\entities\cabinet\Race;
use cabinet\entities\user\User;
use cabinet\repositories\cabinet\RaceRepository;
use cabinet\repositories\UserRepository;

class ParticipationService
{
    private $races;
    private $users;

    public function __construct(RaceRepository $races, UserRepository $users)
    {
        $this->races = $races;
        $this->users = $users;
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

        $race->assignUser($user->id, $raceId);
    }
}