<?php

namespace App\Controller;

use App\Service\CatalogService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CatalogController extends AbstractController
{
    #[Route('/catalog', name: 'app_catalog')]
    public function index(Request $request, PaginatorInterface $paginator, CatalogService $catalogService): Response
    {
        $pagination = $paginator->paginate(
            $catalogService->findAllWithSearchQuery($request),
            $request->query->getInt('page', 1)
        );

        [
            'min' => $priceMin,
            'max' => $priceMax,
            'sellers' => $sellers
        ]
            = $catalogService->getMinMaxPriceSellers(
            $request
        );
        return $this->render('catalog/index.html.twig', [
            'pagination' => $pagination,
            'priceMin' => $priceMin,
            'priceMax' => $priceMax,
            'sellers' => $sellers,
        ]);
    }

    #[Route('/catalog/{id}', name: 'app_catalog_category')]
    public function category(int $id): Response
    {
        return $this->render('catalog/index.html.twig', [
            'controller_name' => 'CatalogController' . $id,
        ]);
    }
}
