<?php
/**
 * Created by PhpStorm.
 * User: Santosh
 * Date: 11/18/2019
 * Time: 9:36 AM
 */

namespace App\Services\ImageUpload\Strategy;


class UploadWithAspectRatio implements IUploadStrategy
{
    public function cropAndSaveImage( $fullImage , $imagePath , $posX1 , $posY1 , $width , $height , $resolutionWidth , $resolutionHeight )
    {
        $fullImage->crop( ( int ) $width , ( int ) $height , ( int ) $posX1 , ( int ) $posY1 );

        $fullImage->resize($resolutionWidth , $resolutionHeight , function ($constraint) {

            $constraint->aspectratio();

            $constraint->upsize();

        })->save( $imagePath );
    }
}