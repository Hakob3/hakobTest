<?php

namespace App\DataFixtures;

use App\Entity\Colors;
use App\Entity\Geometry;
use App\Entity\Record;
use App\Entity\User;
use App\Repository\ColorsRepository;
use App\Repository\GeometryRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordEncoder;
    private ColorsRepository $colorsRepository;
    private GeometryRepository $geometryRepository;

    public function __construct(
        UserPasswordHasherInterface $passwordEncoder,
        ColorsRepository $colorsRepository,
        GeometryRepository $geometryRepository)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->colorsRepository = $colorsRepository;
        $this->geometryRepository = $geometryRepository;
    }

    public function load(ObjectManager $manager): void
    {
        $colorsArr = ['red', 'blue', 'green'];
        $geometriesArr = ['square', 'circle', 'triangle'];

        foreach ($colorsArr as $colorArr) {
            $color = new Colors();
            $color->setColor($colorArr);
            $manager->persist($color);
        }

        foreach ($geometriesArr as $geometryArr) {
            $geometry = new Geometry();
            $geometry->setGeometry($geometryArr);
            $manager->persist($geometry);
        }

        $manager->flush();

        for ($i = 0; $i < 20; $i++){
            $record = new Record();
            $record->setText('Write a blog post' . $i);
            $record->setEmail('record'. $i .'@test.com');
            $record->setColor($this->colorsRepository->findOneBy(['color' => 'red']));
            $record->setGeometry($this->geometryRepository->findOneBy(['geometry' => 'circle']));

            $manager->persist($record);
        }

        $user = new User();

        $user->setName('Test Name');
        $user->setEmail('test@mail.com');
        $user->setPassword($this->passwordEncoder->hashPassword($user, 'password'));
        $user->setRoles(['ROLE_ADMIN']);

        $manager->persist($user);

        $manager->flush();
    }
}
