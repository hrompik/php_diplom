<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Service\FileUploader;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\File;

class ProductFixtures extends BaseFixtures implements DependentFixtureInterface
{
    public static $products = [];

    private static $name = [
        'Laptop',
        'Mouser',
        'Video card',
        'Teapot',
        'Spoon',
        'Table',
        'SSD',
        'Monitor',
    ];
    private static $color = [
        'red',
        'white',
        'black',
        'yellow',
        'blue',
        'green',
        'gray',
    ];
    private static $size = [
        'small',
        'medium',
        'little',
        'big',
    ];

    public function __construct(private readonly FileUploader $fileUploader)
    {
    }

    public function loadData(ObjectManager $manager): void
    {
        for ($i = 0; $i < 50; $i++) {
            $product = $this->create(Product::class, function (Product $product) use ($manager) {
                $product
                    ->setName(
                        $this->faker->randomElement(self::$name) . ' ' . $this->faker->randomElement(
                            self::$color
                        ) . ' ' . $this->faker->randomElement(self::$size)
                    )
                    ->setDescription($this->faker->realText(200))
                    ->setCategory($this->getReference($this->faker->randomElement(CategoryFixtures::$categories)))
                    ->setImg(
                        $this->fileUploader->uploadFile(
                            FileUploader::PRODUCTS_STORAGE,
                            new File(
                                dirname(dirname(__DIR__)) . '/public/assets/img/content/sale/product.png'
                            )
                        )
                    );
            });
            self::$products[] = 'Product' . $i;
            $this->addReference('Product' . $i, $product);
        }
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
        ];
    }


}
