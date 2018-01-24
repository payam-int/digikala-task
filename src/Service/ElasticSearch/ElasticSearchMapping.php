<?php
/**
 * Created by PhpStorm.
 * User: payam
 * Date: 1/24/18
 * Time: 7:03 AM
 */

namespace App\Service\ElasticSearch;


/**
 * Interface ElasticSearchMapping
 * @package App\Service\ElasticSearch
 *
 * ElasticSearchMappings reflect changes in entities to the search database.
 * Every mapping can handle multiple entities.
 * Methods of this interface are called when ORM entities are inserted or updated.
 */
interface ElasticSearchMapping
{
    /**
     * @param $entity
     * @return bool
     *
     * Check if this mapping needs to do operations when this entity inserted or updated.
     */
    public function accept($entity): bool;

    /**
     * @param $entity
     * @return array
     */
    public function serialize($entity): array;

    /**
     * @return array
     */
    public function getMappings(): array;
}