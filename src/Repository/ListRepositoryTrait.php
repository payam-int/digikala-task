<?php
/**
 * Created by PhpStorm.
 * User: payam
 * Date: 1/23/18
 * Time: 4:22 PM
 */

namespace App\Repository;


use Doctrine\ORM\Query;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

trait ListRepositoryTrait
{

    public function getList(int $page = 1): Pagerfanta
    {
        $entiy_name = $this->_entityName;
        $query = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('p')
            ->from($entiy_name, 'p')
            ->orderBy('p.id', 'DESC')
            ->getQuery();

        return $this->createPaginator($query, $page);
    }

    private function createPaginator(Query $query, int $page): Pagerfanta
    {
        $paginator = new Pagerfanta(new DoctrineORMAdapter($query));
        $paginator->setMaxPerPage(10);
        $paginator->setCurrentPage($page);

        return $paginator;
    }
}