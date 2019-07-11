<?php
namespace cabinet\forms\manage\shop\product;

use yii\base\Model;
use cabinet\entities\shop\product\Product;
use cabinet\entities\cabinet\Race;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

class ProductForm extends Model
{
    public $name;
    public $description;
    public $price;
    public $sort;
    public $photo;
    public $race_id;
    private $_product;

    public function __construct(Product $product = null, $config = [])
    {
        if($product) {
            $this->name = $product->name;
            $this->description = $product->description;
            $this->price = $product->price;
            $this->sort = $product->sort;
            $this->race_id = $product->race_id;
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
            [['price', 'race_id', 'sort'], 'integer'],
            ['sort', 'default', 'value' => 1],
            [['photo'], 'file', 'extensions' => 'jpeg, png, jpg'],
        ];
    }

    public function attributeLabels(){
        return [
            'name' => 'Название',
            'price' => 'Цена (руб.)',
            'description' => 'Описание',
            'status' => 'Статус',
            'created_at' => 'Дата создания',
            'quantity' => 'Количество',
            'sort' => 'Сортировка',
            'race_id' => 'Забег'
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

    public function getRaces(): array
    {
        return ArrayHelper::map(
            Race::find()->where(['>', 'date_end', date('Y-m-d H:i:s')])->orderBy('name')->asArray()->all(), 'id', 'name'
        );
    }
}