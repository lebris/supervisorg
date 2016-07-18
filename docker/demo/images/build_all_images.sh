#!/bin/sh

set -e
set -u

NO_CACHE=""

for i in "$@"; do
  case $i in
    --no-cache)
      NO_CACHE="nocache"
      ;;
  esac
done

for i in php php-apache workers supervisord; do
  cd $i
  make ${NO_CACHE}
  cd -
done
