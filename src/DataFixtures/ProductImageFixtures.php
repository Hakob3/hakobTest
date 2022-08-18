<?php

namespace App\DataFixtures;

use App\Entity\ProductImage;
use App\Repository\ProductRepository;
use App\Utils\Filesystem\FilesystemWorker;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductImageFixtures extends Fixture implements DependentFixtureInterface
{
    private ProductRepository $productRepository;
    private SluggerInterface $slugger;
    private string $productImagesDir;
    private FilesystemWorker $filesystemWorker;

    public function __construct(ProductRepository $productRepository, SluggerInterface $slugger, FilesystemWorker $filesystemWorker, string $productImagesDir)
    {
        $this->productRepository = $productRepository;
        $this->slugger = $slugger;
        $this->productImagesDir = $productImagesDir;
        $this->filesystemWorker = $filesystemWorker;
    }

    public function load(ObjectManager $manager)
    {
        $filesystem = new Filesystem();

        foreach ($this->productRepository->findAll() as $product) {
            $originalFilename = pathinfo('/home/user/Projects/akobTest/src/DataFixtures/fixtureFiles/images/376203-4k-wallpaper-62f99ac338163.jpg', PATHINFO_FILENAME);
            $safeFilename = $this->slugger->slug($originalFilename);

            $filename = sprintf('%s-%s.%s', $safeFilename, uniqid(), 'jpg');

            $this->filesystemWorker->createNewFolder($this->productImagesDir);

            $filepath = $this->productImagesDir . '/' . $product->getId();

            $filesystem->copy('src/DataFixtures/fixtureFiles/images/376203-4k-wallpaper-62f99ac338163.jpg', $filepath . '/' . $filename, true);

            $productImages = new ProductImage();
            $productImages->setProduct($product);
            $productImages->setFilename($filename);
            $manager->persist($productImages);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ProductFixtures::class,
        ];
    }
}