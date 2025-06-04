<?php

namespace App\DataFixtures;

use App\Entity\Habit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $names = ['Exercise', 'Reading', 'Meditation', 'Coding', 'Journaling'];
        $descriptions = [
            '30 minutes of cardio',
            'Read 20 pages of a book',
            '10 minutes of breathing',
            'Work on a side project',
            'Write 5 lines in your journal'
        ];

        for ($i = 0; $i < 5; $i++) {
            $habit = new Habit();
            $habit->setName($names[$i]);
            $habit->setDescription($descriptions[$i]);
            $habit->setPeriod(rand(7, 30)); // random duration in days

            // Random start date within last 10 days or null
            if (rand(0, 1)) {
                $habit->setStartDate((new \DateTime())->modify("-" . rand(0, 10) . " days"));
            } else {
                $habit->setStartDate(null);
            }

            $manager->persist($habit);
        }

        $manager->flush();
    }
}
