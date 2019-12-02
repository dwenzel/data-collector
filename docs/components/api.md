API
===

### Entity

| property   | type   |          | description                  |
|:-----------|:-------|:---------|:-----------------------------|
| identifier | string | required | Unique identifier            |
| vendor     | string | required | Vendor name of API           |
| name       | string | required | Name of the API              |
| version    | string | required | Version. Register an API for each version|
| description| string | optional |                              |


## Commands

### Add

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

### List

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
### Remove
* remove API by identifier:
```bash
php bin/console  data-collector:api:forget 54381ab6-b581-45cd-9813-82e0da5fe5e5
```

* display command reference:
```bash
php bin/console  data-collector:api:forget -h
```

### Show

* show API by identifier:
```bash
php bin/console  data-collector:api:show 54381ab6-b581-45cd-9813-82e0da5fe5e5
```
* display command reference:
```bash
php bin/console  data-collector:api:show -h
```
