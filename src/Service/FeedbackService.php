<?php

namespace App\Service;

use App\Entity\Feedback;
use App\Entity\Product;
use App\Form\FeedbackFormType;
use App\Repository\FeedbackRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class FeedbackService extends AbstractController
{

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly ProductRepository $productRepository,
        private readonly ProductsService $productsService,
        private readonly FeedbackRepository $feedbackRepository,
    ) {
    }

    public function addFeedback(int $productId, Request $request): \Symfony\Component\Form\FormView
    {
        $form = $this->createForm(FeedbackFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Feedback $feedback */
            /** @var Product $product */
            $product = $this->productRepository->getProduct($productId);
            $feedback = $form->getData();
            $feedback
                ->setCreatedBy($this->getUser());
            $this->em->persist($feedback);
            $product = $product->addFeedback($feedback);
            $this->em->persist($product);
            $this->em->flush();
            $this->productsService->resetProduct($productId);
            $form = $this->createForm(FeedbackFormType::class);
        }
        return $form->createView();
    }

    public function getFeedbacks(int $productId): array
    {
        return $this->feedbackRepository->findBy(['product.id' => $productId]);
    }

    public function getFeedbacksCount(int $productId): int
    {
        return count($this->feedbackRepository->findBy(['product.id' => $productId]));
    }
}
