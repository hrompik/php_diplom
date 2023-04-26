<?php

namespace App\Controller;

use App\Service\FeedbackService;
use App\Service\ProductsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/product/{id}', name: 'app_product_view')]
    public function view(
        int $id,
        Request $request,
        ProductsService $productsService,
        FeedbackService $feedbackService,

    ): Response {
        $formView = $feedbackService->addFeedback($id, $request);
        $product = $productsService->getProduct($id);

        return $this->render('product/view.html.twig', [
            'product' => $product,
            'avgPrice' => ProductsService::getAvgPrice($product),
            'feedbackForm' => $formView,
        ]);
    }
}
