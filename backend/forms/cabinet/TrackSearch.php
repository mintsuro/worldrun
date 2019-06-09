<?php

namespace backend\forms\cabinet;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use cabinet\entities\cabinet\Track;
use yii\helpers\ArrayHelper;

class TrackSearch extends Model
{
    public $id;
    public $date_from;
    public $date_to;
    public $status;

    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['date_from', 'date_to'], 'date', 'format' => 'php:Y-m-d'],
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = Track::find()->alias('t');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            't.id' => $this->id,
            't.status' => $this->status,
        ]);

        $query
            ->andFilterWhere(['>=', 't.date_start', $this->date_from ? $this->date_from : null])
            ->andFilterWhere(['<=', 't.date_start', $this->date_to ? $this->date_to : null]);

        return $dataProvider;
    }
}
