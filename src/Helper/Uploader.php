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
    
    /**
     * Uploading image file .
     */
    //public function uploadFile(File $file): string
    public function uploadFile(File $file): string
    {
        
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        $file->move($this->params->get('images_directory'), $fileName);

        return $fileName;
    }
}
