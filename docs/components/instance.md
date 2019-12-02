Instance
========

### Entity

### Instance
| property   | type   |          | description                  |
|:-----------|:-------|:---------|:-----------------------------|
| identifier | uuid   | required | universal unique identifier. |
| name       | string | required | Instance name for display    |
| role       | string | optional | Description of role. E.g. `production` or `testing`|


## Commands

### Add instance 

* register instance 'Mimir' by name:
```bash
php bin/console  data-collector:instance:register Mimir
```
An unique universal identifier (UUID] will be created during registration. 
This identifier can be used with other instances of the Data Collector.

* register instance 'Loki' by identifier and name:
```bash
php bin/console  data-collector:instance:register Loki --identifier=54381ab6-b581-45cd-9813-82e0da5fe5e5
```

* register instance 'Loki' by identifier and name using shortcut `i`:
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

### List

* List all instances
```bash
php bin/console  data-collector:instance:list
```

* display command reference
```bash
php bin/console  data-collector:instance:list -h
```

### Remove
* remove instance by uuid:
```bash
php bin/console  data-collector:instance:forget 54381ab6-b581-45cd-9813-82e0da5fe5e5
```

* display command reference:
```bash
php bin/console  data-collector:instance:forget -h
```

### Show
not yet implemented

### Update
not yet implemented

### Add API
not yet implemented

