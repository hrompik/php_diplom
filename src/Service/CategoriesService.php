<?php

namespace App\Service;

use App\Repository\CategoryRepository;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Contracts\Cache\ItemInterface;

class CategoriesService
{

    public function __construct(
        private readonly int $time,
        private readonly CacheItemPoolInterface $cache,
        private readonly CategoryRepository $categoryRepository
    ) {
    }


    public function resetMenuCategories(): void
    {
        $this->cache->deleteItem('menu');
    }


    public function getMenuCategories()
    {
        #TODO УБрать доп время
        return $this->cache->get('menu', function (ItemInterface $item) {
            $item->expiresAfter($this->time);
            return $this->categoryRepository->getMenuCategories();
        });
    }
}
