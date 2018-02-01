<?php

namespace EWZ\Bundle\SearchBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class EWZSearchExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $container->setParameter('lucene.indices', $config['indices']);

        // for set the parameters for the default search-index => for BC reasons & if there is only one index defined
        if(array_key_exists('analyzer', $config)){
	        $defaultIndexAnalyzer = $config['analyzer'];
	        $defaultIndexPath = $config['path'];
    	} else {
    		$indices = array_values($config['indices']);
    		$config = $indices[0];
	        $defaultIndexAnalyzer = $config['analyzer'];
	        $defaultIndexPath = $config['path'];
       	}
        $container->setParameter('lucene.analyzer', $defaultIndexAnalyzer);
        $container->setParameter('lucene.index.path', $defaultIndexPath);
    }
}
