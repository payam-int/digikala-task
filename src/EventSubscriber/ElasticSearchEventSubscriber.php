<?php

namespace App\EventSubscriber;

use App\Entity\Product;
use App\Service\ElasticSearch\ElasticSearchService;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ElasticSearchEventSubscriber implements EventSubscriber
{

    private $elastic_search;

    /**
     * SearchIndexerSubscriber constructor.
     * @param $elastic_search
     */
    public function __construct(ElasticSearchService $elastic_search)
    {
        $this->elastic_search = $elastic_search;
    }


    public function postUpdate(LifecycleEventArgs $args)
    {
        if (!$this->elastic_search)
            return;

        $entity = $args->getEntity();
        $this->elastic_search->index($entity);
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        if (!$this->elastic_search)
            return;

        $entity = $args->getEntity();
        $this->elastic_search->index($entity);
    }

    public function postRemove(LifecycleEventArgs $args)
    {
        if (!$this->elastic_search)
            return;

        $entity = $args->getEntity();
        $this->elastic_search->delete($entity);
    }

    public function getSubscribedEvents()
    {
        return [
            'postUpdate', 'postPersist', 'postRemove'
        ];
    }
}
