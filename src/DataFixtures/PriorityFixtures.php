<?php

namespace App\DataFixtures;

use App\Entity\Priority;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PriorityFixtures extends Fixture
{
    const PRIORITIES = [
        'Normal',
        'Urgent',
        'Critique',
        'Bloquant',
        'Immédiate',
        'Faible'
    ];
    public function load(ObjectManager $manager): void
    {
        foreach (self::PRIORITIES as $priority) {
            $p = new Priority();
            $p->setTitle($priority);
            $manager->persist($p);
        }

        $manager->flush();
    }
}
