<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Service\FileUploader;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\File;

class CategoryFixtures extends Fixture
{
    public function __construct(private FileUploader $fileUploader, private CategoryRepository $categoryRepository)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $categories = [
            'monitors' => '1.svg',
            'headphones' => '2.svg',
            'video cards' => '3.svg',
            'speakers' => '5.svg',
            'cameras' => '6.svg',
            'tablets' => '8.svg',
            'microwaves' => '9.svg',
            'teapots' => '10.svg',
            'mixer' => '12.svg',
        ];

        foreach ($categories as $name => $icon) {
            $category = new Category();
            $category
                ->setName($name)
                ->setIcon(
                    $this->fileUploader->uploadFile(
                        FileUploader::CATEGORIES_STORAGE,
                        new File(dirname(dirname(__DIR__)) . '/public/assets/img/icons/departments/' . $icon)
                    )
                )
                ->setSort(rand(1, 50));
            $manager->persist($category);
        }

        $manager->flush();
        $categories = $this->categoryRepository->findAll();
        $index = array_rand($categories);
        $category = $categories[$index];
        unset($categories[$index]);
        $parent = $categories[array_rand($categories)];
        $category->setParent($parent);
        $manager->persist($category);
        $manager->flush();
    }

}
