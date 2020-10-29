FROM nginx:1.17-alpine

RUN apk add --no-cache \
  bash \
  logrotate \
  openssl

RUN set -x ; \
  addgroup -g 82 -S www-data ; \
  adduser -u 82 -D -S -G www-data www-data && exit 0 ; exit 1

# Create 'messages' file used from 'logrotate'
RUN touch /var/log/messages
# Remove the default conf
RUN rm /etc/nginx/conf.d/default.conf

COPY nginx.conf /etc/nginx/
COPY site.conf /etc/nginx/sites-available/

ADD ./startup.sh /opt/startup.sh
RUN sed -i 's/\r//g' /opt/startup.sh

CMD ["/bin/sh", "/opt/startup.sh"]

WORKDIR /var/www

EXPOSE 80 443
