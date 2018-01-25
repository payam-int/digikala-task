<?php
/**
 * Created by PhpStorm.
 * User: payam
 * Date: 1/24/18
 * Time: 7:53 AM
 */

namespace App\ElasticSearchMappings;


use App\Entity\Product;
use App\Entity\Variant;
use App\Service\ElasticSearch\ElasticSearchMapping;
use App\Service\ElasticSearch\ElasticSearchService;
use Doctrine\ORM\EntityManagerInterface;

class ProductMapping implements ElasticSearchMapping
{

    /**
     * @var ElasticSearchService
     */
    private $elasticSearchService;

    /**
     * ProductMapping constructor.
     * @param $em
     */
    public function __construct(ElasticSearchService $elasticSearchService)
    {
        $this->elasticSearchService = $elasticSearchService;
    }


    public function accept($entity): bool
    {
        if ($entity instanceof Product || $entity instanceof Variant) {
            return true;
        }
        return false;
    }

    public function serialize($entity): array
    {

        $product = null;
        $variants = [];

        if ($entity instanceof Product) {
            $product = $entity;
        } else if ($entity instanceof Variant) {
            $product = $entity->getProduct();
        } else {
            return null;
        }

        $variants = $product->getVariants();

        $variants_array = [];

        foreach ($variants as $variant) {
            $variants_array[] = [
                'id' => $variant->getId(),
                'color' => $variant->getColor(),
                'price' => $variant->getPrice()
            ];
        }

        return [
            'id' => $product->getId(),
            'type' => 'product',
            'body' => [
                'title' => $product->getTitle(),
                'description' => $product->getDescription(),
                'variants' => $variants_array
            ]
        ];
    }

    public function delete($entity)
    {
        if ($entity instanceof Product) {
            return [
                'type' => 'product',
                'id' => $entity->getId()
            ];
        } else if ($entity instanceof Variant) {
            $this->elasticSearchService->index($entity->getProduct());
        }

        return [];
    }


    /**
     * @return array
     */

    public function getMappings(): array
    {
        return [
            "mappings" => [
                "product" => [
                    "properties" => [
                        "title" => ["type" => "text"],
                        "description" => ["type" => "text"],
                        "variants" => [
                            "type" => "nested",
                            "properties" => [
                                "color" => ["type" => "text"],
                                "price" => ["type" => "double"]
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }


}