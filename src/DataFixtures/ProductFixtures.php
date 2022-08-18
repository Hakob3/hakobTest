<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Repository\CategoryRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    private CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 4; $i++) {
            $newProduct = new Product();
            $newProduct->setTitle('test product');
            $newProduct->setPrice('555');
            $newProduct->setDescription('test');
            $newProduct->setQuantity(rand(2, 50));
            $newProduct->setIsPublished(true);
            $newProduct->setCategory($this->categoryRepository->findOneBy(['slug' => 'sneakers']));
            $manager->persist($newProduct);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
        ];
    }
}