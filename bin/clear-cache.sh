php bin/console doctrine:schema:drop --force
php bin/console doctrine:schema:create
php bin/console doctrine:cache:clear-metadata
php bin/console doctrine:cache:clear-query
php bin/console doctrine:cache:clear-result
php bin/console cache:pool:clear app.cache.search
php bin/console cache:clear
php bin/console doctrine:fixtures:load
php bin/console elastic-search:install
php bin/console elastic-search:index 'App\Entity\Product'

