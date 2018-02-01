<?php

namespace EWZ\Bundle\SearchBundle\Lucene;

use Zend\Search\Lucene\Analysis\Analyzer\Analyzer;
use Zend\Search\Lucene\Index\Term;
use Zend\Search\Lucene\Search\QueryHit;
use Zend\Search\Lucene\Document as ZendDocument;

class LuceneSearch
{
    /** @var \Zend\Search\Lucene\Index  */
    protected $index;

    /**
     * Instanciate the Auth service
     *
     * @param string   $luceneIndexPath
     * @param Analyzer $analyzer
     */
    public function __construct($luceneIndexPath, $analyzer = null)
    {
        if (file_exists($luceneIndexPath)) {
            $this->index = Lucene::open($luceneIndexPath);
        } else {
            $this->index = Lucene::create($luceneIndexPath);
        }

        if (isset($analyzer)) {
            Analyzer::setDefault(new $analyzer);
        }
    }

    /**
     * @return \Zend\Search\Lucene\Index
     */
    public function getIndex() {
        return $this->index;
    }

    /**
     * This is a convience function to add a document to the index
     *
     * @param ZendDocument $document
     */
    public function addDocument( $document)
    {
        $this->deleteDocument($document);
        $this->index->addDocument($document);
    }

    /**
     * A convience function to commit and optimize the index
     */
    public function updateIndex()
    {
        $this->index->commit();
        $this->index->optimize();
    }

    /**
     * @param $query
     *
     * @return QueryHit[]
     */
    public function find($query)
    {
        return call_user_func_array(array($this->index, 'find'), func_get_args());
    }

    /**
     * @param ZendDocument $document
     */
    public function updateDocument(ZendDocument $document)
    {
        $this->addDocument($document);
    }

    /**
     * @param ZendDocument $document
     */
    public function deleteDocument(ZendDocument $document)
    {
        // Search for documents with the same Key value.
        $term = new Term($document->getField('key')->value, 'key');
        $docIds = $this->index->termDocs($term);

        // Delete any documents found.
        foreach ($docIds as $id) {
            $this->index->delete($id);
        }
    }
}
