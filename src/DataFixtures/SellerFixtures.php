<?php

namespace App\DataFixtures;


use App\Entity\Seller;
use Doctrine\Persistence\ObjectManager;

class SellerFixtures extends BaseFixtures
{
    public static $sellers = [];

    public function loadData(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; $i++) {
            $seller = $this->create(Seller::class, function (Seller $seller) use ($manager) {
                $seller
                    ->setName($this->faker->company);;
            });
            self::$sellers[] = 'Seller' . $i;
            $this->addReference('Seller' . $i, $seller);
        }
    }

}
