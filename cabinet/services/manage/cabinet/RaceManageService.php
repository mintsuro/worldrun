<?php

namespace cabinet\services\manage\cabinet;

use cabinet\repositories\cabinet\RaceRepository;
use cabinet\entities\cabinet\Race;
use cabinet\forms\manage\cabinet\RaceForm;

class RaceManageService
{
    private $repository;

    public function __construct(
        RaceRepository $repository
    )
    {
        $this->repository = $repository;
    }

    public function create(RaceForm $form): Race
    {
        $race = Race::create(
            $form->name,
            $form->description,
            $form->status,
            $form->date_start,
            $form->date_end
        );

        if ($form->photo) {
            $race->setPhoto($form->photo);
        }

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
            $form->date_end
        );

        if ($form->photo) {
            $race->setPhoto($form->photo);
        }

        $this->repository->save($race);
    }

    public function remove($id): void
    {
        $race = $this->repository->get($id);
        $this->repository->remove($race);
    }
}