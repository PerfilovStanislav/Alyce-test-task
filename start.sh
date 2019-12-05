#!/bin/bash

echo "Run Docker containers!"

NETWORK=test-network

if [ $(docker network ls | grep $NETWORK | wc -l) -lt 1 ]
then
    docker network create --driver=bridge $NETWORK
else
    echo "общая docker сеть уже была создана"
fi

if [ ! -f .env ]; then
  cp .env.example .env
  sudo chmod 0777 .env
fi

docker-compose -p $NETWORK up -d
docker exec -it test-php-fpm composer install
sudo chmod -R 0777 ./storage/ ./bootstrap/cache/ ./docker/ ./vendor/
docker exec -it test-php-fpm php artisan key:generate
docker exec -it test-php-fpm php artisan migrate
docker exec -it test-php-fpm php artisan db:seed
