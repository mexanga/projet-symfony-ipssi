#!/bin/sh

composer dumpa
docker cp . symfony-film-web:/var/www/project
docker-compose up --build -d
docker exec -it projet-film-symfony-web bash -c "chmod -R 777 /var/www/project/var/cache/dev && chmod -R 777 /var/www/project/var/log && chmod -R 777 /var/log"