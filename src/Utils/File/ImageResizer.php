<?php

namespace App\Utils\File;

use Imagine\Gd\Imagine;
use Imagine\Image\Box;

class ImageResizer
{
    private $imagine;

    public function __construct()
    {
        $this->imagine = new Imagine();
    }

    /**
     * @param string $originalFileFolder
     * @param string $originalFilename
     * @param array $targetParams
     * @return string
     */

    public function resizeImageAndSave(string $originalFileFolder, string $originalFilename, array $targetParams): string
    {
        $originalFilePath = $originalFileFolder . '/' . $originalFilename;

        list($imageWidth, $imageHeight) = getimagesize($originalFilePath);

        $targetWidth = $targetParams['width'];
        $targetHeight = $targetParams['height'];
        $targetFolder = $targetParams['newFolder'];
        $targetFilename = $targetParams['newFilename'];

        $ratio = $imageWidth / $imageHeight;

        if ($targetHeight) {
            if ($targetWidth / $targetHeight > $ratio) {
                $targetWidth = $targetHeight * $ratio;
            } else {
                $targetHeight = $targetWidth / $ratio;
            }
        } else {
            $targetHeight = $targetWidth / $ratio;
        }

        $targetFilePath = sprintf('%s/%s', $targetFolder, $targetFilename);

        $imagineFile = $this->imagine->open($originalFilePath);

        $imagineFile
            ->resize(
                new Box($targetWidth, $targetHeight)
            )
            ->save($targetFilePath);

        return $targetFilename;
    }
}