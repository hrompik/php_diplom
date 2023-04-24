<?php

namespace App\Service;


use League\Flysystem\FilesystemOperator;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    const AVATAR_STORAGE = 'avatarsStorage';
    const CATEGORIES_STORAGE = 'categoriesStorage';

    public function __construct(
        private FilesystemOperator $avatarsStorage,
        private FilesystemOperator $categoriesStorage,
        private SluggerInterface $slugger
    ) {
    }

    public function uploadFile(string $storageName, File $file, ?string $oldFileName = null): string
    {
        $fileName = $this->slugger
            ->slug(
                pathinfo(
                    $file instanceof UploadedFile ? $file->getClientOriginalName() : $file->getFilename(),
                    PATHINFO_FILENAME
                )
            )
            ->append('-' . uniqid())
            ->append('.' . $file->guessExtension())
            ->toString();

        $stream = fopen($file->getPathname(), 'r');

        $this->$storageName->writeStream($fileName, $stream);
        if (is_resource($stream)) {
            fclose($stream);
        }


        if ($oldFileName && $this->$storageName->has($oldFileName)) {
            $this->$storageName->delete($oldFileName);
        }

        return $fileName;
    }

}
