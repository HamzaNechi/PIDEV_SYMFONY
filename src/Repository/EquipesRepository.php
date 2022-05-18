<?php

namespace App\Repository;

use App\Entity\Equipes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Equipes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Equipes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Equipes[]    findAll()
 * @method Equipes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EquipesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Equipes::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Equipes $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Equipes $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }


    public function findByName($name)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT p
                FROM App\Entity\Equipes p
                WHERE p.nom LIKE :str'
            )
            ->setParameter('str', '%'.$name.'%')
            ->getResult();
    }


    public function NombreEquipe()
    {
        $qb=$this->createQueryBuilder('a');
        $qb->select('count(a.id)')
        ;
        return $qb->getQuery()->getSingleScalarResult();
    }

    // /**
    //  * @return Equipes[] Returns an array of Equipes objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Equipes
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
