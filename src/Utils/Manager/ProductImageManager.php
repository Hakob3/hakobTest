<?php

namespace App\Utils\Manager;

use App\Entity\ProductImage;
use App\Utils\File\FileSaver;
use App\Utils\Filesystem\FilesystemWorker;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class ProductImageManager extends AbstractBaseManager
{
    private FilesystemWorker $filesystemWorker;
    private FileSaver $fileSaver;

    public function __construct(EntityManagerInterface $entityManager, FileSaver $fileSaver, FilesystemWorker $filesystemWorker)
    {
        parent::__construct($entityManager);

        $this->filesystemWorker = $filesystemWorker;
        $this->entityManager = $entityManager;
        $this->fileSaver = $fileSaver;
    }

    /**
     * @return ObjectRepository
     */

    public function getRepository(): ObjectRepository
    {
        return $this->entityManager->getRepository(ProductImage::class);
    }

    /**
     * @param string $productDir
     * @param string|null $productImageFilename
     * @return ProductImage|null
     */
    public function saveImageForProduct(string $productDir, string $productImageFilename = null)
    {
        if (!$productImageFilename) {
            return null;
        }

//        $this->filesystemWorker->createNewFolder($productDir);

//        $filename = sprintf('%s_%s.jpg', uniqid(), 'image');

        $productImage = new ProductImage();

       $productImage->setFilename($productImageFilename);

        return $productImage;
    }

    /**
     * @param ProductImage $productImage
     * @param string $productDir
     */
    public function removeImageFromProduct(ProductImage $productImage, string $productDir)
    {
        $filePath = $productDir . '/' . $productImage->getFilename();
        $this->filesystemWorker->remove($filePath);

        $product = $productImage->getProduct();
        $product->removeProductImage($productImage);

        $this->entityManager->flush();
    }
}