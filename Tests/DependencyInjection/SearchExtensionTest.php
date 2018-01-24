<?php

namespace EWZ\Bundle\SearchBundle\Tests\DependencyInjection;


use EWZ\Bundle\SearchBundle\DependencyInjection\EWZSearchExtension;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\EnvPlaceholderParameterBag;

class SearchExtensionTest extends WebTestCase
{
    /**
     * @covers EWZ\Bundle\SearchBundle\DependencyInjection\EWZSearchExtension::load
     */
    public function testConfigLoad()
    {
        /** @var ContainerBuilder $container */
        $container = new ContainerBuilder(new EnvPlaceholderParameterBag());
        $loader = new EWZSearchExtension();

        $loader->load(array(), $container);
        $searchClass = $container->getParameter('ewz_search.lucene.search.class');
        $this->assertEquals('EWZ\\Bundle\\SearchBundle\\Lucene\\LuceneSearch', $searchClass, '->luceneLoad() loads the lucene.xml file if not already loaded');

        $analyzer = $container->getParameter("lucene.analyzer");
        $this->assertEquals('Zend\Search\Lucene\Analysis\Analyzer\Common\TextNum\CaseInsensitive', $analyzer, '->luceneLoad() loads the lucene.xml file if not already loaded');

        $indexPath = $container->getParameter("lucene.index.path");
        $this->assertEquals('%kernel.root_dir%/cache/%kernel.environment%/lucene/index', $indexPath, '->luceneLoad() loads the lucene.xml file if not already loaded');
    }
}
