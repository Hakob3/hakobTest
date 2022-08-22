<?php

namespace App\Utils\Filesystem;

use Symfony\Component\Filesystem\Filesystem;

class FilesystemWorker
{
    private Filesystem $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @param string $folder
     */
    public function createNewFolder(string $folder)
    {
        if (!$this->filesystem->exists($folder)) {
            $this->filesystem->mkdir($folder);
        }
    }

    public function remove(string $item)
    {
        if($this->filesystem->exists($item)){
            $this->filesystem->remove($item);
        }
    }
}