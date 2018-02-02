EWZSearchBundle
=============

[![Build Status](https://api.travis-ci.org/excelwebzone/EWZSearchBundle.svg)](https://travis-ci.org/excelwebzone/EWZSearchBundle)

This bundle provides advance search capability for Symfony.

## Installation
Installation depends on how your project is setup:

### Installation using composer
Execute require command.
``` bash
$ composer require excelwebzone/search-bundle "^1.0"
```
Enable the bundle in `AppKernel.php`.
``` php
<?php

// in AppKernel::registerBundles()
$bundles = array(
    // ...
   	new EWZ\Bundle\SearchBundle\EWZSearchBundle(),
    // ...
);
```

## Configuration
Define your search indices in the config.yml. You can use the EWZSearchBundle with multiple search indices and with various Analyzers. 

**NOTE**: If you want to include numbers in your search queries then you'll need to set
analyzer to Zend\Search\Lucene\Analysis\Analyzer\Common\TextNum\CaseInsensitive
See http://framework.zend.com/manual/en/zend.search.lucene.extending.html for more information

For backward compatability reasons the old and new config both work.

### using one or more SearchIndex => new config

``` yaml
# app/config/config.yml
ewz_search:
    indices:
        indexFoo:
            path:                 %kernel.root_dir%/EwzLuceneIndices/%kernel.environment%/myIndexFoo
            analyzer:             Zend\Search\Lucene\Analysis\Analyzer\Common\Utf8\CaseInsensitive
        indexBar:
            path:                 %kernel.root_dir%/EwzLuceneIndices/%kernel.environment%/myIndexBar
            analyzer:             Zend\Search\Lucene\Analysis\Analyzer\Common\TextNum\CaseInsensitive

    # deprecated
    analyzer:             Zend\Search\Lucene\Analysis\Analyzer\Common\TextNum\CaseInsensitive
    path:                 %kernel.root_dir%/cache/%kernel.environment%/lucene/index
```

### using only one SearchIndex => old config

``` yaml
# app/config/config.yml
ewz_search:
    analyzer: Zend\Search\Lucene\Analysis\Analyzer\Common\TextNum\CaseInsensitive
    path:     %kernel.root_dir%/cache/%kernel.environment%/lucene/index
```

Congratulations! You're ready!

## Basic Usage

### Getting the index
Depending on you configuration you can get access to the LuceneSearch object for your index in one of the following ways:

``` php
<?php

use EWZ\Bundle\SearchBundle\Lucene\LuceneSearch;

// with the new configuration-style
$luceneSearchForFooIndex = $this->get('ewz_search.lucene.manager')->getIndex('indexFoo');
$luceneSearchForBarIndex = $this->get('ewz_search.lucene.manager')->getIndex('indexBar');

// with the old configuration-style
$search = $this->get('ewz_search.lucene');
```

### Use the index
To index an object use the following example:

``` php
<?php

use EWZ\Bundle\SearchBundle\Lucene\LuceneSearch;

$search = $this->get('ewz_search.lucene.manager')->getIndex('indexFoo');

$document = new Document();
$document->addField(Field::keyword('key', $story->getId()));
$document->addField(Field::text('title', $story->getTitle()));
$document->addField(Field::text('url', $story->getUrl()));
$document->addField(Field::unstored('body', $story->getDescription()));

$search->addDocument($document);
$search->updateIndex();
```

When you want to retrieve data, use:

``` php
<?php

use EWZ\Bundle\SearchBundle\Lucene\LuceneSearch;

$search = $this->get('ewz_search.lucene.manager')->getIndex('indexFoo');
$query = 'Symfony2';

$results = $search->find($query);
```

**NOTE**: See the Zend documentation for more information.
