<?php

namespace App\Service;

use App\Repository\ProductParamsRepository;
use App\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class CatalogService
{

    public function __construct(
        private readonly int $time,
        private readonly TagAwareCacheInterface $cache,
        private readonly ProductRepository $productRepository,
        private readonly ProductParamsRepository $productParamsRepository,
        private readonly PaginatorInterface $paginator

    ) {
    }

    public function resetCatalogCahce()
    {
        $this->cache->invalidateTags(['catalogs']);
    }

    public function getAll(Request $request)
    {
        $uid = 'Catalog' . print_r($request->query, true);

        return $this->cache->get(
            $uid,
            function (ItemInterface $item) use ($request) {
                $item->tag('catalogs');
                $item->expiresAfter($this->time);

                $pagination = $this->paginator->paginate(
                    $this->findAllWithSearchQuery($request),
                    $request->query->getInt('page', 1)
                );

                [
                    'min' => $min,
                    'max' => $max,
                    'sellers' => $sellers,
                    'colors' => $colors,
                ] = $this->getFilters($request);

                return [
                    'priceMin' => $min,
                    'priceMax' => $max,
                    'sellers' => $sellers,
                    'pagination' => $pagination,
                    'colors' => $colors
                ];
            }
        );
    }

    public function findAllWithSearchQuery(Request $request)
    {
        $products = $this->productRepository->findIdWithAvgPrice(
            $request->query->get('priceFrom'),
            $request->query->get('priceTo'),
        );
        $ids = [];
        foreach ($products as $product) {
            $ids[] = $product[0]->getId();
        }

        return $this->productRepository->findAllWithSearchQuery(
            $request->query->get('q'),
            $request->query->get('title'),
            $request->query->get('sorted'),
            $ids,
            $request->query->get('sellerId'),
            $request->query->get('colors'),
            $request->query->get('crackedScreen'),
            $request->query->get('categoryId'),
        );
    }

    private function getFilters(Request $request): array
    {
        $products = $this->productRepository->findAllWithSearchQuery(
            $request->query->get('q'),
            $request->query->get('title'),
        )
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

            $colors = $this->productParamsRepository->findColorsWithSearchQuery(
                $request->query->get('q'),
                $request->query->get('title'),
            );

            return [
                'min' => intval(floor($min)),
                'max' => intval(ceil($max)),
                'sellers' => $sellers,
                'colors' => $colors
            ];
        }
        return ['min' => 0, 'max' => 0, 'sellers' => [], 'colors' => []];
    }


}
