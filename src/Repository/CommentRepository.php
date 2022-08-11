<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Comment>
 *
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function add(Comment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Comment $entity, bool $flush = false): void
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
	 private function getCommentsQueryBuilder($trickId){
		// Select the comments
		$queryBuilder = $this->createQueryBuilder('c')
			//->addSelect('p');
            ->orderBy('c.createdAt', 'DESC');
		// Add the package relation
		//$queryBuilder->leftJoin('o.packages','p');
		
		// Add WHERE clause
		$queryBuilder->where("c.Trick = $trickId");
		//	->andWhere('p.deleted = 0');
		
		//Return the QueryBuilder
		return $queryBuilder;
	} /**/

    /**
	 * Retrieve the list paginated tricks
	 * @param $page
	 * @return Paginator
	 */

    public function getComments($trickId, $page, $pageSize){
		
		$firstPage = ($page - 1) * $pageSize;

		$queryBuilder = $this->getCommentsQueryBuilder($trickId);
        
		
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
//     * @return Comment[] Returns an array of Comment objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Comment
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
