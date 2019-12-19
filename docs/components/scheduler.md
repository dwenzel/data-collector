Scheduler Commands
==================

This package uses scheduled commands.

## Commands

### Add

* Add a scheduled command:
```bash
php bin/console  data-collector:scheduler:add FooBar '0 5 * * *' data-collector:collect 
```

* display command reference:
```bash
php bin/console  data-collector:scheduler:add -h
```

### List

* list all scheduled commands
```bash
php bin/console  data-collector:scheduler:list
```

* display command reference:
```bash
php bin/console  data-collector:scheduler:list -h
```
