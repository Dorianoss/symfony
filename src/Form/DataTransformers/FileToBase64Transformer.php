<?php

namespace App\Form\DataTransformers;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\File;

class FileToBase64Transformer implements DataTransformerInterface
{

    /**
     * Transforms an object (issue) to a string (number).
     *
     * @param  File|null $file
     * @return string
     */
    public function transform($file)
    {
    }

    /**
     * Transforms a string (number) to an object (issue).
     *
     * @param $file
     */
    public function reverseTransform($file)
    {
        if($file instanceof \Symfony\Component\HttpFoundation\File\UploadedFile)
            return base64_encode(file_get_contents($file->getPathname()));
        else
        return;
    }
}