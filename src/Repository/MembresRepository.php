<?php

namespace App\Repository;

use App\Entity\Membres;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query\AST\Join;
use Doctrine\ORM\Query\Expr\Join as ExprJoin;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Membres|null find($id, $lockMode = null, $lockVersion = null)
 * @method Membres|null findOneBy(array $criteria, array $orderBy = null)
 * @method Membres[]    findAll()
 * @method Membres[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MembresRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Membres::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Membres $entity, bool $flush = true): void
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
    public function remove(Membres $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Membres[] Returns an array of Membres objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function findOneWithPilote()
    {
        $qb=$this->createQueryBuilder('a');
        $qb->select('a.id, a.nom , a.image, a.role , a.nationalite, a.date_naissance,p.numero')
           ->leftJoin('App\Entity\Pilotes','p', ExprJoin::WITH , 'a.id=p.id')
           ->orderBy('a.id','DESC')
        ;
        return $qb->getQuery()->getResult();
    }

    //find by equipe
    public function findByEquipe($equipe)
    {
        $qb=$this->createQueryBuilder('a');
        $qb->select('a.id, a.nom , a.image, a.role , a.nationalite, a.date_naissance,p.numero')
           ->leftJoin('App\Entity\Pilotes','p', ExprJoin::WITH , 'a.id=p.id')
           ->where('a.equipe = :str')
           ->setParameter('str', $equipe )
           ->orderBy('a.id','DESC')
        ;
        return $qb->getQuery()->getResult();
    }


    public function findPilote()
    {
        $qb=$this->createQueryBuilder('a');
        $qb->select('a.id, a.nom , a.image, a.role , a.nationalite, a.date_naissance,p.numero')
           ->innerJoin('App\Entity\Pilotes','p', ExprJoin::WITH , 'a.id=p.id')
           ->orderBy('a.id','DESC')
        ;
        return $qb->getQuery()->getResult();
    }


    public function findByName($name)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT m
                FROM App\Entity\Membres m
                LEFT JOIN App\Entity\Pilotes p WITH m.id = p.id WHERE m.nom LIKE :str'
            )
            ->setParameter('str', '%'.$name.'%')
            ->getResult();
    }

    /*
    public function findOneBySomeField($value): ?Membres
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
