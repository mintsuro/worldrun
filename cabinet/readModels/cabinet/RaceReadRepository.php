<?php

namespace cabinet\readModels\cabinet;

use cabinet\entities\user\User;
use yii\data\ActiveDataProvider;
use yii\data\DataProviderInterface;
use cabinet\entities\cabinet\Race;
use yii\db\ActiveQuery;

class RaceReadRepository
{
    public function getAll(User $user): DataProviderInterface
    {
        $query = Race::find()->alias('r')->active('r');
        $query->joinWith(['userAssignments us'], false);
        //$query->where(['not', ['us.user_id' => $user->id]]);
        $query->orderBy('r.date_end')->all();
        return $this->getProvider($query);
    }

    public function getAllByUser(User $user): DataProviderInterface
    {
        $query = Race::find()->alias('r');
        $query->joinWith(['userAssignments us'], false);
        $query->andWhere(['us.user_id' => $user->id]);
        $query->orderBy('r.id');
        return $this->getProvider($query);
    }

    private function getProvider(ActiveQuery $query): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => $query,
            'sort'  => false,
            'pagination' => [
                'pageSize' => 12,
            ]
        ]);
    }
}