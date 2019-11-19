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

### Add instance 

* register instance 'Mimir' by name:
```bash
php bin/console  data-collector:instance:register Mimir
```
A uuid will be created during registration. This identifier can be used 
with other instances of the Data Collector and to retrieve collected data.

* register instance 'Loki' by uuid and name:
```bash
php bin/console  data-collector:instance:register Loki --identifier=54381ab6-b581-45cd-9813-82e0da5fe5e5
```

* register instance 'Loki' by uuid and name using shortcut `i`:
```bash
php bin/console  data-collector:instance:register Loki -i 54381ab6-b581-45cd-9813-82e0da5fe5e5
```
* register instance 'Yggdrasil' by name with identifier and role:
```bash
php bin/console  data-collector:instance:register Yggdrasil Staging --identifier=8b348664-b187-472f-94d2-c88330829708
```

* display command reference:
```bash
php bin/console  data-collector:instance:register -h
```

### List Instances

* List all instances
```bash
php bin/console  data-collector:instance:list
```

* display command reference
```bash
php bin/console  data-collector:instance:list -h
```

### Remove Instance
* remove instance by uuid:
```bash
php bin/console  data-collector:instance:forget 54381ab6-b581-45cd-9813-82e0da5fe5e5
```

* display command reference:
```bash
php bin/console  data-collector:instance:forget -h
```

### Update Instance
not yet implemented

### Add API
not yet implemented

### List APIs
* List all APIs
```bash
php bin/console  data-collector:api:list
```

* display command reference
```bash
php bin/console  data-collector:api:list -h
```

### Remove Instance
* remove API by identifier:
```bash
php bin/console  data-collector:api:forget foo/bar/2.0.0
```

* display command reference:
```bash
php bin/console  data-collector:api:forget -h
```



## Entities

### Instance
| property   | type   |          | description                  |
|:-----------|:-------|:---------|:-----------------------------|
| identifier | uuid   | required | universal unique identifier. |
| name       | string | required | Instance name for display    |
| role       | string | optional | Description of role. E.g. `production` or `testing`|

### Api

| property   | type   |          | description                  |
|:-----------|:-------|:---------|:-----------------------------|
| identifier | string | required | Unique identifier            |
| vendor     | string | required | Vendor name of API           |
| name       | string | required | Name of the API              |
| version    | string | required | Version. Register an API for each version|
| description| string | optional |                              |


## Contribution

Feedback and suggestions are highly welcome. Please file an issue on [github](https://github.com/dwenzel/data-collector/issues).

Contributed code must be covered by unit or functional tests.

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

