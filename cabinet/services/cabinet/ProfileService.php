<?php

namespace cabinet\services\cabinet;

use cabinet\forms\user\ProfileEditForm;
use cabinet\repositories\cabinet\ProfileRepository;

class ProfileService
{
    private $profiles;

    public function __construct(ProfileRepository $profiles)
    {
        $this->profiles = $profiles;
    }

    public function edit($userId, ProfileEditForm $form): void
    {
        $profile = $this->profiles->getByUser($userId);

        $profile->edit($form->first_name, $form->last_name, $form->sex,
            $form->age, $form->city, $form->phone, $form->postal_code,
            $form->address_delivery, $form->size_costume);

        $this->profiles->save($profile);
    }
}