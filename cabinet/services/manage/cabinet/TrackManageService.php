<?php

namespace cabinet\services\manage\cabinet;

use cabinet\repositories\cabinet\TrackRepository;
use cabinet\entities\cabinet\Track;
use cabinet\forms\manage\cabinet\TrackForm;

class TrackManageService
{
    private $repository;

    public function __construct(
        TrackRepository $repository
    )
    {
        $this->repository = $repository;
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
        $product = $this->repository->get($id);
        $product->activate();
        $this->repository->save($product);
    }

    public function draft($id): void
    {
        $product = $this->repository->get($id);
        $product->draft();
        $this->repository->save($product);
    }
}