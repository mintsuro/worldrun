<?php

namespace cabinet\readModels\shop;

use cabinet\entities\shop\product\Product;
use yii\data\ActiveDataProvider;
use yii\data\DataProviderInterface;
use yii\db\ActiveQuery;
use yii\db\Expression;

class ProductReadRepository
{
    public function count(): int
    {
        return Product::find()->active()->count();
    }

    public function getAll($raceId): DataProviderInterface
    {
        $query = Product::find()->alias('p')->active('p')
            ->where(['race_id' => $raceId])
            ->orderBy(['sort' => SORT_ASC]);
        return $this->getProvider($query);
    }

    public function getFreeAll(): array
    {
        return Product::find()->alias('p')->active('p')->andWhere(['price' => 0])->all();
    }

    public function find($id): ?Product
    {
        return Product::find()->active()->andWhere(['id' => $id])->one();
    }

    private function getProvider(ActiveQuery $query): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ],
            'pagination' => false,
        ]);
    }
}