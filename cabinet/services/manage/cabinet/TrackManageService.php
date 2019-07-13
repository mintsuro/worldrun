<?php
namespace cabinet\services\manage\cabinet;

use cabinet\repositories\cabinet\TrackRepository;
use cabinet\entities\cabinet\Track;
use cabinet\forms\manage\cabinet\TrackForm;
use common\mail\services\Email;

class TrackManageService
{
    private $repository;
    private $email;

    public function __construct(
        TrackRepository $repository,
        Email $email
    )
    {
        $this->repository = $repository;
        $this->email = $email;
    }

    public function edit($id, TrackForm $form): void
    {
        $track = $this->repository->get($id);
        $track->edit($form->distance, $form->elapsed_time, $form->status, $form->cancel_reason, $form->cancel_text);

        $this->repository->save($track);
    }

    public function remove($id): void
    {
        $track = $this->repository->get($id);
        $this->repository->remove($track);
    }

    public function activate($id): void
    {
        $track = $this->repository->get($id);
        $track->activate();
        $this->repository->save($track);
    }

    public function draft($id): void
    {
        $track = $this->repository->get($id);
        $track->draft();
        $this->repository->save($track);
    }

    public function isModeration($id): void
    {
        $track = $this->repository->get($id);
        if($track->isCancel() || $track->isActive()){
            $this->email->sendEmailNotifyResModTrack($track->user, $track);
        }
    }
}