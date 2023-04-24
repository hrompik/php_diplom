<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class MenuController extends AbstractController
{
    public function menu(CategoryRepository $categoryRepository): Response
    {
        return $this->render('partial/menu.html.twig', [
            'categories' => $categoryRepository->getMenuCategories(),
        ]);
    }
}
