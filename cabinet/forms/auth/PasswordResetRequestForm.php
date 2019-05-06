<?php

namespace cabinet\forms\auth;

use yii\base\Model;
use cabinet\entities\user\User;

class PasswordResetRequestForm extends Model
{
    public $email;

    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => User::class,
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => 'Такой пользователь с адресом электронной почты не найден.'
            ],
        ];
    }
}