<?php

namespace cabinet\cart\storage;

use cabinet\cart\CartItem;
use cabinet\entities\shop\product\Product;
use yii\db\Connection;
use yii\db\Query;

/**
 * @property user_id
 * @property product_id
 * @property $quantity
 */
class DbStorage implements StorageInterface
{
    private $userId;
    private $db;

    public function __construct($userId, Connection $db)
    {
        $this->userId = $userId;
        $this->db = $db;
    }

    public function load(): array
    {
        $rows = (new Query())
            ->select('*')
            ->from('{{%shop_cart_items}}')
            ->where(['user_id' => $this->userId])
            ->orderBy(['product_id' => SORT_ASC])
            ->all($this->db);

        return array_map(function (array $row){
            /** @var Product $product */
            if($product = Product::find()->active()->andWhere(['id' => $row['product_id']])->one()){
                return new CartItem($product);
            }
            return false;
        }, $rows);
    }

    public function save(array $items): void
    {
        $this->db->createCommand()->delete('{{%shop_cart_items}}', [
            'user_id' => $this->userId,
        ])->execute();

        $this->db->createCommand()->batchInsert(
            '{{%shop_cart_items}}',
            [
                'user_id',
                'product_id',
            ],
            array_map(function (CartItem $item){
                return [
                    'user_id' => $this->userId,
                    'product_id' => $item->getProductId(),
                ];
            }, $items)
        )->execute();
    }
}