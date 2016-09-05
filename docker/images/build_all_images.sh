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

for i in supervisorg workers supervisord packager; do
  cd $i
  make ${NO_CACHE}
  cd -
done
