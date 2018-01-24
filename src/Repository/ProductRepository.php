<?php

namespace App\Repository;

use App\Entity\Product;
use App\Service\ElasticSearch\ElasticSearchSearchableRepository;
use App\Service\ElasticSearch\ElasticSearchService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ProductRepository extends ServiceEntityRepository implements ListRepositoryInterface
{
    use ListRepositoryTrait;

    private $elasticSearchService;

    private const SEARCH_FIELDS = ["title", "description", "variants.color", "variants.price"];

    public function __construct(RegistryInterface $registry, ElasticSearchService $elasticSearchService)
    {
        parent::__construct($registry, Product::class);

        $this->elasticSearchService = $elasticSearchService;
    }

    public function search($query)
    {

        $values = $this->elasticSearchService->simpleQuerySearch($query, 'product', self::SEARCH_FIELDS);
        $models = [];
        
        if (count($values) > 0) {
            $query = $this->getEntityManager()
                ->createQueryBuilder();
            $query = $query
                ->select('p')
                ->from('App\Entity\Product', 'p')
                ->add('where', $query->expr()->in('p.id', $values))
                ->setCacheable(true)
                ->getQuery();

            $results = $query->execute();
            $results_by_id = [];

            foreach ($results as $result) {
                $results_by_id[$result->getId()] = $result;
            }

            foreach ($values as $key => $value) {
                if (key_exists($value, $results_by_id))
                    $models[] = $results_by_id[$value];
            }
        }


        return $models;
    }


}
