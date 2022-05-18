<?php

namespace App\Repository;

use App\Entity\Participation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Participation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Participation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Participation[]    findAll()
 * @method Participation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParticipationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Participation::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Participation $entity, bool $flush = true): void
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
    public function remove(Participation $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }


    // public function search($value)
    //  {

    //         return $this->createQueryBuilder('p')
    //             ->join('p.pilote' ,'pi' )
    //             ->join('p.equipe', 'e'  )
    //             ->join('p.course', 'c')
    //             ->where('p.course like :val')
    //             ->orWhere('c.nom like :val')
    //             ->orWhere('p.numero like :val')
    //             ->orWhere('c.circuit like :val')
    //             ->orWhere('e.nom like :val')
    //             ->setParameter('val', $value)
    //             ->getQuery()
    //             ->getResult();
    //     }


    public function searchB($value)
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery('select p from APP\Entity\Participation p 
        join APP\Entity\Pilotes as pi with pi=p.pilote 
            join APP\Entity\Courses as c with c=p.course 
            join APP\Entity\Equipes as e with e=p.equipe
            WHERE e.nom like :val or c.nom like :val')

            ->setParameter('val', $value);
        return  $query->getResult();
    }






    /** hedha yekhou id course w yrajaa participation mtaa course hedha  */

    /**
     * @return Participation[] Returns an array of Participation objects
     */

    public function findByCourse($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.course = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult();
    }


    /*
    public function findOneBySomeField($value): ?Participation
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
