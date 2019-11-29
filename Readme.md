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

### Add API 

* register API by name 'Foo', vendor name 'ACME' and version '1.0.0':
```bash
php bin/console  data-collector:api:register Foo ACME 1.0.0
```
A uuid will be created during registration. This identifier can be used 
with other instances of the Data Collector and to retrieve collected data.

* register API by  name 'bar', vendor name 'ACME', and version '1.0.0' with identifier ':
```bash
php bin/console  data-collector:api:register bar ACME 1.0.0 --identifier=54381ab6-b581-45cd-9813-82e0da5fe5e5
```

* display command reference:
```bash
php bin/console  data-collector:api:register -h
```

### List APIs

* list all registered APIs
```bash
php bin/console  data-collector:api:list
```

* add endpoint named 'foo' to API with identifier 54381ab6-b581-45cd-9813-82e0da5fe5e5
```bash
php bin/console  data-collector:api:add-endpoint foo 54381ab6-b581-45cd-9813-82e0da5fe5e5
```

* display command reference:
```bash
php bin/console  data-collector:api:list -h
```
### Remove API
* remove API by identifier:
```bash
php bin/console  data-collector:api:forget 54381ab6-b581-45cd-9813-82e0da5fe5e5
```

* display command reference:
```bash
php bin/console  data-collector:api:forget -h
```

---
### Add instance 

* register instance 'Mimir' by name:
```bash
php bin/console  data-collector:instance:register Mimir
```
An unique universal identifier (UUID] will be created during registration. 
This identifier can be used with other instances of the Data Collector.

* register instance 'Loki' by uuid and name:
```bash
php bin/console  data-collector:instance:register Loki --identifier=54381ab6-b581-45cd-9813-82e0da5fe5e5
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

* list all instances:
```bash
php bin/console  data-collector:instance:list
```


* display command reference:
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

### Endpoint

Belongs to an API

| property   | type   |          | description                  |
|:-----------|:-------|:---------|:-----------------------------|
| identifier | string | required | Unique identifier            |
| api        | string | required | API identifier               |
| name       | string | required | Name of the API              |
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

