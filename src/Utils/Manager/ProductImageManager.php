<?php

namespace App\Utils\Manager;

use App\Entity\ProductImage;
use App\Utils\File\ImageResizer;
use App\Utils\Filesystem\FilesystemWorker;
use Doctrine\ORM\EntityManagerInterface;

class ProductImageManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var FilesystemWorker
     */
    private $filesystemWorker;
    /**
     * @var string
     */
    private $uploadsTempDir;
    /**
     * @var ImageResizer
     */
    private $imageResizer;

    public function __construct(EntityManagerInterface $entityManager, FilesystemWorker $filesystemWorker, ImageResizer $imageResizer, string $uploadsTempDir){

        $this->entityManager = $entityManager;
        $this->filesystemWorker = $filesystemWorker;
        $this->uploadsTempDir = $uploadsTempDir;
        $this->imageResizer = $imageResizer;
    }

    /**
     * @param string $productDir
     * @param string|null $tempImageFileName
     * @return ProductImage|null
     */
    public function saveImageForProduct(string $productDir, string $tempImageFileName = null){
        if(!$tempImageFileName){
            return null;
        }

        $this->filesystemWorker->createFolderIfItNotExist($productDir);

        $filenameId = uniqid();
        $imageSmallParams = [
            'width'=>60,
            'height'=>null,
            'newFolder'=>$productDir,
            'newFilename'=>sprintf('%s_%s.jpg',$filenameId, 'small')
        ];
        $imageSmall = $this->imageResizer->resizeImageAndSave($this->uploadsTempDir, $tempImageFileName, $imageSmallParams);

        $imageMiddleParams = [
            'width'=>430,
            'height'=>null,
            'newFolder'=>$productDir,
            'newFilename'=>sprintf('%s_%s.jpg',$filenameId, 'middle')
        ];
        $imageMiddle = $this->imageResizer->resizeImageAndSave($this->uploadsTempDir, $tempImageFileName, $imageMiddleParams);

        $imageBigParams = [
            'width'=>800,
            'height'=>null,
            'newFolder'=>$productDir,
            'newFilename'=>sprintf('%s_%s.jpg',$filenameId, 'big')
        ];
        $imageBig = $this->imageResizer->resizeImageAndSave($this->uploadsTempDir, $tempImageFileName, $imageBigParams);

        $productImage = new ProductImage();
        $productImage->setFilenameSmall($imageSmall);
        $productImage->setFilenameMiddle($imageMiddle);
        $productImage->setFilenameBig($imageBig);

        return $productImage;
    }
}