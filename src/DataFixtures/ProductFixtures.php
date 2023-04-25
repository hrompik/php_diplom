<?php

namespace App\DataFixtures;

use App\Entity\Feedback;
use App\Entity\Product;
use App\Entity\ProductImage;
use App\Service\FileUploader;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\File;

class ProductFixtures extends BaseFixtures implements DependentFixtureInterface
{
    public static $products = [];

    private static $name = [
        'Ноутбук',
        'Мышка',
        'Видео карта',
        'Чайник',
        'Стол',
        'Аквариум',
        'SSD',
        'Монитор',
    ];
    private static $color = [
        'красный',
        'белый',
        'черный',
        'желтый',
        'синий',
        'зелёный',
        'серый',
    ];
    private static $size = [
        'маленький',
        'средний',
        'обычный',
        'большой',
    ];

    public function __construct(private readonly FileUploader $fileUploader)
    {
    }

    private $images = [
        'sale/product.png',
        'home/bigGoods.png',
        'home/card.jpg',
        'home/slider.png',
        'home/videoca.png',
    ];

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
                    ->setSort(rand(1, 50))
                    ->setSold(rand(1, 500));
            });

            for ($j = 0; $j < rand(1, 5); $j++) {
                $image = new ProductImage();

                $image->setImg(
                    $this->fileUploader->uploadFile(
                        FileUploader::PRODUCTS_STORAGE,
                        new File(
                            dirname(
                                dirname(__DIR__)
                            ) . '/public/assets/img/content/' . $this->faker->randomElement($this->images)
                        )
                    )
                )->setProduct($product);
                $manager->persist($image);
            }

            for ($j = 0; $j < rand(1, 5); $j++) {
                $feedback = new Feedback();
                $feedback->setProduct($product)
                    ->setText($this->faker->realText(200))
                    ->setCreatedAt($this->faker->dateTimeBetween('-100 days', '-1 days'))
                    ->setCreatedBy($this->getReference($this->faker->randomElement(UserFixtures::$users)));
                $manager->persist($feedback);
            }

            $manager->flush();


            self::$products[] = 'Product' . $i;
            $this->addReference('Product' . $i, $product);
        }
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
            UserFixtures::class,
        ];
    }


}
