<?php

namespace cabinet\forms\manage\shop\product;

use yii\base\Model;
use cabinet\entities\shop\product\Product;
use yii\web\UploadedFile;
use yii\imagine\Image;

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

    public function beforeValidate(): bool
    {
        if(parent::beforeValidate()){
            $this->photo = UploadedFile::getInstance($this, 'photo');
            return true;
        }
        return false;
    }

    public function upload()
    {
        $width = 300;
        $height = 300;
        $originPath = \Yii::getAlias('@uploadsRoot') . '/origin/product/' . $this->photo->baseName . '.' . $this->photo->extension;
        $thumbPath = \Yii::getAlias('@uploadsRoot') . '/origin/product/' . $this->photo->baseName . "x$width-$height" . '.' . $this->photo->extension;

        if ($this->validate()) {
            $this->photo->saveAs($originPath);
            Image::thumbnail($originPath, $width, $height)->save($thumbPath, ['quality' => 90]);
            return true;
        } else {
            return false;
        }
    }
}