<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends BaseFixtures
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public static $users = [];

    public function loadData(ObjectManager $manager): void
    {
        $this->create(User::class, function (User $user) use ($manager) {
            $user
                ->setEmail('admin@symfony.skillbox')
                ->setFio('Администратор')
                ->setPhone('+7 (111) 111-11-11')
                ->setPassword($this->passwordHasher->hashPassword($user, '123456'))
                ->setRoles(['ROLE_ADMIN']);
        });


        for ($i = 0; $i < 9; $i++) {
            $user = $this->create(User::class, function (User $user) use ($manager, $i) {
                $user
                    ->setEmail($this->faker->email)
                    ->setFio($this->faker->name)
                    ->setPhone('+7 (222) 222-22-2' . $i)
                    ->setPassword($this->passwordHasher->hashPassword($user, '123456'))
                    ->setRoles(['ROLE_USER']);
            });
            self::$users[] = 'User' . $i;
            $this->addReference('User' . $i, $user);
        }
    }
}
