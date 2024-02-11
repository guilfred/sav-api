<?php

namespace App\EventDoctrine;
use App\Entity\Account;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: Account::class)]
class CreateAccount
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher) {}

    public function postPersist(Account $account, PostPersistEventArgs $args): void
    {
        $hashedPassword = $this->passwordHasher->hashPassword($account, $account->getPassword());
        $account->setPassword($hashedPassword);
        $args->getObjectManager()->flush();
    }
}

