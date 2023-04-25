<?php

namespace App\Service;

use App\Repository\BannerRepository;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Contracts\Cache\ItemInterface;

class BannerService
{

    public function __construct(
        private readonly int $time,
        private readonly CacheItemPoolInterface $cache,
        private readonly BannerRepository $bannerRepository
    ) {
    }

    public function resetBanners()
    {
        $this->cache->deleteItem('Banners');
    }

    public function getBanners()
    {
        return $this->cache->get('Banners', function (ItemInterface $item) {
            $item->expiresAfter($this->time);
            return $this->bannerRepository->getBanners();
        });
    }

}
