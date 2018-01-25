# Digikala PHP Task 


- I needed a quick start, so I chose `Symfony Framework bundle` and its useful convention for directory structures.
- I used `Twig` as the template engine.
- For reaching an abstract communication with the primary database I used `Doctrine` ORM.
- I used `Symfony Cache` for managing Elasticsearch cache. It's easy to change cache adapter. I used `predis`.
- `pagerfanta-bundle` is used to paginate in admin panel and products list.
- I used `Symfony Console` to make an interface for the needed functions (e.g. add roles to a user, change passwords, set mappings in Elasticsearch and index entities).
- For handling form creation and submission I used `Symfony Forms` and `Symfony Validation` for validating the submitted values.

## User Login and Registration
Controller:
```
src/Controller/AuthenticationController.php
```
Forms:
```
src/Form/UserLoginType.php
src/Form/UserRegisterType.php
```
- `Symfony Security` is set up for reading POST parameters of `/login` path and login automatically  if it is correct.
- Clients log in automatically after the registration.

## Searching and viewing products
Controller:
```
src/Controller/ProductsController.php
```

- There are two tables for `Product` and `Variant` entities.
- Every `Product` may have many `Variant` entities.


### Indexing products in Elasticsearch
I made a service for communicating with Elasticsearch. 
A product and its variants are indexed together. In order to make the advanced search form work properly, I made the below command to put mappings in  Elasticsearch index:
```
php bin/console elastic-search:install
```

When you do an operation on entities, an event would be fired by `Doctrine`. After firing these events Elasticsearch service would send these entities to `ElasticSearchEntityMappings`. EntityMappings handle operations on ElasticSearch database.


#### `ElasticSearchService`
This service handles indexing entities by `ElasticSearchMapping` classes.

#### `ElasticSearchMapping` classes
EntityMapping classes handle how entities get indexed in ElasticSearch. Every class is in charge of managing one document type in the index. They have to implement `ElasticSearchMappings` interface.
Mappings would be registered to `ElasticSearchService` at Kernel's compile time.

### Searching
Search queries are made in `ProductRepository`. Caching results are handled by `ElasticSearchService`.

## Administration
You must have `ROLE_ADMIN` to access admin panel.
There is a command for adding a role to a user:
```
php bin/console user:add-role user ROLE_ADMIN
```
After using this command you have to log out and log in again.