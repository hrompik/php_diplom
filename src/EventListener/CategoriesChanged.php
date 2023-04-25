<?php

namespace App\EventListener;

use App\Entity\Category;
use App\Service\CategoriesService;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostRemoveEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;

class CategoriesChanged
{
    public function __construct(private readonly CategoriesService $categoriesService)
    {
    }

    public function postUpdate(Category $category, PostUpdateEventArgs $event): void
    {
        $this->categoriesService->resetMenuCategories();
    }

    public function postPersist(Category $category, PostPersistEventArgs $event): void
    {
        $this->categoriesService->resetMenuCategories();
    }

    public function postRemove(Category $category, PostRemoveEventArgs $event): void
    {
        $this->categoriesService->resetMenuCategories();
    }


}