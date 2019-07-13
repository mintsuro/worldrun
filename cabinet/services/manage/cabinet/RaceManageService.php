<?php
namespace cabinet\services\manage\cabinet;

use cabinet\repositories\cabinet\RaceRepository;
use cabinet\entities\cabinet\Race;
use cabinet\entities\cabinet\UserAssignment;
use cabinet\forms\manage\cabinet\RaceForm;
use cabinet\services\cabinet\TrackService;
use common\mail\services\Email;

class RaceManageService
{
    private $repository;
    private $email;
    private $trackService;

    public function __construct(
        RaceRepository $repository,
        TrackService $trackService,
        Email $email
    )
    {
        $this->repository = $repository;
        $this->email = $email;
        $this->trackService = $trackService;
    }

    public function create(RaceForm $form): Race
    {
        $race = Race::create(
            $form->name,
            $form->description,
            $form->status,
            $form->strava_only,
            $form->date_start,
            $form->date_end,
            $form->date_reg_from,
            $form->date_reg_to,
            $form->type
        );

        if ($form->photo) {
            $race->setPhoto($form->photo);
        }

        $race->addTemplate($form->template);

        $this->repository->save($race);

        return $race;
    }

    public function edit($id, RaceForm $form): void
    {
        $race = $this->repository->get($id);
        $race->edit(
            $form->name,
            $form->description,
            $form->status,
            $form->strava_only,
            $form->date_start,
            $form->date_end,
            $form->date_reg_from,
            $form->date_reg_to,
            $form->type
        );

        if ($form->photo) {
            $race->setPhoto($form->photo);
        }

        $race->editTemplate($race->id, $form->template);

        $this->repository->save($race);
    }

    public function remove($id): void
    {
        $race = $this->repository->get($id);
        $this->repository->remove($race);
    }

    /**
     * @param Race[] $races
     * @return string
     * Уведомление о старте забега и смена статуса забега 'Идет забег'
     */
    public function notifyStart(array $races): string
    {
        $messages = [];
        $res = 'Новых уведомлений нет';

        /** @var Race $race */
        foreach($races as $race):
            /** @var UserAssignment $assignment */
            foreach($race->userAssignments as $assignment){
                if($assignment->notify_start == 0){
                    $messages[] = $this->email->emailNotifyStartRace($assignment->user, $race);
                }
            }
            $race->status = Race::STATUS_WAIT;
            $race->save();
        endforeach;

        if($messages){
            if($this->email->mailer->sendMultiple($messages)){
                foreach($races as $race):
                    foreach($race->userAssignments as $assignment){
                        if($assignment->notify_start == 0){
                            $assignment->notify_start = 1;
                            $assignment->update(false);
                        }
                    }
                endforeach;
            }
            $res = 'Уведомления отправлены';
        }

        return $res;
    }

    /**
     * @param Race[] $races
     * @return string
     * Уведомление об скором окончании забега
     */
    public function notifyEnd(array $races): string
    {
        $messages = [];
        $res = 'Новых уведомлений нет';

        /** @var Race $race */
        foreach($races as $race):
            /** @var UserAssignment $assignment */
            foreach($race->userAssignments as $assignment){
                if($assignment->notify_end == 0){
                    $messages[] = $this->email->emailNotifyEndRace($assignment->user, $race);
                }
            }
        endforeach;

        if($messages){
            if($this->email->mailer->sendMultiple($messages)){
                foreach($races as $race):
                    foreach($race->userAssignments as $assignment){
                        if($assignment->notify_end == 0){
                            $assignment->notify_end = 1;
                            $assignment->update(false);
                        }
                    }
                endforeach;
            }
            $res = 'Уведомления отправлены';
        }

        return $res;
    }

    /**
     * @param Race[] $races
     * @return string
     * Уведомление об окончании забега и смена статуса забега на 'Завершен'
     */
    public function notifyFinish(array $races): string
    {
        $messages = [];
        $res = 'Новых уведомлений нет';
        $position = 0;

        /** @var Race $race */
        foreach($races as $race):
            /** @var UserAssignment $assignment */
            foreach($race->userAssignments as $assignment){
                if($assignment->notify_finish == 0){
                    $tracks = $race->getTracks()->andWhere(['user_id' => $assignment->user_id])->count();

                    if($tracks > 0){
                        $query = $this->trackService->sumResult($race);
                        // Определяем место пользователя в забеге
                        for ($i = 0, $j = 0; $i < count($query); $i++, $j++) {
                            if ($query[$j]['user_id'] == $assignment->user->id) {
                                $position = $j + 1;
                                break;
                            }
                        }
                        $messages[] = $this->email->emailNotifyFinishRace($assignment->user, $race, $position);
                    }
                }
            }
            $race->status = Race::STATUS_COMPLETE;
            $race->save();
        endforeach;

        if($messages){
            if($this->email->mailer->sendMultiple($messages)){
                foreach($races as $race):
                    foreach($race->userAssignments as $assignment){
                        if($assignment->notify_finish == 0){
                            $assignment->notify_finish = 1;
                            $assignment->update(false);
                        }
                    }
                endforeach;
            }
            $res = 'Уведомление отправлено';
        }

        return $res;
    }
}