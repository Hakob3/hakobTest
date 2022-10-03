<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Repository\RolesRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Class UserFixtures
 * @package App\DataFixtures
 */
class UserFixtures extends Fixture implements DependentFixtureInterface
{
    /** @var UserPasswordHasherInterface  */
    private UserPasswordHasherInterface $userPasswordHasher;

    /** @var RolesRepository  */
    private RolesRepository $rolesRepository;

    /**
     * UserFixtures constructor.
     * @param UserPasswordHasherInterface $userPasswordHasher
     * @param RolesRepository $rolesRepository
     */
    public function __construct(UserPasswordHasherInterface $userPasswordHasher, RolesRepository $rolesRepository)
    {
        $this->userPasswordHasher = $userPasswordHasher;
        $this->rolesRepository = $rolesRepository;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('test@mail.com');
        $user->setRoles($this->rolesRepository->findOneBy(['name' => 'ROLE_ADMIN']));
        $user->setPassword($this->userPasswordHasher->hashPassword(
            $user, '111111'));
        $user->setIsVerified(true);
        $user->setFullName('Test Admin');
        $user->setAddress('test address');
        $user->setZipcode('55668');
        $user->setPhone('+74848848444');

        $manager->persist($user);
        $manager->flush();
    }

    /**
     * @return string[]
     */
    public function getDependencies(): array
    {
        return [
            RolesFixtures::class,
        ];
    }
}