<?php

namespace cabinet\services\auth;

use cabinet\entities\user\User;
use cabinet\repositories\UserRepository;
use cabinet\services\TransactionManager;
use cabinet\entities\cabinet\Race;
use common\mail\services\Email;
use Strava\API\OAuth;
use Strava\API\Client;
use Strava\API\Exception;
use Strava\API\Service\REST;
use Yii;
use yii\helpers\Json;

class StravaService
{
    private $users;
    private $transaction;

    public function __construct(UserRepository $users,
        TransactionManager $transaction)
    {
        $this->users = $users;
        $this->transaction = $transaction;
    }

    /**
     * @param integer $id
     * @param string $token
     */
    public function attach($id, $token): void
    {
        $user = $this->users->get($id);

        if(!$user->strava){
            $user->attachStrava($token);
            $this->users->save($user);
        }else{
            $user->changeStrava($token);
        }
    }

    /**
     * @param Race $race
     * @throws \Exception
     * @return array
     */
    public function getAthleteData($race)
    {
        $user = $this->users->get(Yii::$app->user->identity->getId());

        if (!$user = $this->users->findByStrava($user->strava->token)) {
            throw new \DomainException('Пользователь с таким токеном Strava не найден.');
        }

        try {
            $token = $user->strava->token;
            $adapter = new \GuzzleHttp\Client(['base_uri' => 'https://www.strava.com/api/v3/']);
            $service = new REST($token, $adapter);
            $client = new Client($service);
            $activities = $client->getAthleteActivities(strtotime($race->date_end), strtotime($race->date_start), $page = null, $per_page = null);

            return $activities;

        }catch(Exception $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
    }
}