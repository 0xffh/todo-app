ToDo app
================

* run: git clone git@github.com:0xffh/todo-app.git todo-app

Usage (local ENV):
--------
* run: cd todo-app-test
* copy *docker-compose.local.yml.dist* to *docker-compose.local.yml* and make the necessary changes
* run: docker-compose -f docker-compose.yml -f docker-compose.local.yml up --build -d
* copy *.entrypoint.local.sh.dist* to *.entrypoint.local.sh* and make the necessary changes
* run: sh .entrypoint.local.sh

application on 127.0.0.1:8085 or http://todo-app.loc

phpMyAdmin on 127.0.0.1:8086 or http://pma.todo-app.loc

Tests (tests ENV):
-------------
* run: cd todo-app-test
* copy *docker-compose.test.yml.dist* to *docker-compose.test.yml* and make the necessary changes
* run: docker-compose -f docker-compose.yml -f docker-compose.test.yml up --build -d
* copy *.entrypoint.local.test.sh.dist* to *.entrypoint.local.test.sh* and make the necessary changes
* run: sh .entrypoint.local.test.sh
* docker-compose -f docker-compose.yml -f docker-compose.test.yml exec php php vendor/bin/phpunit 

application on 127.0.0.1:8087 or http://todo-app-test.loc

phpMyAdmin on 127.0.0.1:8088 or http://pma.todo-app-test.loc

Example ServerServices/docker-compose.yml for use API via virtual host:

    version: '2'
    services:
      nginx-proxy:
        image: jwilder/nginx-proxy
        container_name: nginx-proxy-local
        restart: always
        ports:
          - "80:80"
        volumes:
          - /var/run/docker.sock:/tmp/docker.sock:ro
          
Also need configure */etc/hosts* file