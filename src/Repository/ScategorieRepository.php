<?php

namespace App\Repository;

use App\Entity\Scategorie;
use App\Entity\Categorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Scategorie>
 *
 * @method Scategorie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Scategorie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Scategorie[]    findAll()
 * @method Scategorie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Scategorie::class);
    }

    public function trouverDoublon(int $numero, Categorie $categorie): int{
        $qb = $this->createQueryBuilder('s')
        ->select('COUNT(s)')
        ->where('s.numero = :numero')
        ->andWhere('s.categorie = :categorie')
        ->setParameter('numero', $numero)
        ->setParameter('categorie', $categorie->getId());
        
        return $qb->getQuery()->getSingleScalarResult();
    }
       
       

//    /**
//     * @return Scategorie[] Returns an array of Scategorie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Scategorie
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
