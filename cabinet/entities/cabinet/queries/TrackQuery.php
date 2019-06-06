<?php

namespace cabinet\entities\cabinet\queries;

use cabinet\entities\cabinet\Track;
use yii\db\ActiveQuery;

class TrackQuery extends ActiveQuery
{
    /**
     * @param null $alias
     * @return $this
     */
    public function active($alias = null){
        return $this->andWhere([
            ($alias ? $alias . '.' : '') . 'status' => Track::STATUS_ACTIVE,
        ]);
    }
}