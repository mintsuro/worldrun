<?php

namespace cabinet\entities\cabinet\queries;

use cabinet\entities\cabinet\Race;
use yii\db\ActiveQuery;

class RaceQuery extends ActiveQuery
{
    /**
     * @param null $alias
     * @return $this
     */
    public function registration($alias = null)
    {
        return $this->andWhere([
            ($alias ? $alias . '.' : '') . 'status' => Race::STATUS_REGISTRATION,
        ]);
    }

    /**
     * @param null $alias
     * @return $this
     */
    public function wait($alias = null)
    {
        return $this->andWhere([
            ($alias ? $alias . '.' : '') . 'status' => Race::STATUS_WAIT,
        ]);
    }

    /**
     * @param null $alias
     * @return $this
     */
    public function active($alias = null)
    {
        return $this->andWhere(['or',
            ($alias ? $alias . '.' : '') . 'status' => Race::STATUS_WAIT,
            ($alias ? $alias . '.' : '') . 'status' => Race::STATUS_REGISTRATION,
        ]);
    }
}