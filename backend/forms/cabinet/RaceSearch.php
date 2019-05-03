<?php

namespace backend\forms\cabinet;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use cabinet\entities\cabinet\Race;
use yii\helpers\ArrayHelper;

class RaceSearch extends Model
{
    public $id;
    public $date_from;
    public $date_to;
    public $name;
    public $foto;
    public $status;

    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['name', 'foto'], 'safe'],
            [['date_from', 'date_to'], 'date', 'format' => 'php:Y-m-d'],
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = Race::find()->alias('r');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'r.id' => $this->id,
            'r.status' => $this->status,
        ]);

        $query
            ->andFilterWhere(['like', 'r.name', $this->name])
            ->andFilterWhere(['>=', 'r.date_start', $this->date_from ? $this->date_from : null])
            ->andFilterWhere(['<=', 'r.date_end', $this->date_to ? $this->date_to : null]);

        return $dataProvider;
    }
}
