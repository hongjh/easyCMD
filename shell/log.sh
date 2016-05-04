#!/bin/sh
WEB_ROOT=$(dirname `pwd`);
while true; do
    echo "start php script shake crontab.."
    /usr/local/php/bin/php $WEB_ROOT/my.php
    echo "respawn the worker .."
done
