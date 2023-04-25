<?php

namespace App\EventListener;

use App\Entity\Product;
use App\Service\ProductsService;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostRemoveEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;

class ProductChanged
{
    public function __construct(private readonly ProductsService $productsService)
    {
    }

    public function postUpdate(Product $product, PostUpdateEventArgs $event): void
    {
        $this->productsService->resetProduct($product->getId());
    }

    public function postPersist(Product $product, PostPersistEventArgs $event): void
    {
        $this->productsService->resetProduct($product->getId());
    }

    public function postRemove(Product $product, PostRemoveEventArgs $event): void
    {
        $this->productsService->resetProduct($product->getId());
    }


}