EWZSearchBundle
=============

This bundle provides advance search capability for Symfony.

## Installation

Installation depends on how your project is setup:

### Installation using composer
To install EWZSearchBundle with Composer just add the following to your `composer.json` file:

```
// composer.json
{
    // ...
    require: {
        // ...
        "excelwebzone/zend-search": "dev-master",
        "excelwebzone/search-bundle": "dev-master",
    }
}
```
Then, you can install the new dependencies by running Composer’s update command from 
the directory where your `composer.json` file is located:

```
php composer.phar update
```
Now, Composer will automatically download all required files, and install them for you. 
All that is left to do is to update your AppKernel.php file, and register the new bundle:

```
<?php

// in AppKernel::registerBundles()
$bundles = array(
    // ...
   	new EWZ\Bundle\SearchBundle\EWZSearchBundle(),
    // ...
);
```


### alternative Installation methods

#### Install with the vendors.php or using submodules
##### The `bin/vendors.php` method

If you're using the `bin/vendors.php` method to manage your vendor libraries,
add the following entries to the `deps` in the root of your project file:

```
[EWZSearchBundle]
    git=http://github.com/excelwebzone/EWZSearchBundle.git
    target=/bundles/EWZ/Bundle/SearchBundle

; Dependency:
;------------
[Search]
    git=http://github.com/excelwebzone/zend-search.git
    target=/zend-search

```

Next, update your vendors by running:

``` bash
$ ./bin/vendors
```

Great! Now skip down to *Configure the autoloader*.

##### Submodules

If you're managing your vendor libraries with submodules, first create the
`vendor/bundles/EWZ/Bundle` directory:

``` bash
$ mkdir -pv vendor/bundles/EWZ/Bundle
```

Next, add the necessary submodules:

``` bash
$ git submodule add git://github.com/excelwebzone/zend-search.git vendor/zend-search/Zend/Search
$ git submodule add git://github.com/excelwebzone/EWZSearchBundle.git vendor/bundles/EWZ/Bundle/SearchBundle
```

#### Configure the autoloader

Add the following entry to your autoloader:

``` php
<?php
// app/autoload.php

$loader->registerNamespaces(array(
    // ...

    'Zend\\Search' => __DIR__.'/../vendor/zend-search/',
    'EWZ'          => __DIR__.'/../vendor/bundles',
));
```

#### Enable the bundle

Finally, enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...

        new EWZ\Bundle\SearchBundle\EWZSearchBundle(),
    );
}
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
