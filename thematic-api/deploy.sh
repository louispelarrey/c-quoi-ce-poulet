SERVER_NAME=cquoicepoulet.com \
APP_SECRET=89NC75ZAN0PQQ348FHS8f \
POSTGRES_PASSWORD=ChangeMe \
CADDY_MERCURE_JWT_SECRET=ChangeMe \
docker compose -f docker-compose.yml -f docker-compose.prod.yml up -d
