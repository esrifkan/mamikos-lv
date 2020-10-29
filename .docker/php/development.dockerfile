FROM php:7.2.30-fpm-alpine

RUN apk add --no-cache supervisor

RUN docker-php-ext-install mysqli mbstring pdo pdo_mysql tokenizer

RUN apk add --no-cache --virtual .build-deps zlib-dev && docker-php-ext-install zip && apk del .build-deps

# Install and enable xdebug
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
  && pecl install xdebug && docker-php-ext-enable xdebug \
  && apk del .build-deps
COPY ./xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Check PHP version:
RUN set -xe; php -v | head -n 1 | grep -q "PHP ${PHP_VERSION}"

# Install composer and add its bin to the PATH.
RUN curl -s http://getcomposer.org/installer | php && \
  echo "export PATH=${PATH}:/var/www/vendor/bin" >> ~/.bashrc && \
  mv composer.phar /usr/local/bin/composer

COPY ./php.ini /usr/local/etc/php/php.ini
COPY ./pool.conf /usr/local/etc/php-fpm.d/
COPY ./config/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

#
#--------------------------------------------------------------------------
# Crontab
#--------------------------------------------------------------------------
#
COPY ./config/crontabs /var/spool/cron/crontabs/default
RUN cat /var/spool/cron/crontabs/default >> /var/spool/cron/crontabs/root
RUN touch /var/log/cron.log

# Clean up
RUN rm -rf /tmp/* /var/tmp/*

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
WORKDIR /var/www