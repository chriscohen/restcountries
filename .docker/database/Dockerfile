FROM mysql/mysql-server:5.7.22

ENV MYSQL_ROOT_PASSWORD=root
ENV MYSQL_DATABASE=cube

RUN yum install -y vim

COPY db_config.sql /docker-entrypoint-initdb.d/db_config.sql
