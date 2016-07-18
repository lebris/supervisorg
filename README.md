# Supervisorg

**/!\ WIP**

A dashboard for supervisord.

## Installation

```shell
$ git clone https://github.com/lebris/supervisorg.git supervisorg
$ cd supervisorg
$ php composer.phar install
$ ./start
```

The `start` helper is a shell script that hydrate the configuration files, compile the less files and dump assets.

## Try it !
First the demo requires some images to be built.

```shell
$ cd docker/demo/images && ./build_all_images.sh
```

Then run the containers :
```shell
$ cd docker/demo
$ USER_ID=$(id -u) GROUP_ID=$(id -g) WEB_PORT=80 RMQ_PORT=15672 docker-compose up -d
```

To access to the dashboard `http://<DOCKER_MACHINE_IP>`
