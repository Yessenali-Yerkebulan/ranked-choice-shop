<?php

namespace App\Utils\Manager;

use App\Entity\ProductImage;
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

    public function __construct(EntityManagerInterface $entityManager, FilesystemWorker $filesystemWorker, string $uploadsTempDir){

        $this->entityManager = $entityManager;
        $this->filesystemWorker = $filesystemWorker;
        $this->uploadsTempDir = $uploadsTempDir;
    }

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
        $imageSmall = '';

        $imageMiddleParams = [
            'width'=>430,
            'height'=>null,
            'newFolder'=>$productDir,
            'newFilename'=>sprintf('%s_%s.jpg',$filenameId, 'middle')
        ];
        $imageBigParams = [
            'width'=>800,
            'height'=>null,
            'newFolder'=>$productDir,
            'newFilename'=>sprintf('%s_%s.jpg',$filenameId, 'big')
        ];
        $imageBig = '';

        $productImage = new ProductImage();
        $productImage->setFilenameSmall($imageSmall);
        $productImage->setFilenameMiddle($imageMiddle);
        $productImage->setFilenameBig($imageBig);

        return $productImage;
    }
}