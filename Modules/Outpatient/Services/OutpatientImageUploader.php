<?php

namespace Modules\Outpatient\Services;

use App\Services\ImageUpload\AbstractImageUploader;
use App\Services\ImageUpload\Strategy\IUploadStrategy;

class OutpatientImageUploader extends AbstractImageUploader
{
    const FULL_IMG_FOLDER = "/uploads/outpatient/full/";

    const THUMB_IMG_FOLDER = "/uploads/outpatient/thumb/";

    const CROP_IMG_FOLDER = "/uploads/outpatient/crop/";

    const RES_HEIGHT = NULL;

    const RES_WIDTH = 2000;

    const THB_HEIGHT = NULL;

    const THB_WIDTH = 400;

    protected $aspectRatioStrategy;

    /**
     * SportImageUploader constructor.
     * @param IUploadStrategy|null $strategy
     */
    public function __construct(IUploadStrategy $strategy = null)
    {
        $this->aspectRatioStrategy = $strategy;

        $this->fullImageFolder = self::FULL_IMG_FOLDER;

        $this->thumbImageFolder = self::THUMB_IMG_FOLDER;

        $this->croppedImageFolder = self::CROP_IMG_FOLDER;

        $this->resWidth = self::RES_WIDTH;

        $this->resHeight = self::RES_HEIGHT;
    }

    /**
     * @param $fullImage
     * @param $imagePath
     * @param $posX1
     * @param $posY1
     * @param $width
     * @param $height
     */
    public function cropAndSaveImage($fullImage, $imagePath, $posX1, $posY1, $width, $height)
    {
        $this->aspectRatioStrategy
            ->cropAndSaveImage(
                $fullImage,
                $imagePath,
                $posX1,
                $posY1,
                $width,
                $height,
                $this->resWidth,
                $this->resHeight
            );
    }

}