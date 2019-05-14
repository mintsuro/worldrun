<?php

namespace behaviors;

use Yii;
use yii\base\Behavior;
use yii\imagine\Image;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

class ImageBehavior extends Behavior
{
    /** @var string */
    public $name;

    public $widthThumb;
    public $heightThumb;

    /** @var string */
    public $thumbPath = '';
    public $thumbUrl = '';

    protected $_subPath;

    public function events(){

    }

    /** @return string */
    public function getImageUrl(){
        $owner = $this->owner;
        return '';
    }

    /** @param string $name
     *  @return bool
     */
    public function upload($name = 'photo'): bool
    {
        /* @var ActiveRecord $owner */
        $owner = $this->owner;
        $image = UploadedFile::getInstance($owner, $name);
        if($image){
            $path = Image::thumbnail($image, 100, 100);
        }
    }
}