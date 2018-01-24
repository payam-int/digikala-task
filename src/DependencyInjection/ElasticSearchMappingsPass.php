<?php
/**
 * Created by PhpStorm.
 * User: payam
 * Date: 1/24/18
 * Time: 7:46 AM
 */

namespace App\DependencyInjection;


use App\Service\ElasticSearch\ElasticSearchService;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;


/**
 * Class ElasticSearchMappingsPass
 * @package App\DependencyInjection
 *
 * This class helps ElasticSearchService finding Mapping classes in compilation time.
 */
class ElasticSearchMappingsPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     *
     * More information: https://symfony.com/doc/current/service_container/compiler_passes.html
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(ElasticSearchService::class)) {
            return;
        }

        $definition = $container->findDefinition(ElasticSearchService::class);

        $tagged = $container->findTaggedServiceIds('elastic_search.mapping');
        foreach ($tagged as $class => $etc) {
            $definition->addMethodCall('addMapping', array(new Reference($class)));
        }
    }

}