<?php

namespace App\Service;

use App\Repository\ProductRepository;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\HttpFoundation\Request;

class CatalogService
{

    public function __construct(
        private readonly int $time,
        private readonly CacheItemPoolInterface $cache,
        private readonly ProductRepository $productRepository
    ) {
    }

    public function findAllWithSearchQuery(Request $request)
    {
        $products = $this->productRepository->findIdWithAvgPrice(
            $request->query->get('priceFrom'),
            $request->query->get('priceTo'),
        );
        $ids = [];
        foreach ($products as $product){
            $ids[] = $product[0]->getId();
        }

        return $this->productRepository->findAllWithSearchQuery(
            $request->query->get('q'),
            $request->query->get('title'),
            $request->query->get('sorted'),
            $ids,
        );
    }

    public function getMinMaxPriceSellers(Request $request)
    {
        $products = $this->findAllWithSearchQuery($request)
            ->getQuery()
            ->getResult();
        if (count($products) > 0) {
            $min = PHP_INT_MAX;
            $max = PHP_INT_MIN;
            foreach ($products as $product) {
                if ($product['cost'] > $max) {
                    $max = $product['cost'];
                }
                if ($product['cost'] < $min) {
                    $min = $product['cost'];
                }

                $sellers[$product[0]->getPrices()->getValues()[0]->getSeller()->getId()] =
                    $product[0]->getPrices()->getValues()[0]->getSeller()->getName();
            }
            return ['min' => intval(floor($min)), 'max' => intval(ceil($max)), 'sellers' => $sellers];
        }
        return ['min' => 0, 'max' => 0, 'sellers' => []];
    }


}
