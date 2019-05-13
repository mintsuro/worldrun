<?php

namespace backend\forms\shop;

use yii\base\Model;
use cabinet\helpers\ProductHelper;
use yii\data\ActiveDataProvider;
use cabinet\entities\shop\product\Product;
use yii\helpers\ArrayHelper;

class ProductSearch extends Model
{
    public $id;
    public $code;
    public $name;
    public $category_id;
    public $brand_id;
    public $quantity;
    public $status;

    public function rules(): array
    {
        return [
            [['id', 'status', 'quantity'], 'integer'],
            [['name'], 'safe'],
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = Product::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC, 'quantity' => SORT_DESC],
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'quantity' => $this->quantity,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }

    public function statusList(): array
    {
        return ProductHelper::statusList();
    }
}
