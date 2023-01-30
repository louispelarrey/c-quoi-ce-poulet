SERVER_NAME=your-domain-name.example.com \
APP_SECRET=ChangeMe \
POSTGRES_PASSWORD=ChangeMe \
CADDY_MERCURE_JWT_SECRET=ChangeMe \
docker compose -f docker-compose.yml -f docker-compose.prod.yml up -d
