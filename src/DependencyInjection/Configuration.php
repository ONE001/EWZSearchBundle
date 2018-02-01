<?php

namespace EWZ\Bundle\SearchBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ewz_search');

        $rootNode
            ->children()
            	->arrayNode('indices')
             		->useAttributeAsKey('name')
             		->prototype('array')
	 		            ->children()
			            	->scalarNode('path')->defaultValue('%kernel.root_dir%/EwzLuceneIndices/%kernel.environment%/defaultIndex')->end()
	 		                ->scalarNode('analyzer')->defaultValue('Zend\Search\Lucene\Analysis\Analyzer\Common\TextNum\CaseInsensitive')->end()
	 		            ->end()
	 				->end()
 				->end()

 				// for BC reasons only
                ->scalarNode('analyzer')->defaultValue('Zend\Search\Lucene\Analysis\Analyzer\Common\TextNum\CaseInsensitive')->end()
                ->scalarNode('path')->defaultValue('%kernel.root_dir%/cache/%kernel.environment%/lucene/index')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
