<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
    ) {}

    public function load(ObjectManager $manager): void
    {
        // Compte ADMINISTRATEUR
        $admin = new User();
        $admin->setEmail('admin@atelier-antan.fr');
        $admin->setFirstname('John');
        $admin->setLastname('Doe');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setCreatedAt(new \DateTimeImmutable());
        $admin->setPassword(
            $this->passwordHasher->hashPassword($admin, 'admin123')
        );
        $manager->persist($admin);

        // Compte CLIENT de test
        $client = new User();
        $client->setEmail('client@exemple.fr');
        $client->setFirstname('Jean');
        $client->setLastname('Dupont');
        $client->setRoles(['ROLE_USER']);
        $client->setCreatedAt(new \DateTimeImmutable());
        $client->setPassword(
            $this->passwordHasher->hashPassword($client, 'client123')
        );
        $manager->persist($client);

        $manager->flush();
    }
}
