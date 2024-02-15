<?php

namespace App\Repository;

use App\Entity\Account;
use App\Entity\Profile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<Account>
 * @implements PasswordUpgraderInterface<Account>
 *
 * @method Account|null find($id, $lockMode = null, $lockVersion = null)
 * @method Account|null findOneBy(array $criteria, array $orderBy = null)
 * @method Account[]    findAll()
 * @method Account[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccountRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Account::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof Account) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function createAccount(array $data): Account
    {
        $profile = $this->createProfile($data['profile']);
        $account = new Account();
        $account
            ->setEmail($data['email'])
            ->addRole(Account::ROLES['client'])
            ->setPassword($data['password'])
            ->setProfile($profile)
            ;

        $this->getEntityManager()->persist($profile);
        $this->getEntityManager()->persist($account);
        $this->getEntityManager()->flush();

        return $account;
    }

    private function createProfile(array $data): Profile
    {
        $profile = new Profile();
        $profile
            ->setName($data['name'])
            ->setFirstname($data['firstname'])
            ->setTel($data['tel'] ?? null)
            ->setSociety($data['society'])
            ->setDescription($data['description'] ?? null)
        ;

        return $profile;
    }
}
