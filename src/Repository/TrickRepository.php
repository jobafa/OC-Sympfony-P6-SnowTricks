<?php

namespace App\Repository;

use App\Entity\Trick;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Trick>
 *
 * @method Trick|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trick|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trick[]    findAll()
 * @method Trick[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrickRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trick::class);
    }

    public function add(Trick $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Trick $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    
    /**
	 * Retrieve the list of tricks
	 * @return QueryBuilder
	 */
	 private function getTricksQueryBuilder(){
		// Select the tricks
		$queryBuilder = $this->createQueryBuilder('t')//->addSelect('p');
            ->orderBy('t.createdAt', 'DESC');
		//Return the QueryBuilder
		return $queryBuilder;
	} /**/

    /**
	 * Retrieve the list paginated tricks
	 * @param $page
	 * @return Paginator
	 */

    public function getTricks($page, $pageSize){
		
		$firstPage = ($page - 1) * $pageSize;

		$queryBuilder = $this->getTricksQueryBuilder();
        
		
		// Set the returned page
		$queryBuilder->setFirstResult($firstPage);
		$queryBuilder->setMaxResults($pageSize);
		
		// Generate the Query
		$query = $queryBuilder->getQuery();
		//dd($query);
		// Generate the Paginator
		$paginator = new Paginator($query, true);
		return $paginator;
	}

	

//    /**
//     * @return Trick[] Returns an array of Trick objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Trick
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
