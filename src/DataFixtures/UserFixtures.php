<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Repository\RolesRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $userPasswordHasher;
    private RolesRepository $rolesRepository;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher, RolesRepository $rolesRepository)
    {
        $this->userPasswordHasher = $userPasswordHasher;
        $this->rolesRepository = $rolesRepository;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('test@mail.com');
        $user->setRoles($this->rolesRepository->find(1));
        $user->setPassword($this->userPasswordHasher->hashPassword(
            $user, 'password'));
        $user->setIsVerified(true);
        $user->setFullName('Test Admin');
        $user->setAddress('test address');
        $user->setZipcode('55668');
        $user->setPhone('+74848848444');
        $manager->persist($user);

        $manager->flush();
    }
}