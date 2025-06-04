<?php

namespace App\DataFixtures;

use App\Entity\Habit;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $usersData = [
            ['username' => 'alice', 'email' => 'alice@example.com', 'password' => 'pass123'],
            ['username' => 'bob', 'email' => 'bob@example.com', 'password' => 'pass123'],
        ];

        $habitNames = ['Exercise', 'Reading', 'Meditation', 'Coding', 'Journaling'];
        $habitDescriptions = [
            '30 minutes of cardio',
            'Read 20 pages of a book',
            '10 minutes of breathing',
            'Work on a side project',
            'Write 5 lines in your journal',
        ];
        $colors = ['#FF5733', '#33FF57', '#3357FF', '#F333FF', '#33FFF5'];

        foreach ($usersData as $userData) {
            $user = new User();
            $user->setUsername($userData['username']);
            // You don't have email field in User entity, so skip that or add it if needed
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($this->passwordHasher->hashPassword($user, $userData['password']));
            $manager->persist($user);

            // For each user, create 3 random habits
            for ($i = 0; $i < 3; $i++) {
                $habit = new Habit();
                $habit->setName($habitNames[$i]);
                $habit->setDescription($habitDescriptions[$i]);
                $habit->setPeriod(rand(7, 30));
                $habit->setColor($colors[$i]);
                $habit->setUser($user);

                if (rand(0, 1)) {
                    $habit->setStartDate((new \DateTime())->modify("-" . rand(0, 10) . " days"));
                } else {
                    $habit->setStartDate(null);
                }

                $manager->persist($habit);
            }
        }

        $manager->flush();
    }
}
