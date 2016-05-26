# Build supervisorg docker images
## Build the images

```shell
$ ./build.sh <git ref> <tag>
```

This command will build the supervisorg and workers image.

Parameters :
 * `<git_ref>` : a git commit hash, tag or branch name
 * `<tag>` : tag to assign to the built images

This will clone the sources, prepare the application (dependencies, assets, etc.), and copy (to `/var/www/supervisorg`) supervisorg sources in the image.

Example:
```
$ ./build.sh master latest
```

## Prerequisites

Before running this command you must build the supervisorg/packager image.

```
$ cd path/to/supervisorg/docker/packager
$ make
```

## Test the built image

```shell
$ docker-compose up -d
```
