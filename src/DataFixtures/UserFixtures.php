<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user
            ->setEmail('admin@symfony.skillbox')
            ->setFio('Администратор')
            ->setPhone('+7 (111) 111-11-11')
            ->setPassword($this->passwordHasher->hashPassword($user, '123456'))
            ->setRoles(['ROLE_ADMIN']);

        $manager->persist($user);

        $user = new User();
        $user
            ->setEmail('user@symfony.skillbox')
            ->setFio('Иванов Иван Иванович')
            ->setPhone('+7 (222) 222-22-22')
            ->setPassword($this->passwordHasher->hashPassword($user, '123456'))
            ->setRoles(['ROLE_USER']);

        $manager->persist($user);

        $manager->flush();
    }
}
