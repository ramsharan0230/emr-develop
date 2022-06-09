<?php
/**
 * Created by PhpStorm.
 * User: Santosh
 * Date: 11/15/2019
 * Time: 3:09 PM
 */

namespace App\Services\ImageUpload;


interface ImageUploaderInterface
{
    public function saveWebPImage($file );

    public function saveOriginalImage( $file );

    public function cropAndSaveImage( $fullImage , $imagePath , $posX1 , $posY1 , $width , $height );

    public function cropAndSaveImageThumb( $fullImage, $filename );

    public function deleteFullImage( $fileName );

    public function deleteThumbImage( $fileName );

}