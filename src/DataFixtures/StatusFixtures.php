<?php

namespace App\DataFixtures;

use App\Entity\Status;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class StatusFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        foreach (Status::STATUS as $status) {
            $s = new Status();
            $s->setTitle($status);
            $manager->persist($s);
        }

        $manager->flush();
    }
}
