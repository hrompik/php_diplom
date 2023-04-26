<?php

namespace App\EventListener;

use App\Entity\Product;
use App\Service\ProductsService;
use App\Service\TopService;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostRemoveEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;

class ProductChanged
{
    public function __construct(
        private readonly ProductsService $productsService,
        private readonly TopService $topService
    ) {
    }


    public function postUpdate(Product $product, PostUpdateEventArgs $event): void
    {
        $this->productsService->resetProduct($product->getId());
        $this->topService->resetTop();
    }

    public function postPersist(Product $product, PostPersistEventArgs $event): void
    {
        $this->productsService->resetProduct($product->getId());
        $this->topService->resetTop();
    }

    public function postRemove(Product $product, PostRemoveEventArgs $event): void
    {
        $this->productsService->resetProduct($product->getId());
        $this->topService->resetTop();
    }


}