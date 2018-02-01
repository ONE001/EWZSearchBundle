<?php

namespace EWZ\Bundle\SearchBundle\Lucene;

use EWZ\Bundle\SearchBundle\Lucene\Lucene;

class LuceneIndexManager
{
	/** @var array */
	private $indices = array();

    /**
     * Instanciate of the index manager
     *
     * @param array  $indices
     * @param string $indexClass
     */
    public function __construct(array $indices, $indexClass)
    {
    	foreach ($indices as $name => $config) {
    		$analyzer = $config['analyzer'];
    		$path = $config['path'];
    		$index = new $indexClass($path, $analyzer);
    		$this->indices[$name] = $index;
    	}
    }

    /**
     * Get the specified lucene search-index
     *
     * @param string $indexName
     *
     * @return LuceneSearch
     */
    public function getIndex($indexName)
    {
    	if (array_key_exists($indexName, $this->indices)) {
	    	return $this->indices[$indexName];
    	}

    	return null;
    }

}
