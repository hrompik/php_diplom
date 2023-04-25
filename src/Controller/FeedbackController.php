<?php

namespace App\Controller;

use App\Service\FeedbackService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class FeedbackController extends AbstractController
{
    public function feedback(FeedbackService $feedbackService): Response
    {
        return $this->render('partial/top.html.twig', [
            'top' => $feedbackService->getTop(),
        ]);
    }
}
