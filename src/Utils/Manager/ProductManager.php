<?php

namespace App\Utils\Manager;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class ProductManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var string
     */
    private $productImagesDir;

    public function __construct(EntityManagerInterface $entityManager, string $productImagesDir){

        $this->entityManager = $entityManager;
        $this->productImagesDir = $productImagesDir;
    }

    /**
     * @return ObjectRepository
     */
    public function getRepository(): ObjectRepository
    {
        return $this->entityManager->getRepository(Product::class);
    }

    /**
     * @param Product $product
     * @return void
     */
    public function save(Product $product){
        $this->entityManager->persist($product);
        $this->entityManager->flush();
    }

    public function remove(){

    }

    /**
     * @param Product $product
     * @return string
     */
    public function getProductImagesDir(Product $product){
        return sprintf('%s/%s', $this->productImagesDir, $product->getId());
    }

    public function updateProductImages(Product $product, string $tempImageFilename = null): Product
    {
        if(!$tempImageFilename){
            return $product;
        }

        $productDir = $this->getProductImagesDir($product);

        dd($productDir);
    }
}