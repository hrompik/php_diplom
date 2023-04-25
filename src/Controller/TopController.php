<?php

namespace App\Controller;

use App\Service\BannerService;
use App\Service\TopService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class TopController extends AbstractController
{
    public function top(TopService $topService): Response
    {
        return $this->render('partial/top.html.twig', [
            'top' => $topService->getTop(),
        ]);
    }
}
