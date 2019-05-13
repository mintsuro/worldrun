<?php

namespace cabinet\forms\manage\shop\product;

use yii\base\Model;
use cabinet\entities\shop\product\Product;
use yii\web\UploadedFile;


class ProductForm extends Model
{
    public $name;
    public $description;
    public $price;
    public $photo;

    private $_product;

    public function __construct(Product $product = null, $config = [])
    {
        if($product) {
            $this->name = $product->name;
            $this->description = $product->description;
            $this->price = $product->price;
            $this->_product = $product;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['name', 'price'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['description'], 'string'],
            ['price', 'integer'],
            [['photo'], 'file', 'extensions' => 'jpeg, png, jpg'],
        ];
    }

    public function beforeValidate(): bool
    {
        if(parent::beforeValidate()){
            $this->photo = UploadedFile::getInstance($this, 'photo');
            return true;
        }
        return false;
    }

    public function attributeLabels(){
        return [
            'name' => 'Название',
            'price' => 'Цена',
            'description' => 'Описание',
            'photo' => 'Фотография',
            'status' => 'Статус',
            'created_at' => 'Дата создания',
            'quantity' => 'Количество'
        ];
    }
}