#! /bin/sh

set -e
set -u

[ $# -ne 2 ] && { echo "Usage: $0 [git reference] [tag]"; exit 1; }

GIT_REF=$1
TAG=$2

SRC_DIR=src
APP_SRC_DIR=/var/www/supervisorg

cleanup() {
    for DIR in src supervisorg/src workers/src; do
        if [ -d "$(pwd)/${DIR}" ]; then
            echo "Cleaning directory ${DIR}"
            docker run --rm \
                       -v $(pwd)/${DIR}:/tmp/${DIR} \
                       busybox \
                       sh -c "rm -rf /tmp/${DIR}/*"

           rm -rf ${DIR}
        fi
    done
}

ORIGINAL_DIR=$(pwd)

cleanup

git clone https://github.com/lebris/supervisorg.git ${SRC_DIR}
OLD_PWD=$(pwd)
cd ${SRC_DIR}
git checkout $GIT_REF
cd ${OLD_PWD}
unset OLD_PWD

docker run -v $(pwd)/${SRC_DIR}:${APP_SRC_DIR} \
           -v ~/.composer:/root/.composer \
           --rm \
           --name=supervisorg_packager \
           -ti \
           supervisorg/packager \
           /bin/su -c 'cd /var/www/supervisorg && \
                       php composer.phar install --no-dev --optimize-autoloader && \
                       vendor/bin/karma hydrate --env=prod && \
                       sh ./lessc && \
                       php package assetic:dump && \
                       chown -Rf www-data:www-data /var/www/supervisorg/*'

SERVICE_LIST='supervisorg workers'
for SERVICE in $SERVICE_LIST;do
    OLD_PWD=$(pwd)

    cd ${SERVICE}

    mv ../src .
    # .dockerignore file must be at the root directory of the context
    cp src/.dockerignore .

    make TAG=${TAG}

    rm .dockerignore
    mv src ../

    cd ${OLD_PWD}
    unset OLD_PWD
done

cleanup
