#!/bin/sh

USER_ID=${USER_ID:-33}
GROUP_ID=${GROUP_ID:-33}

if [ "$USER_ID" -ne 33 ] || [ "$GROUP_ID" -ne 33 ]; then
    groupmod --gid $GROUP_ID www-data
    usermod --uid $USER_ID --gid $GROUP_ID www-data
fi

exec "$@"
