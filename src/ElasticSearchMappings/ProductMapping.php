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

class ProductMapping implements ElasticSearchMapping
{
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
        return [
            'type' => 'product',
            'id' => $entity->getId()
        ];
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