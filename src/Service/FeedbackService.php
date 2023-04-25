<?php

namespace App\Service;

use App\Repository\ProductRepository;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Contracts\Cache\ItemInterface;

class FeedbackService
{

    public function __construct(
        private readonly int $time,
        private readonly CacheItemPoolInterface $cache,
        private readonly ProductRepository $productRepository
    ) {
    }

    public function resetTop()
    {
        $this->cache->deleteItem('Top');
    }

    public function getTop()
    {
        return $this->cache->get('Top', function (ItemInterface $item) {
            $item->expiresAfter($this->time);
            return $this->productRepository->getTop();
        });
    }

}
