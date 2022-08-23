<?php

namespace App\DataFixtures;

use App\Entity\Roles;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class RolesFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $roles = ['Admin' => 'ROLE_ADMIN', 'User' => 'ROLE_USER'];

        foreach ($roles as $name => $role) {
            $newRole = new Roles();
            $newRole->setName($role);
            $newRole->setDisplayName($name);

            $manager->persist($newRole);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['roles'];
    }
}