<?php

namespace App\Controller;

use App\Service\CategoriesService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class MenuController extends AbstractController
{
    public function menu(CategoriesService $categories): Response
    {
        return $this->render('partial/menu.html.twig', [
            'categories' => $categories->getMenuCategories(),
        ]);
    }
}
