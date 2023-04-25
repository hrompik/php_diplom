<?php

namespace App\EventListener;

use App\Entity\Banner;
use App\Service\BannerService;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostRemoveEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;

class BannerChanged
{
    public function __construct(private readonly BannerService $bannerService)
    {
    }

    public function postUpdate(Banner $banner, PostUpdateEventArgs $event): void
    {
        $this->bannerService->resetBanners();
    }

    public function postPersist(Banner $banner, PostPersistEventArgs $event): void
    {
        $this->bannerService->resetBanners();
    }

    public function postRemove(Banner $banner, PostRemoveEventArgs $event): void
    {
        $this->bannerService->resetBanners();
    }


}