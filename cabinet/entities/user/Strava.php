<?php

namespace cabinet\entities\user;

use yii\db\ActiveRecord;
use Webmozart\Assert\Assert;

/**
 * @property integer $user_id
 * @property string  $token
 * @property string  $active
 */

class Strava extends ActiveRecord
{
    public static function create($token): self
    {
        Assert::notEmpty($token);

        $item = new static();
        $item->token = $token;
        $item->user_id = \Yii::$app->user->identity->getId();
        return $item;
    }

    public function edit($token): void
    {
        Assert::notEmpty($token);

        $this->token = $token;
    }

    public function isFor($token): bool
    {
        return ($this->token === $token);
    }

    public static function tableName()
    {
        return '{{%user_strava}}';
    }
}