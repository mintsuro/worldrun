<?php

namespace cabinet\services\cabinet;

use cabinet\forms\user\ProfileEditForm;
use cabinet\repositories\cabinet\ProfileRepository;
use cabinet\repositories\UserRepository;
use cabinet\services\RoleManager;
use cabinet\services\TransactionManager;
use cabinet\access\Rbac;

class ProfileService
{
    private $profileRepository;
    private $usersRepository;
    private $transaction;
    private $roles;

    public function __construct(
        ProfileRepository $profileRepository,
        UserRepository $userRepository,
        TransactionManager $transaction,
        RoleManager $roles
    )
    {
        $this->profileRepository = $profileRepository;
        $this->usersRepository = $userRepository;
        $this->roles = $roles;
        $this->transaction = $transaction;
    }

    public function edit($userId, ProfileEditForm $form): void
    {
        $profile = $this->profileRepository->getByUser($userId);
        $user = $this->usersRepository->get($userId);

        $profile->edit($form->first_name, $form->last_name, $form->sex,
            $form->age, $form->city, $form->phone, $form->postal_code,
            $form->address_delivery, $form->size_costume);

        $user->editUsername($form->first_name);

        $this->transaction->wrap(function() use ($user, $profile){
            $this->usersRepository->save($user);
            $this->profileRepository->save($profile);
            $this->roles->assign($user->id, RBAC::ROLE_PARTICIPANT);
        });
    }
}