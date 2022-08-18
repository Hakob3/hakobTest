<?php

namespace App\Utils\File;

use App\Entity\Product;
use App\Utils\Filesystem\FilesystemWorker;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileSaver
{
    /**
     * @var FilesystemWorker
     */
    private FilesystemWorker $filesystemWorker;
    private string $productImagesDir;

    public function __construct( FilesystemWorker $filesystemWorker, string $productImagesDir)
    {
        $this->filesystemWorker = $filesystemWorker;
        $this->productImagesDir = $productImagesDir;
    }

    /**
     * @param UploadedFile $uploadedFile
     * @param Product $product
     * @param string $filename
     * @return string|null
     */
    public function saveUploadedFileIntoTemp(UploadedFile $uploadedFile, Product $product, string $filename): ?string
    {
        $this->filesystemWorker->createNewFolder($this->productImagesDir);

        $filepath = $this->productImagesDir . '/' . $product->getId();

        try {
            $uploadedFile->move($filepath, $filename);
        } catch (FileException $exception) {
            return null;
        }

        return $filename;
    }
}