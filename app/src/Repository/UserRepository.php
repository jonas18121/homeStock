<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function findUser(int $value): User
    {
        /** @var User */
        $user = $this->createQueryBuilder('u')
            ->select('u, b')
            ->leftJoin('u.bookings', 'b')
            ->andWhere('u.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getSingleResult()
        ;

        if ($user instanceof UserInterface) {
            return $user;
        }
    }

    public function countUser(): string
    {
        /** @var string */
        $count = $this->createQueryBuilder('u')
            ->select('COUNT(u)')
            ->getQuery()
            ->getSingleResult()
        ;

        return $count;
    }

    public function isEmailExist(string $email): ?User
    {
        /** @var User|null */
        $user = $this->createQueryBuilder('u')
            ->select('u')
            ->andWhere('u.email = :val')
            ->setParameter('val', $email)
            ->getQuery()
            ->getOneOrNullResult()
        ;

        if ($user instanceof UserInterface) {
            return $user;
        }

        return null;
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
