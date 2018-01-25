<?php

namespace EWZ\Bundle\SearchBundle\Lucene;

use Zend\Search\Lucene\Document as ZendDocument;

class Document extends ZendDocument
{
    /**
     * Returns type for a named field in this document.
     *
     * @param string $fieldName
     *
     * @return string
     *
     * @throws \Exception
     */
    public function getFieldType($fieldName)
    {
        if (!array_key_exists($fieldName, $this->_fields)) {
            throw new \Exception("Field name \"$fieldName\" not found in document.");
        }

        return $this->_fields[$fieldName]->getType ();
    }
}
