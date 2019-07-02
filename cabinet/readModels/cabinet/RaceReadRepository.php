<?php

namespace cabinet\readModels\cabinet;

use cabinet\entities\user\User;
use yii\data\ActiveDataProvider;
use yii\data\DataProviderInterface;
use cabinet\entities\cabinet\Race;
use yii\db\ActiveQuery;

class RaceReadRepository
{
    public function getAllByStartDate(): ActiveQuery
    {
        $query = Race::find()->alias('r')->active('r');
        $query->where(['>=', 'date_start', time()]);
        $query->andWhere(['<=', 'date_end', time()]);
        $query->orderBy(['r.date_start', SORT_ASC]);
        $query->all();
        return $query;
    }

    public function getAllByStartReg(): DataProviderInterface
    {
        $query = Race::find()->alias('r')->active('r');
        //$query->join('INNER JOIN', 'cabinet_user_participation us', 'us.user_id != :user_id', [':user_id' => $user->id]);
        $query->where(['>=', 'date_reg_from', time()]);
        $query->orderBy('r.date_end');
        $query->all();
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