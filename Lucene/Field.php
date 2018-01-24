<?php

namespace EWZ\Bundle\SearchBundle\Lucene;

use Zend\Search\Lucene\Document\Field as ZendField;

class Field extends ZendField
{


    /**
     * Constructs a String-valued Field that is not tokenized, but is indexed
     * and stored.  Useful for non-text fields, e.g. date or url.
     *
     * @param string $name
     * @param string $value
     * @param string $encoding
     * @return \Zend\Search\Lucene\Document\Field
     */
    public static function keyword($name, $value, $encoding = 'UTF-8')
    {
        return new self($name, $value, $encoding, true, true, false);
    }


    /**
     * Constructs a String-valued Field that is not tokenized nor indexed,
     * but is stored in the index, for return with hits.
     *
     * @param string $name
     * @param string $value
     * @param string $encoding
     * @return \Zend\Search\Lucene\Document\Field
     */
    public static function unIndexed($name, $value, $encoding = 'UTF-8')
    {
        return new self($name, $value, $encoding, true, false, false);
    }


    /**
     * Constructs a Binary String valued Field that is not tokenized nor indexed,
     * but is stored in the index, for return with hits.
     *
     * @param string $name
     * @param string $value
     * @param string $encoding
     * @return \Zend\Search\Lucene\Document\Field
     */
    public static function binary($name, $value)
    {
        return new self($name, $value, '', true, false, false, true);
    }

    /**
     * Constructs a String-valued Field that is tokenized and indexed,
     * and is stored in the index, for return with hits.  Useful for short text
     * fields, like "title" or "subject". Term vector will not be stored for this field.
     *
     * @param string $name
     * @param string $value
     * @param string $encoding
     * @return \Zend\Search\Lucene\Document\Field
     */
    public static function text($name, $value, $encoding = 'UTF-8')
    {
        return new self($name, $value, $encoding, true, true, true);
    }


    /**
     * Constructs a String-valued Field that is tokenized and indexed,
     * but that is not stored in the index.
     *
     * @param string $name
     * @param string $value
     * @param string $encoding
     * @return \Zend\Search\Lucene\Document\Field
     */
    public static function unStored($name, $value, $encoding = 'UTF-8')
    {
        return new self($name, $value, $encoding, false, true, true);
    }


    /*
     * convience function to find the way the field was created
     * instead of having to check the is* individually
     *
     * @returns string
     */
    public function getType()
    {
        if (!$this->isStored) {
            // only UnStored meets this criteria
            return 'UnStored';
        }

        if ($this->isBinary) {
            // only Binary isBinary :)
            return 'Binary';
        }

        if ($this->isIndexed) {
            // Keyword or Text
            if( $this->isTokenized ) {
                // only text is tokenized
                return 'Text';
            } else {
                return 'Keyword';
            }
        }

        // only unIndexed is left
        return 'UnIndexed';
    }
}
