<?php

namespace App\Helper;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class Uploader
{
    /**
     * @var String
     */
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }
    /* private $uploadsPath;

    public function __construct(string $uploadsPath)
    {
        $this->uploadsPath = $uploadsPath;
    } */

    /**
     * Uploading image file .
     */
    public function uploadFile(File $file): string
    {
        //$destination = $this->uploadsPath.$type.'/'.$folderName;
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        $file->move($this->params->get('images_directory'), $fileName);

        return $fileName;
    }
}
