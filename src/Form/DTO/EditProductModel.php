<?php

namespace App\Form\DTO;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class EditProductModel
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    #[Assert\NotBlank(message: 'Please enter a title')]
    public $title;

    /**
     * @var string
     */
    #[Assert\NotBlank(message: 'Please enter a price')]
    #[Assert\GreaterThanOrEqual(value: 0)]
    public $price;

    /**
     * @var int
     */
    #[Assert\NotBlank(message: 'Please indicate the quantity')]
    public $quantity;

    /**
     * @var UploadedFile|null
     */
    #[Assert\File(maxSize: '5024k', mimeTypes: ['image/jpeg', 'image/png'], mimeTypesMessage: 'Please upload a valid image')]
    public $newImage;

    /**
     * @var string
     */
    public $description;

    /**
     * @var Category
     */
    #[Assert\NotBlank(message: 'Please select a category')]
    public $category;

    /**
     * @var bool
     */
    public $isDeleted;

    /**
     * @var bool
     */
    public $isPublished;

    public static function makeFromProduct(?Product $product): self
    {
        $model = new self();

        if(!$product){
            return $model;
        }

        $model->id = $product->getId();
        $model->title = $product->getTitle();
        $model->price = $product->getPrice();
        $model->quantity = $product->getQuantity();
        $model->description = $product->getDescription();
        $model->isDeleted = $product->isIsDeleted();
        $model->isPublished = $product->isIsPublished();

        return $model;
    }
}