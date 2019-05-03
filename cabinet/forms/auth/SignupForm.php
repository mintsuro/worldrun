<?php

namespace cabinet\forms\auth;

use yii\base\Model;
use cabinet\entities\user\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => User::class, 'message' => 'Такой email адрес уже зарегистрирован.'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Имя',
            'Email' => 'Email'
        ];
    }
}