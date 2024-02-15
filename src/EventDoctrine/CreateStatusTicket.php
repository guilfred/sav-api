<?php

namespace App\EventDoctrine;

use App\Entity\Status;
use App\Entity\Ticket;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Events;
use Doctrine\Persistence\ObjectManager;

#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: Ticket::class)]
class CreateStatusTicket
{
    /**
     * postPersist function description
     *
     * @param Ticket               $ticket
     * @param PostPersistEventArgs $args
     *
     * @throws void description of exception
     */
    public function postPersist(Ticket $ticket, PostPersistEventArgs $args): void
    {
        $manager = $args->getObjectManager();
        $status = $this->getStatus($manager);
        if (!$status) {
            return;
        }

        $ticket->setStatus($status);
        $manager->flush();
    }

    /**
     * Retrieve the status from the database based on the given ObjectManager.
     *
     * @param  ObjectManager $objectManager The object manager to use for database operations
     *
     * @return Status|null The status object if found, or null if not found
     */
    private function getStatus(ObjectManager $objectManager): ?Status
    {
        return $objectManager->getRepository(Status::class)->findOneBy(['title' => Status::STATUS['pending']]);
    }

}
