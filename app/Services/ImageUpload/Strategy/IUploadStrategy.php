<?php
namespace App\Services\ImageUpload\Strategy;


interface IUploadStrategy
{
    public function cropAndSaveImage(
        $fullImage,
        $imagePath,
        $posX1,
        $posY1,
        $width,
        $height,
        $resolutionWidth,
        $resolutionHeight
    );
}