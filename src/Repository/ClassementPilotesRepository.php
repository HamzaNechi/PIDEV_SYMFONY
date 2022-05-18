<?php

namespace App\Repository;

use App\Entity\ClassementPilotes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ClassementPilotes>
 *
 * @method ClassementPilotes|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClassementPilotes|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClassementPilotes[]    findAll()
 * @method ClassementPilotes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClassementPilotesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClassementPilotes::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(ClassementPilotes $entity, bool $flush = true): void
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
    public function remove(ClassementPilotes $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return ClassementPilotes[] Returns an array of ClassementPilotes objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ClassementPilotes
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */



    public function orderbyNbrPartcipants(){
        $qb=$this->createQueryBuilder('a');

     //  $em=$this->getEntityManager();
       // $query =$em->createQuery(' select c from App\Entity\ClassementPilote c order by c.points_total  ASC');
       $qb->orderBy('a.points_total','ASC');
        return $qb->getQuery()->getResult();
    }

}
