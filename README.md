# Digikala PHP-Task

![screencapture-localhost-8000-products-1516818437490](https://user-images.githubusercontent.com/4481808/35349951-a241c27c-0151-11e8-8c07-0e890986aead.png)


This is a simple e-commerce written in PHP 7. Lots of Symfony components and bundles are used.

## Guide
Do following steps to install the app. After that, you have to register on the website. Then you have to add `ROLE_ADMIN` to your user. Then log out and log in to get able to use admin panel.

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

## Run server
```
php bin/console server:run
```

## Creating admin user
You have to create a user by register form.

Making a user admin:
```
php bin/console user:add-role user ROLE_ADMIN
```

## Change user password
```
php bin/console user:change-password user password
```

