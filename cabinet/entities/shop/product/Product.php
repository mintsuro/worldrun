<?php
namespace cabinet\entities\shop\product;

use cabinet\entities\EventTrait;
use cabinet\entities\shop\product\queries\ProductQuery;
use cabinet\entities\shop\product\events\ProductAppearedInStock;
use cabinet\entities\cabinet\Race;
use yii\db\ActiveRecord;
use yii\db\ActiveQuery;
use yii\db\Exception;

/**
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $price
 * @property integer $quantity
 * @property string  $photo
 * @property integer $status
 * @property integer $sort
 * @property integer $created_at
 * @property integer $race_id
 *
 * @property Race $race
 **/

class Product extends ActiveRecord
{
    use EventTrait;

    const STATUS_DRAFT = 0;
    const STATUS_ACTIVE = 1;

    public static function create($name, $description, $price, $sort, $raceId): self
    {
        $product = new static();
        $product->name = $name;
        $product->description = $description;
        $product->price = $price;
        $product->status = self::STATUS_ACTIVE;
        $product->sort = $sort;
        $product->race_id = $raceId;
        $product->created_at = time();
        return $product;
    }

    public function edit($name, $description, $price, $sort, $raceId): void
    {
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->sort = $sort;
        $this->race_id = $raceId;
    }

    public function setPhoto($photo){
        $this->photo = $photo;
    }

    public function changeQuantity($quantity): void
    {
        $this->setQuantity($quantity);
    }

    public function activate(): void
    {
        if ($this->isActive()) {
            throw new \DomainException('Товар уже доступен для продажи.');
        }
        $this->status = self::STATUS_ACTIVE;
    }

    public function draft(): void
    {
        if ($this->isDraft()) {
            throw new \DomainException('Товар уже недоступен для продажи.');
        }
        $this->status = self::STATUS_DRAFT;
    }

    public function isActive(): bool
    {
        return $this->status == self::STATUS_ACTIVE;
    }

    public function isDraft(): bool
    {
        return $this->status == self::STATUS_DRAFT;
    }

    public function isAvailable(): bool
    {
        return $this->quantity > 0;
    }

    public function canBeCheckout($quantity): bool
    {
        return $quantity <= $this->quantity;
    }

    public function checkout($quantity): void
    {
        if ($quantity > $this->quantity) {
            throw new \DomainException('Только ' . $this->quantity . ' товаров доступно.');
        }
        $this->setQuantity($this->quantity - 1);
    }

    private function setQuantity($quantity): void
    {
        if ($this->quantity == 0 && $quantity > 0) {
            $this->recordEvent(new ProductAppearedInStock($this));
        }
        $this->quantity = $quantity;
    }

    ##########################

    public function getRace(): ActiveQuery
    {
        return $this->hasOne(Race::class, ['id' => 'race_id']);
    }

    ##########################

    public function behaviors()
    {
        return [
            [
                'class' => '\yiidreamteam\upload\ImageUploadBehavior',
                'attribute' => 'photo',
                'thumbs' => [
                    'thumb' => ['width' => 400, 'height' => 400],
                ],
                'filePath' => \Yii::getAlias('@uploadsRoot') . '/origin/product/[[pk]]-[[basename]]',
                'fileUrl' => \Yii::$app->get('frontendUrlManager')->baseUrl . '/uploads/origin/product/[[pk]]-[[basename]]',
                'thumbPath' => \Yii::getAlias('@uploadsRoot') . '/thumb/product/[[pk]]-[[basename]]',
                'thumbUrl' => \Yii::$app->get('frontendUrlManager')->baseUrl . '/uploads/thumb/product/[[pk]]-[[basename]]',
            ],
        ];
    }

    public function attributeLabels(){
        return [
            'name' => 'Название',
            'price' => 'Цена',
            'photo' => 'Фотография',
            'description' => 'Описание',
            'status' => 'Статус',
            'created_at' => 'Дата создания',
            'quantity' => 'Количество',
            'sort' => 'Сортировка',
            'race_id' => 'Забег'
        ];
    }

    public static function tableName(): string
    {
        return '{{%shop_products}}';
    }

    public static function find(): ProductQuery
    {
        return new ProductQuery(static::class);
    }
}