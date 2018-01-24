# Digikala PHP-Task

This is a simple e-commerce written in php7.1. Lots of Symfony components and bundles are used.

## Download
Open terminal and type:
```
git clone https://github.com/payam-int/digikala-task.git
```

## Configuration and Installation
Steps:
- Install dependecies
- Configure database
- Create schema and load fixtures
- Configure cache and elasticsearch
### Install dependencies
```
cd digikala-test
composer install
```

### Configure Database

#### Mysql
Open `.env` file and edit `DATABASE_URL` variable.
#### Sqlite
If you want to use sqlite you have to change it's driver in `config/packages/doctrine.yaml`.

Change this
```yaml
doctrine:
    dbal:
        driver: 'pdo_mysql'
        server_version: '5.7'
        charset: utf8mb4
```
to this

```yaml
doctrine:
    dbal:
        driver: 'pdo_sqlite'
        charset: utf8mb4
```

### Create schema and load fixtures
Open terminal and type:
```
php bin/console doctrine:schema:create
php bin/console doctrine:fixtures:load
```

### Configure cache and elasticsearch
#### ElasticSearch
Open `config/services.yaml` and change these:
```yaml
elasticsearch:
      index: digikala
      hosts:
      - host: localhost
        port: 9200
```
Host parameters are same as [here](https://www.elastic.co/guide/en/elasticsearch/client/php-api/current/_configuration.html).

After configuring elastic search run these commands:
```
php bin/console elastic-search:install # if it goes wrong don't continue
php bin/console elastic-search:index 'App\Entity\Product' --clear-type product
``` 

#### Configuring cache
Settings are in `config/packages/framework.yaml` and `config/packages/doctrine.yaml`.
Default is `redis://localhost:6379`. You can set different caches for different purposes.

For clearing cache type this:
```
php bin/console cache:clear
php 
```

## Run server
```
php bin/console server:run
```