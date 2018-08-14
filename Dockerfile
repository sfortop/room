FROM fortop/php:core

ARG ENV=dev
COPY . /var/www/room
COPY .build/do.sh /bin/do.sh
COPY ".build/www.$ENV.conf" /usr/local/etc/php-fpm.d/www.conf
COPY .build/room.nginx.conf /etc/nginx/sites-enabled/default
COPY .build/log.nginx.conf /etc/nginx/conf.d/
COPY .build/fpm-config.ini /usr/local/etc/php/conf.d/
WORKDIR /var/www/room

RUN composer install -n --no-progress
CMD ["bash","-c","/bin/do.sh"]

