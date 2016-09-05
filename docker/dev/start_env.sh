#! /bin/sh

set -e
set -u

WEB_PORT=80
RMQ_PORT=15672

for i in "$@"; do
  case $i in
      --web-port=*)
        WEB_PORT=${i#--web-port=}
        ;;
      --rmq-port=*)
        RMQ_PORT=${i#--rmq-port=}
        ;;
  esac
done

[ -z "${WEB_PORT}" ] && { echo 'No web port provided, exiting.'; exit 1; }
[ -z "${RMQ_PORT}" ] && { echo 'No rabbitmq port provided, exiting.'; exit 1; }

export USER_ID=$(id -u)
export GROUP_ID=$(id -g)
export WEB_PORT
export RMQ_PORT

docker-compose up -d
