<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

                $contributor = new User();
                $contributor->setEmail($faker->sentence(4));
                $contributor->setRoles(['ROLE_CONTRIBUTOR']);
                $hashedPassword = $this->passwordHasher->hashPassword(
                $contributor,
                'contributorpassword'
                );
                $contributor->setPassword($hashedPassword);
                $manager->persist($contributor);

                $admin = new User();
                $admin->setEmail('admin@monsite.com');
                $admin->setRoles(['ROLE_ADMIN']);
                $hashedPassword = $this->passwordHasher->hashPassword(
                $admin,
                'adminpassword'
                );
                $admin->setPassword($hashedPassword);
                $manager->persist($admin);
                $this->addReference('user_', $contributor);

        $manager->flush();
    }
}
