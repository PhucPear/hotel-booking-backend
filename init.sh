#!/bin/bash

ENV=${1:-dev}

# màu mè chút cho ngầu 😎
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # no color

print_line() {
  echo -e "${YELLOW}=====================================${NC}"
}

print_title() {
  echo -e "${GREEN}$1${NC}"
}

print_step() {
  echo -e "${YELLOW}➜ $1...${NC}"
}

print_success() {
  echo -e "${GREEN}✔ $1${NC}"
}

print_error() {
  echo -e "${RED}✖ $1${NC}"
}

# header
echo ""
print_line
print_title "🚀 STARTING SETUP ($ENV)"
print_line
echo ""

# chọn môi trường
if [ "$ENV" = "dev" ]; then
  COMPOSE_FILE=docker-compose.dev.yml
elif [ "$ENV" = "production" ]; then
  COMPOSE_FILE=docker-compose.prod.yml
else
  print_error "Invalid environment. Use dev or prod"
  exit 1
fi

# chạy docker
print_step "Building & starting containers"
docker compose -f "$COMPOSE_FILE" down -v
docker compose -f "$COMPOSE_FILE" build
docker compose -f "$COMPOSE_FILE" up -d

print_step "Waiting for app to boot"
sleep 6

# setup laravel
print_step "Installing dependencies & setup Laravel"
docker exec app_hotel_booking bash -c "
composer install &&
php artisan key:generate
"

# Setting permissions
chmod -R 777 storage bootstrap/cache

# dev vs prod
if [ "$ENV" = "dev" ]; then
  print_step "Running migrations DEV only"
  docker exec app_hotel_booking bash -c "
  php artisan migrate &&
  php artisan db:seed &&
  php artisan optimize &&
  composer dump-autoload -o
  "
fi

if [ "$ENV" = "production" ]; then
  print_step "Optimizing Laravel (PROD)"
  docker exec app_hotel_booking bash -c "
  php artisan config:cache &&
  php artisan route:cache &&
  php artisan view:cache
  "
fi

# done
echo ""
print_line
print_success "🎉 Setup completed for $ENV!"
print_line
echo ""