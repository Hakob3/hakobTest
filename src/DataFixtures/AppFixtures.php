<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\ProductImage;
use App\Entity\User;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Utils\File\FileSaver;
use App\Utils\Filesystem\FilesystemWorker;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    private CategoryRepository $categoryRepository;
    private ProductRepository $productRepository;
    private UserPasswordHasherInterface $userPasswordHasher;
    private SluggerInterface $slugger;
    private FileSaver $fileSaver;
    private FilesystemWorker $filesystemWorker;
    private string $productImagesDir;

    public function __construct(
        UserPasswordHasherInterface $userPasswordHasher,
        CategoryRepository $categoryRepository,
        ProductRepository $productRepository,
        SluggerInterface $slugger,
        FileSaver $fileSaver,
        FilesystemWorker $filesystemWorker,
        string $productImagesDir
    )
    {

        $this->categoryRepository = $categoryRepository;
        $this->productRepository = $productRepository;
        $this->userPasswordHasher = $userPasswordHasher;
        $this->slugger = $slugger;
        $this->fileSaver = $fileSaver;
        $this->filesystemWorker = $filesystemWorker;
        $this->productImagesDir = $productImagesDir;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('test@mail.com');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword($this->userPasswordHasher->hashPassword(
            $user, 'password'));
        $user->setIsVerified(true);
        $user->setFullName('Test Admin');
        $user->setAddress('test address');
        $user->setZipcode('55668');
        $user->setPhone('+74848848444');
        $manager->persist($user);

        $categories = ['jeans', 'hats', 'jackets', 'sneakers', 'dresses'];

        foreach ($categories as $category) {
            $newCategory = new Category();
            $newCategory->setTitle($category);
            $manager->persist($newCategory);
        }

        $manager->flush();

        for ($i = 0; $i < 10; $i++) {
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
}
