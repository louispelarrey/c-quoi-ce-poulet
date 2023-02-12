docker compose exec php bin/console --env=test doctrine:database:create
docker compose exec php bin/console --env=test doctrine:schema:create
docker compose exec php bin/console --env=test doctrine:fixtures:load
