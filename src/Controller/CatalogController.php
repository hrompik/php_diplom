<?php

namespace App\Controller;

use App\Service\CatalogService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CatalogController extends AbstractController
{
    #[Route('/catalog', name: 'app_catalog')]
    public function index(Request $request, CatalogService $catalogService): Response
    {
        return $this->render('catalog/index.html.twig', $catalogService->getAll($request));
    }

    #[Route('/catalog/{id}', name: 'app_catalog_category')]
    public function category(int $id): Response
    {
        return $this->render('catalog/index.html.twig', [
            'controller_name' => 'CatalogController' . $id,
        ]);
    }
}
