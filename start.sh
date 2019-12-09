#!/bin/bash

echo "Run Docker containers!"

if [ ! -f .env ]; then
  cp .env.example .env
  sudo chmod 0777 .env
fi

docker-compose -p test-network up --build -d
docker exec -it php-fpm composer install
sudo chmod -R 0777 ./storage/ ./bootstrap/cache/ ./vendor/ ./docker/data/
docker exec -it php-fpm php artisan key:generate
docker exec -it php-fpm php artisan migrate
docker exec -it php-fpm php artisan db:seed
docker exec -it php-fpm phpunit
sudo chmod 0777 composer.lock
