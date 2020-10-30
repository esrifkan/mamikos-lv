FROM mariadb:10.4.10-bionic

##### Set Timezone #####
ARG TZ=UTC
ENV TZ ${TZ}
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone && chown -R mysql:root /var/lib/mysql/
ADD mysqld.cnf /etc/mysql/mysql.conf.d/mysqld.cnf

CMD ["mysqld"]

EXPOSE 3306