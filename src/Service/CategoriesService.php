<?php

namespace App\Service;

use App\Repository\CategoryRepository;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Contracts\Cache\ItemInterface;

class CategoriesService
{

    public function __construct(
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
        return $this->cache->get('menu', function (ItemInterface $item) {
            $item->expiresAfter(24*60*60);
            return $this->categoryRepository->getMenuCategories();
        });
    }
}
