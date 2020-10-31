FROM php:7.2.30-fpm-alpine

RUN apk add --no-cache supervisor

RUN docker-php-ext-install mysqli mbstring pdo pdo_mysql tokenizer

RUN apk add --no-cache --virtual .build-deps zlib-dev && docker-php-ext-install zip && apk del .build-deps

# Check PHP version:
RUN set -xe; php -v | head -n 1 | grep -q "PHP ${PHP_VERSION}"

# Install composer and add its bin to the PATH.
RUN curl -s http://getcomposer.org/installer | php && \
  echo "export PATH=${PATH}:/var/www/vendor/bin" >> ~/.bashrc && \
  mv composer.phar /usr/local/bin/composer

COPY .docker/php/php.ini /usr/local/etc/php/php.ini
COPY .docker/php/pool.conf /usr/local/etc/php-fpm.d/
COPY --chown=www-data:www-data ./ /var/www

### Crontab ###
COPY .docker/php/crontabs/default /var/spool/cron/crontabs/
RUN cat /var/spool/cron/crontabs/default >> /var/spool/cron/crontabs/root
RUN touch /var/log/cron.log

COPY .docker/php/config/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Clean up
RUN rm -rf /tmp/* /var/tmp/*

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
WORKDIR /var/www