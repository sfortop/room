set -x

while ! mysqladmin ping -h"$MYSQL_HOST" --silent; do
    sleep 1
done

vendor/bin/phinx migrate -c config/phinx.php

cd /var/www/room/public
php-fpm > /var/log/php-fpm.room.log &
nginx -g "daemon off;"