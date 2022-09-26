<?php

namespace App\DataFixtures;

use App\Entity\Roles;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Class RolesFixtures
 * @package App\DataFixtures
 */
class RolesFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $roles = [
            'Super Admin' => 'ROLE_SUPER_ADMIN',
            'Admin' => 'ROLE_ADMIN',
            'User' => 'ROLE_USER'
        ];

        foreach ($roles as $name => $role) {
            $newRole = new Roles();
            $newRole->setName($role);
            $newRole->setDisplayName($name);

            $manager->persist($newRole);
        }

        $manager->flush();
    }
}