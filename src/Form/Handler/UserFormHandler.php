<?php

namespace App\Form\Handler;

use App\Entity\User;
use App\Utils\Manager\UserManager;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Class UserFormHandler
 * @package App\Form\Handler
 */
class UserFormHandler
{
    /** @var UserManager */
    private UserManager $userManager;
    private UserPasswordHasherInterface $userPasswordHasher;

    /**
     * UserFormHandler constructor.
     * @param UserManager $userManager
     * @param UserPasswordHasherInterface $userPasswordHasher
     */
    public function __construct(UserManager $userManager, UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userManager = $userManager;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    /**
     * @param Form $form
     * @return User
     */
    public function processEditForm(FormInterface $form): User
    {
        $plainPassword = $form->get('plainPassword')->getData();

        /** @var User $user */
        $user = $form->getData();

        if ($plainPassword){
            $user->setPassword(
                $this->userPasswordHasher->hashPassword($user, $plainPassword)
            );
        }

        $user->setRoles($form->get('roles')->getData());

        $this->userManager->save($user);
        return $user;
    }
}