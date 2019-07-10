<?php

namespace cabinet\services\manage\cabinet;

use cabinet\entities\cabinet\Race;
use cabinet\entities\cabinet\Track;
use cabinet\repositories\cabinet\RaceRepository;
use Yii;

class PdfGeneratorService
{
    private $repository;

    public function __construct(RaceRepository $repository)
    {
        $this->repository = $repository;
    }
}