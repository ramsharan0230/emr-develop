<?php

namespace App\Services\ImageUpload;


abstract class AbstractImageUploader implements ImageUploaderInterface
{
    protected $fullImageFolder;

    protected $thumbImageFolder;

    protected $croppedImageFolder;

    protected $resHeight;

    protected $resWidth;

    public function __construct()
    {

    }

    public function saveOriginalImage($file)
    {
        // echo $file; exit;
        $fileOriginal = $file->getClientOriginalName();

        $extension = $file->getClientOriginalExtension();

        $filename = pathinfo($fileOriginal, PATHINFO_FILENAME);

        $filename = time() . '_' . str_random(5) . '_' . str_slug($filename) . '.' . $extension;
        // echo $filename; exit;
        $file->move(public_path($this->fullImageFolder), $filename);

        return $filename;
    }

    public function saveWebPImage($file)
    {
        $fileOriginal = $file->getClientOriginalName();

        $extension = pathinfo($fileOriginal, PATHINFO_EXTENSION);

        $tempUploadFilePath = $file->getPathname();

        $imageCreatedFromResource = $this->createImageFromResource($tempUploadFilePath, $extension);

        $fullImageDestination = $this->getFullImageFolderPath();

        $filename = pathinfo($fileOriginal, PATHINFO_FILENAME);

        $filename = time() . '_' . str_random(5) . '_' . str_slug($filename) . '.webp';

        imagewebp($imageCreatedFromResource, $fullImageDestination . '/' . $filename);

        return $filename;
    }

    abstract function cropAndSaveImage($fullImage, $imagePath, $posX1, $posY1, $width, $height);

    public function cropAndSaveImageThumb($fullImage, $filename)
    {
        $fullImage->resize( $this->resHeight, $this->resWidth , function ( $constraint ) {

            $constraint->aspectratio();

            $constraint->upsize();

        })->save( $this->getThumbImagePath( $filename ) );
    }

    public function deleteFullImage( $fileName )
    {
        $fullImage = public_path($this->fullImageFolder . $fileName);

        if(file_exists($fullImage) && is_file($fullImage)){
            unlink($fullImage);
        }
    }

    public function deleteThumbImage( $fileName )
    {
        $thumbImage = public_path($this->thumbImageFolder . $fileName);

        if(file_exists($thumbImage) && is_file($thumbImage)){
            unlink($thumbImage);
        }
    }

    public function deleteCroppedImage( $filename ){
        $croppedImage = public_path($this->croppedImageFolder . $filename);

        if(file_exists($croppedImage) && is_file($croppedImage)){
            unlink($croppedImage);
        }
    }

    public function getFullImagePath( $filename )
    {
        return public_path( $this->fullImageFolder . $filename );
    }

    public function getThumbImagePath( $filename )
    {
        return public_path( $this->thumbImageFolder . $filename );
    }

    public function getCroppedImagePath( $filename )
    {
        return public_path( $this->croppedImageFolder . $filename );
    }

    protected function getFullImageFolderPath()
    {
        return public_path($this->fullImageFolder);
    }

    protected function getThumbImageFolderPath()
    {
        return public_path($this->thumbImageFolder);
    }

    protected function getCroppedImageFolderPath()
    {
        return public_path($this->croppedImageFolder);
    }

    protected function createImageFromResource($tempPath, $extension)
    {
       $extension = strtolower($extension);

        switch ($extension) {
            case 'jpeg':

                $imageCreatedFromResource = imagecreatefromjpeg($tempPath);

                break;

            case 'jpg':

                $imageCreatedFromResource = imagecreatefromjpeg($tempPath);

                break;

            case 'png':

                $imageCreatedFromResource = imagecreatefrompng($tempPath);

                break;

            default:

                die('Invalid image type');

        }

        return $imageCreatedFromResource;
    }

}