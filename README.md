# PHP-docker


## Set up

First copy the `.env.example` file as `.env`. Inside this file enter the credentials that you want.
### Naming the containers.
* We have 3 containers that you can see in `docker-compose.yml` file: `app`, `db` & `nginx`.
All of these have a key called `container_name`. You can change it's value to anything that suits your app.

* If you want to choose a port on which to run the container you can change `8005` to some other number
inside the `docker-compose.yml` file, in the following part.

nginx:
image: nginx:alpine
container_name: reponame-nginx
restart: unless-stopped
ports:
  - 8005:80
volumes:
  - ./:/var/www
  - ./docker/nginx:/etc/nginx/conf.d/
networks:
  reponame:
    ipv4_address: 192.168.0.4

* Now you can run `docker compose up --build`. This will build all the containers.

* After the containers are built, you can only run `docker compose up` & `docker compose down`.
You can also run `docker compose u -d` if you don't want to see output in the terminal.

## Running commands
### How to run a command inside the docker container
* List all the running containers: `docker ps`
* Run a command inside a container while you are outside.

docker exec -it <container-name> <command>


* You could also enter the container shell & run commands from inside it as well.

docker exec -it <container-name> /bin/bash


### Composer
* `composer` has to be run inside the app container, that's where it is installed.
To run in inside the app container use this command 

composer dump-autoload
docker exec -it <container-name> composer <command>


### Database
If you want to access the database run the following command:

docker exec -it <container-name> mysql -u <db-username> -p

This will prompt you for a database password that's in the .env file.

### Accessing the website
Inside `docker-compose.yml` file the following code is telling us on which port you can open the website.

ports:
  - 8005:80


This means that after docker containers are up, you can visit `localhost:8005` & should be able to see your website.

Note: `localhost:8005` will serve `public/index.php`# PHP-docker

## Set up

First copy the `.env.example` file as `.env`. Inside this file enter the credentials that you want.
### Naming the containers.
* We have 3 containers that you can see in `docker-compose.yml` file: `app`, `db` & `nginx`.
All of these have a key called `container_name`. You can change it's value to anything that suits your app.

* If you want to choose a port on which to run the container you can change `8005` to some other number
inside the `docker-compose.yml` file, in the following part.

nginx:
image: nginx:alpine
container_name: reponame-nginx
restart: unless-stopped
ports:
  - 8005:80
volumes:
  - ./:/var/www
  - ./docker/nginx:/etc/nginx/conf.d/
networks:
  reponame:
    ipv4_address: 192.168.0.4

* Now you can run `docker compose up --build`. This will build all the containers.

* After the containers are built, you can only run `docker compose up` & `docker compose down`.
You can also run `docker compose u -d` if you don't want to see output in the terminal.

## Running commands
### How to run a command inside the docker container
* List all the running containers: `docker ps`
* Run a command inside a container while you are outside.

docker exec -it <container-name> <command>


* You could also enter the container shell & run commands from inside it as well.

docker compose -exec -it <container-name> /bin/bash


### Composer
* `composer` has to be run inside the app container, that's where it is installed.
To run in inside the app container use this command 

docker compose exec -it <container-name> composer <command>


### Database
If you want to access the database run the following command:

docker compose exec -it <container-name> mysql -u <db-username> -p

This will prompt you for a database password that's in the .env file.

### Accessing the website
Inside `docker-compose.yml` file the following code is telling us on which port you can open the website.

ports:
  - 8005:80


This means that after docker containers are up, you can visit `localhost:8005` & should be able to see your website.

Note: `localhost:8005` will serve `public/index.php`