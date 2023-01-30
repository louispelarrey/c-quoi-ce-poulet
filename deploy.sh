cd thematic-api
SERVER_NAME=cquoicepoulet.com \
APP_SECRET=8b36bb475e22928f84Baz0c4d9d6b4a0ecc \
POSTGRES_PASSWORD=lepoul3tctr0bon93 \
CADDY_MERCURE_JWT_SECRET=lepoul3tctr0bon93 \
docker compose -f docker-compose.yml -f docker-compose.prod.yml up -d