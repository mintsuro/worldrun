<?php

namespace cabinet\readModels\cabinet;

use cabinet\entities\user\User;
use yii\data\ActiveDataProvider;
use yii\data\DataProviderInterface;
use cabinet\entities\cabinet\Race;
use yii\db\ActiveQuery;

class RaceReadRepository
{
    public function find(): ActiveQuery
    {
        $query = Race::find()->alias('r')->active('r');
        return $query;
    }

    public function getByStartDate(): ActiveQuery
    {
        $query = Race::find()->active();
        $query->where(['<=', 'date_start', date('Y-m-d H:i:s')]);
        $query->andWhere(['>=', 'date_end', date('Y-m-d H:i:s')]);
        //$query->andWhere(['status' => Race::STATUS_REGISTRATION]);
        return $query;
    }

    public function getByFinishDate(): ActiveQuery
    {
        $query = Race::find()->active();
        $query->where(['<=', 'date_end', date('Y-m-d H:i:s')]);
        //$query->andWhere(['status' => Race::STATUS_WAIT]);
        return $query;
    }

    public function getAllByStartReg(): DataProviderInterface
    {
        $query = Race::find()->active();
        //$query->join('INNER JOIN', 'cabinet_user_participation us', 'us.user_id != :user_id', [':user_id' => $user->id]);
        $query->where(['<=', 'date_reg_from', date('Y-m-d H:i:s')]);
        $query->andWhere(['>=', 'date_reg_to', date('Y-m-d H:i:s')]);
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

    public function getAll(): DataProviderInterface
    {
        $query = Race::find()->alias('r');
        $query->orderBy(['status' => SORT_ASC]);
        $query->all();
        return $this->getProvider($query);
    }

    private function getProvider(ActiveQuery $query): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => $query,
            'sort'  => false,
            'pagination' => [
                'pageSize' => 3,
            ]
        ]);
    }
}