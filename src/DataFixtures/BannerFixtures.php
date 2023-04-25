<?php

namespace App\DataFixtures;


use App\Entity\Banner;
use App\Service\FileUploader;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\File;

class BannerFixtures extends BaseFixtures
{
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
        for ($i = 0; $i < 10; $i++) {
            $this->create(Banner::class, function (Banner $seller) use ($manager) {
                $seller
                    ->setTitle($this->faker->colorName)
                    ->setText($this->faker->realText(200))
                    ->setImg(
                        $this->fileUploader->uploadFile(
                            FileUploader::BANNERS_STORAGE,
                            new File(
                                dirname(
                                    dirname(__DIR__)
                                ) . '/public/assets/img/content/' . $this->faker->randomElement($this->images)
                            )
                        )
                    );
            });
        }
    }

}
