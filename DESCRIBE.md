# Digikala PHP Task 


- I used `Composer` as package manager
- I used `Symfony Framework bundle` because it has a good convention for directory structures and helped me quick start.
- I used `Twig` as template engine.
- For having a more abstract communication with the primary database I used `Doctrine`.
- I used `Symfony Cache` for managing Elasticsearch cache. It's easy to change cache adapter. I used `predis`.
- For pagination in admin panel and products list is used `pagerfanta-bundle`.
- I used `Symfony console` to make an interface for some functionalities (Add roles to a user, Change password, Set mappings in Elasticsearch and Index entities exist before installing Elasticsearch.)
- For dealing with forms I used `Symfony forms`.
I used `Symfony validation` for validating forms.

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
- Symfony security is set up for reading POST parameters of `/login` path automatically and login if its correct.
- After registeration user automatically logs in. The code is a little tricky here.
## Searching and showing products
Controller:
```
src/Controller/ProductsController.php
```
- There is two tables for `Product` and `Variant` entities.
- Every `Product` may have many `Variant` entities.

### Index products in Elasticsearch
I've made a Service for communicating with Elasticsearch. 
A product and its variants are indexed together. In order to search get work, you have to put mappings first. I made a command for this:
```
php bin/console elastic-search:install
```

When you do an operation on entities an event would be fired by doctrine. After firing these events Elasticsearch service would send these entities to `ElasticSearchEntityMappings`. EntityMappings handle operations on ElasticSearch database.
I think it is a common design pattern. 

#### ElasticSearchEntityMappings
Entity mappings have to handle operations on one type in an index. They have to implement `ElasticSearchMappings` interface.
Mappings would be registered to ElasticSearchService at compile time.

### Searching
Search queries are made in `ProductRepository`. Caching results are handled by `ElasticSearchService`.

## Administration
You must have `ROLE_ADMIN` to access admin panel.
There is a command for adding a role to a user:
```
php bin/console user:add-role user ROLE_ADMIN
```
After using this command you have to log out and then log in.