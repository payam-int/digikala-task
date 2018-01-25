<?php
/**
 * Created by PhpStorm.
 * User: payam
 * Date: 1/24/18
 * Time: 6:17 AM
 */

namespace App\Service\ElasticSearch;


use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use Exception;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\RouteCollectionBuilder;


class ElasticSearchService
{

    private $configuration;

    /**
     * @var Client $client
     */
    private $client;

    private $mappings = [];

    private $container;

    /**
     * @var AbstractAdapter $cache
     */

    private $cache;

    /**
     * ElasticSearchService constructor.
     * @param ContainerInterface $container
     * @param $configuration
     */
    public function __construct(ContainerInterface $container, $configuration)
    {
        $this->configuration = $configuration;
        $this->container = $container;
        $this->buildClient();
        $this->cache = $container->get('app.cache.search');
    }

    private function buildClient()
    {
        $clientBuilder = ClientBuilder::create();
        $clientBuilder->setHosts($this->configuration['hosts']);
        $this->client = $clientBuilder->build();
    }

    private static function hashQuery($query)
    {
        return hash('sha512', $query);
    }

    private static function hashFields($fields)
    {
        return hash('crc32', implode($fields));
    }

    public function addMapping($ref)
    {
        $this->mappings[] = $ref;
    }

    public function putMappings()
    {
        foreach ($this->mappings as $mapping) {
            $params = [
                "index" => $this->configuration['index'],
                "body" => $mapping
            ];

            $response = $this->client->indices()->create($params);
        }
    }

    /**
     * @param $entity
     * @return array|null
     *
     * Insert or update entity
     */
    public function index($entity)
    {
        $result = null;


        foreach ($this->mappings as $mapping) {
            if ($mapping->accept($entity)) {
                $result = $mapping->serialize($entity);
                $result = $result + ['index' => $this->configuration['index']];

                try {
                    $this->update($result);
                } catch (Missing404Exception $e) {
                    $this->insert($result);
                }
            }
        }

        return $result;
    }

    private function insert($entity_serialized)
    {
        if ($entity_serialized == null)
            return;

        $response = $this->client->index($entity_serialized);
    }

    private function update($entity_serialized)
    {

        if ($entity_serialized == null)
            return;

        $params = $entity_serialized;

        $params['body'] = ['doc' => $params['body']];

        $this->client->update($params);

    }

    private function search($params)
    {
        $params = $params + ['index' => $this->configuration['index']];

        try {
            $response = $this->client->search($params);
        } catch (Exception $e) {
            return [];
        }

        return array_map(function ($hit) {
            return $hit['_id'];
        }, $response['hits']['hits']);
    }

    public function simpleQuerySearch(string $query, string $type, array $fields = null): array
    {
        // hashing search query
        $query_cache_key = sprintf("%s_%s_%s", $type, self::hashFields($fields), self::hashQuery($query));

        // getting cache
        $search_result = $this->cache->getItem($query_cache_key);

        if ($search_result->isHit()) {
            return $search_result->get();
        } else {
            $params = [
                'type' => $type,
                'body' => [
                    '_source' => [''],
                    'size' => 10,
                    'query' => [
                        'simple_query_string' => [
                            'query' => $query,
                            'default_operator' => 'and',
                            'fields' => $fields
                        ]
                    ]
                ]
            ];

            $result = $this->search($params);
            $search_result->set($result);
            $this->cache->save($search_result);

            return $result;
        }
    }

    public function advancedQuerySearch(string $type, $fields, array $params): array
    {
        $queryForHashing = json_encode($params);
        $query_cache_key = sprintf("%s_%s_%s", $type, self::hashFields($fields), self::hashQuery($queryForHashing));

        // getting cache
        $search_result = $this->cache->getItem($query_cache_key);

        if ($search_result->isHit()) {
            return $search_result->get();
        } else {
            $params = [
                'type' => $type,
                'body' => [
                        '_source' => [''],
                        'size' => 10
                    ] + $params
            ];

            $result = $this->search($params);
            $search_result->set($result);
            $this->cache->save($search_result);

            return $result;
        }
    }

    public function delete($entity)
    {
        foreach ($this->mappings as $mapping) {
            if ($mapping->accept($entity)) {
                $params = $mapping->delete($entity);
                $params = $params + ['index' => $this->configuration['index']];

                try {
                    $response = $this->client->delete($params);
                } catch (\Exception $e) {
                    // ignore
                }
            }
        }
    }

    public function clearType($type)
    {
        $params = [
            'index' => $this->configuration['index'],
            'type' => $type,
            'body' => [
                'query' => [
                    'match_all' => []
                ]
            ]
        ];

        try {
            $this->client->deleteByQuery($params);
        } catch (Exception $e) {
            // ignore
        }
    }


}