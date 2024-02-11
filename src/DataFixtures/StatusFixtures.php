<?php

namespace App\DataFixtures;

use App\Entity\Status;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class StatusFixtures extends Fixture
{
    const STATUS = [
        'En attente de traitement',
        'En cours',
        'Résolu',
        'Rejeté',
    ];
    public function load(ObjectManager $manager): void
    {
        foreach (self::STATUS as $status) {
            $s = new Status();
            $s->setTitle($status);
            $manager->persist($s);
        }

        $manager->flush();
    }
}
