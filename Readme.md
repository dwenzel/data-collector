Application Data Collector
=========================

Collects data from application instances and stores them into a database.

The instances must provide API endpoints for data collection.

## Prerequisites

* PHP
* composer


## Installation

```bash
git clone git@github.com:dwenzel/data-collector.git
cd data-collector
composer install 
php bin/console  doctrine:database:create
php bin/console  doctrine:migrations:migrate
```

## Usage

_Data Collector_ provides a command line interface. It consists in a 
collection Symfony console commands.

Use the `list` command for an overview:
```bash
bin/console list data-collector
```

For reference see [components](./docs/index.md)


### Tests

* load test data

```bash
 php bin/console doctrine:fixtures:load
```

* perform tests
```bash
 php vendor/bin/phpunit -c phpunit.xml.dist
```

Please open pull requests on github for features and fixes.


## Credits

[`symfony-bundles/queue-bundles`](https://github.com/symfony-bundles/queue-bundle) could not be required due to missing dependencies. 

Instead we took the implementation of the following classes from this package:  
* `QueueInterface`
* `RedisStorage`
* `StorageInterface`


