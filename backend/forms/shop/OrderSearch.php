<?php

namespace backend\forms\shop;

use cabinet\entities\shop\order\Order;
use cabinet\helpers\OrderHelper;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class OrderSearch extends Model
{
    public $id;
    public $date_from;
    public $date_to;

    public function rules(): array
    {
        return [
            [['id'], 'integer'],
            [['date_from', 'date_to'], 'date', 'format' => 'php:Y-m-d'],
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = Order::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['current_status' => SORT_ASC]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query
            ->andFilterWhere(['>=', 'created_at', $this->date_from ? strtotime($this->date_from . ' 00:00:00') : null])
            ->andFilterWhere(['<=', 'created_at', $this->date_to ? strtotime($this->date_to . ' 23:59:59') : null]);


        return $dataProvider;
    }

    public function statusList(): array
    {
        return OrderHelper::statusList();
    }
}
