#!/bin/bash

set -eu

if [ ! -f .env ]
then
  master_password=$(cat /dev/urandom | base64 | head -c 16)
  slave_password=$(cat /dev/urandom | base64 | head -c 16)

  cat .env.default \
    | sed -e "s|^DB_MASTER_PASSWORD=$|&${master_password}|" \
          -e "s|^DB_SLAVE_PASSWORD=$|&${slave_password}|" \
    > .env
fi

source .env

echo "Preparing the slave database..."

mysql -h ${DB_SLAVE_HOST} -P ${DB_SLAVE_PORT} -u root -p <<SQL
CREATE DATABASE IF NOT EXISTS ${DB_SLAVE_DATABASE};

CREATE USER IF NOT EXISTS '${DB_SLAVE_USERNAME}'@'%' IDENTIFIED BY '${DB_SLAVE_PASSWORD}';

GRANT SELECT ON ${DB_SLAVE_DATABASE}.* TO '${DB_SLAVE_USERNAME}'@'%';
GRANT REPLICATION CLIENT, REPLICATION SLAVE ON *.* TO '${DB_SLAVE_USERNAME}'@'%';
SQL

echo "Preparing the master database..."

mysql -h ${DB_MASTER_HOST} -P ${DB_MASTER_PORT} -u root -p <<SQL
CREATE DATABASE IF NOT EXISTS ${DB_MASTER_DATABASE};

CREATE USER IF NOT EXISTS '${DB_MASTER_USERNAME}'@'%' IDENTIFIED BY '${DB_MASTER_PASSWORD}';

GRANT ALL PRIVILEGES ON ${DB_MASTER_DATABASE}.* TO '${DB_MASTER_USERNAME}'@'%';
SQL

./vendor/bin/phpmig migrate
