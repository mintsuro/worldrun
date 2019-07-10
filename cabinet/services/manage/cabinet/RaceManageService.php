<?php
namespace cabinet\services\manage\cabinet;

use cabinet\repositories\cabinet\RaceRepository;
use cabinet\entities\cabinet\Race;
use cabinet\entities\cabinet\UserAssignment;
use cabinet\forms\manage\cabinet\RaceForm;
use common\mail\services\Email;

class RaceManageService
{
    private $repository;
    private $email;

    public function __construct(
        RaceRepository $repository,
        Email $email
    )
    {
        $this->repository = $repository;
        $this->email = $email;
    }

    public function create(RaceForm $form): Race
    {
        $race = Race::create(
            $form->name,
            $form->description,
            $form->status,
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

        /** @var Race $race */
        foreach($races as $race):
            /** @var UserAssignment $assignment */
            foreach($race->userAssignments as $assignment){
                if($assignment->notify_start == 0){
                    $messages[] = $this->email->emailNotifyStartRace($assignment->user, $race);
                    $assignment->notify_start = 1;
                    $assignment->save();
                }
            }
            $race->status = Race::STATUS_WAIT;
            $race->save();
        endforeach;

        $res = 'Notifications all sent out';

        if($messages){
            $this->email->mailer->sendMultiple($messages);
            $res = 'Sent start race notification';
        }

        return $res;
    }

    /**
     * @param Race[] $races
     * @return string
     * Уведомление об скором окончании забега за 6 часов до окончания
     */
    public function notifyEnd(array $races): string
    {
        $messages = [];

        /** @var Race $race */
        foreach($races as $race):
            /** @var UserAssignment $assignment */
            foreach($race->userAssignments as $assignment){
                if($assignment->notify_end == 0){
                    $messages[] = $this->email->emailNotifyEndRace($assignment->user, $race);
                    $assignment->notify_end = 1;
                    $assignment->save();
                }
            }
        endforeach;

        $res = 'Notifications all sent out';

        if($messages){
            $this->email->mailer->sendMultiple($messages);
            $res = 'Sent end race notification';
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

        /** @var Race $race */
        foreach($races as $race):
            /** @var UserAssignment $assignment */
            foreach($race->userAssignments as $assignment){
                if($assignment->notify_finish == 0){
                    $messages[] = $this->email->emailNotifyFinishRace($assignment->user, $race);
                    $assignment->notify_finish = 1;
                    $assignment->save();
                }
            }
            $race->status = Race::STATUS_COMPLETE;
            $race->save();
        endforeach;

        $res = 'Notifications all sent out';

        if($messages){
            $this->email->mailer->sendMultiple($messages);
            $res = 'Sent end race notification';
        }

        return $res;
    }
}