# Usage

## Install the application

Make sure to install dependencies and run the `start` helper to build the application requirements (less, assets, etc).

## Build images

```
$ cd /path/to/supervisorg/docker/images
$ ./build_all_images.sh
```

## Run the demo

```
$ ./start_env.sh
```

Options :
 * `--web-port` : port to publish to reach the supervisorg web interface, default to **80**
 * `--rmq-port` : port to publish to reach the rabbitmq web interface default to **15672**
