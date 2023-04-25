<?php

namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Contracts\Cache\ItemInterface;

class ProductsService
{

    public function __construct(
        private readonly int $time,
        private readonly CacheItemPoolInterface $cache,
        private readonly ProductRepository $productRepository
    ) {
    }

    public function resetProduct(int $id)
    {
        $this->cache->deleteItem('Product' . $id);
    }

    public function getProduct(int $id)
    {
        return $this->cache->get('Product' . $id, function (ItemInterface $item) use ($id) {
            $item->expiresAfter($this->time);
            return $this->productRepository->getProduct($id);
        });
    }

    public static function getAvgPrice(Product $product): float|int
    {
        $result = 0;
        foreach ($product->getPrices() as $price) {
            $result += $price->getCost();
        }
        return $result / count($product->getPrices());
    }
}
