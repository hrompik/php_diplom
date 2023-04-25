<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Price;
use App\Entity\Product;
use App\Service\FileUploader;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\File;

class PriceFixtures extends BaseFixtures implements DependentFixtureInterface
{


    public function loadData(ObjectManager $manager): void
    {
        foreach (ProductFixtures::$products as $product) {
            $sellers = array_rand(SellerFixtures::$sellers, rand(2, count(SellerFixtures::$sellers)));
            foreach ($sellers as $sellerKey) {
                $seller = SellerFixtures::$sellers[$sellerKey];
                $this->create(Price::class, function (Price $price) use ($manager, $product, $seller) {
                    $price
                        ->setProduct($this->getReference($product))
                        ->setSeller($this->getReference($seller))
                        ->setCost($this->faker->randomFloat(2, 100, 10000));
                });
            }
        }
    }

    public function getDependencies()
    {
        return [
            ProductFixtures::class,
            SellerFixtures::class,
        ];
    }


}
