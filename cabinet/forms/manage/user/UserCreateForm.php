<?php

namespace cabinet\forms\manage\user;

use cabinet\entities\user\User;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class UserCreateForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $role;

    public function rules(): array
    {
        return [
            [['username', 'email', 'role'], 'required'],
            ['email', 'email'],
            [['username', 'email'], 'string', 'max' => 255],
            [['email'], 'unique', 'targetClass' => User::class],
            ['password', 'string', 'min' => 6],
        ];
    }

    public function rolesList(): array
    {
        return ArrayHelper::map(\Yii::$app->authManager->getRoles(), 'name', 'description');
    }

    public function attributeLabels(){
        return [
            'username' => 'Имя',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата добавления',
            'status' => 'Статус',
            'password' => 'Пароль',
            'role' => 'Роль'
        ];
    }
}