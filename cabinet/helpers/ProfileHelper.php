<?php

namespace cabinet\helpers;

use cabinet\entities\user\Profile;

class ProfileHelper
{
    public static function sexList(): array
    {
        return [
            Profile::SEX_MALE => 'Мужчина',
            Profile::SEX_FEMALE => 'Женщина',
        ];
    }
}