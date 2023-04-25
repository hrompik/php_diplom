<?php

namespace App\Controller;

use App\Service\BannerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class BannerController extends AbstractController
{
    public function banner(BannerService $bannerService): Response
    {
        return $this->render('partial/banner.html.twig', [
            'banners' => $bannerService->getBanners(),
        ]);
    }
}
