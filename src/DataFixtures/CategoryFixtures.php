<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Service\CategoriesService;
use App\Service\FileUploader;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\File;

class CategoryFixtures extends BaseFixtures
{
    public static $categories = [];

    public function __construct(
        private readonly FileUploader $fileUploader,
        private readonly CategoryRepository $categoryRepository,
        private readonly CategoriesService $categoriesService,
    ) {
    }

    public function loadData(ObjectManager $manager): void
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
            'others' => '12.svg',
        ];

        $i = 1;
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
            self::$categories[] = 'Category' . $i;
            $this->addReference('Category' . $i, $category);
            $i++;
        }
        array_pop(self::$categories);

        $manager->flush();

        $categories = $this->categoryRepository->findAll();
        $index = array_rand($categories);
        $category = $categories[$index];
        unset($categories[$index]);
        $parent = $categories[array_rand($categories)];
        $category->setParent($parent);
        $manager->persist($category);
        $manager->flush();

        $this->categoriesService->resetMenuCategories();

    }

}
