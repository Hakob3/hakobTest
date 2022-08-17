<?php

namespace App\Repository;

use App\Entity\Geometry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Geometry>
 *
 * @method Geometry|null find($id, $lockMode = null, $lockVersion = null)
 * @method Geometry|null findOneBy(array $criteria, array $orderBy = null)
 * @method Geometry[]    findAll()
 * @method Geometry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GeometryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Geometry::class);
    }

    public function add(Geometry $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Geometry $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAtColumn(bool $includeUnavailableProducts = false): array
    {
        $qb = $this->createQueryBuilder('g')
            ->select('g.geometry')
            ->orderBy('g.id', 'ASC');

        $query = $qb->getQuery();

        return $query->execute();
    }

//    /**
//     * @return Geometry[] Returns an array of Geometry objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Geometry
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
