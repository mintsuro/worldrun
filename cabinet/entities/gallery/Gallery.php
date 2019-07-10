<?php
namespace cabinet\entities\gallery;

use yii\db\ActiveRecord;

/**
 * @property string  $type
 * @property integer $ownerId
 * @property integer $rank
 * @property string  $name
 * @property string  $description
 */
class Gallery extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%gallery_image}}';
    }
}