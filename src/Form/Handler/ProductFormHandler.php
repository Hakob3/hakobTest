<?php

namespace App\Form\Handler;

use App\Entity\Product;
use App\Form\DTO\EditProductModel;
use App\Utils\File\FileSaver;
use App\Utils\Manager\ProductManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductFormHandler
{
    private FileSaver $fileSaver;
    private ProductManager $productManager;
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger, ProductManager $productManager, FileSaver $fileSaver)
    {
        $this->fileSaver = $fileSaver;
        $this->productManager = $productManager;
        $this->slugger = $slugger;
    }

    /**
     * @param EditProductModel $editProductModel
     * @param Form $form
     * @return Product|null
     */
    public function processEditForm(EditProductModel $editProductModel, Form $form): ?Product
    {
        $product = new Product();

        if ($editProductModel->id) {
            $product = $this->productManager->find($editProductModel->id);
        }

        $product->setTitle($editProductModel->title);
        $product->setPrice($editProductModel->price);
        $product->setQuantity($editProductModel->quantity);
        $product->setDescription($editProductModel->description);
        $product->setCategory($editProductModel->category);
        $product->setIsDeleted($editProductModel->isDeleted);
        $product->setIsPublished($editProductModel->isPublished);

        $this->productManager->save($product);

        $newImageFile = $form->get('newImage')->getData();

        if ($newImageFile) {
            $originalFilename = pathinfo($newImageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $this->slugger->slug($originalFilename);

            $filename = sprintf('%s-%s.%s', $safeFilename, uniqid(), $newImageFile->guessClientExtension());

            $productImageFilename = $this->fileSaver->saveUploadedFileIntoTemp($newImageFile, $product, $filename);

            $this->productManager->updateProductImages($product, $productImageFilename);
        }


        $this->productManager->save($product);

        return $product;
    }
}