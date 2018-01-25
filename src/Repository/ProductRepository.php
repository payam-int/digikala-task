<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\ProductAdvancedSearch;
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

    private const SEARCH_FIELDS = ["title", "description"];

    public function __construct(RegistryInterface $registry, ElasticSearchService $elasticSearchService)
    {
        parent::__construct($registry, Product::class);

        $this->elasticSearchService = $elasticSearchService;
    }

    public function search($query)
    {

        $searchResult = $this->elasticSearchService->simpleQuerySearch($query, 'product', self::SEARCH_FIELDS);

        $models = $this->getSearchedModels($searchResult);

        return $models;
    }

    public function advancedSearch(ProductAdvancedSearch $advancedSearch)
    {
        // building query

        $conditions = [];
        $queryFields = [];


        if ($advancedSearch->getMinPrice() || $advancedSearch->getMaxPrice()) {
            $priceQuery = [
                'range' => [
                    'variants.price' => []
                ]
            ];

            if ($advancedSearch->getMinPrice()) {
                $priceQuery['range']['variants.price']['gte'] = $advancedSearch->getMinPrice();
                $queryFields[] = 'variants.price.gte';
            }

            if ($advancedSearch->getMaxPrice()) {
                $priceQuery['range']['variants.price']['lte'] = $advancedSearch->getMaxPrice();
                $queryFields[] = 'variants.price.lte';
            }
            $conditions[] = [
                "nested" =>
                    [
                        "path" => "variants",
                        "query" => $priceQuery
                    ]
            ];

        }

        if ($advancedSearch->getSearchQuery()) {
            $fields = $advancedSearch->getSearchFields();
            if (empty($fields)) {
                $fields = ["title", "description", "variants.color"];
            }

            $keywordsQuery = [
                'multi_match' => [
                    'query' => $advancedSearch->getSearchQuery(),
                    'fields' => $fields
                ]
            ];
            $queryFields = $queryFields + $fields;
            $conditions[] = $keywordsQuery;
        }


        $query = [
            'query' => [
                'bool' => [
                    'must' => $conditions
                ]
            ]
        ];

        $searchResult = $this->elasticSearchService->advancedQuerySearch('product', $queryFields, $query);

        $models = $this->getSearchedModels($searchResult);

        return $models;
    }

    private function getSearchedModels(array $searchResult): array
    {
        $models = [];
        if (count($searchResult) > 0) {
            $query = $this->getEntityManager()
                ->createQueryBuilder();
            $query = $query
                ->select('p')
                ->from('App\Entity\Product', 'p')
                ->add('where', $query->expr()->in('p.id', $searchResult))
                ->setCacheable(true)
                ->getQuery();

            $results = $query->execute();
            $results_by_id = [];

            foreach ($results as $result) {
                $results_by_id[$result->getId()] = $result;
            }

            foreach ($searchResult as $key => $value) {
                if (key_exists($value, $results_by_id))
                    $models[] = $results_by_id[$value];
            }
        }

        return $models;
    }


}
