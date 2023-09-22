#!/bin/sh

# # wget & unzip needed to download and extract plugin
apt-get update
apt-get install -y wget unzip default-mysql-client

echo 'Cmd packages installed'

# Copiar los archivos base de WordPress
cp -r /usr/src/wordpress/. /var/www/html/

# Copiar el archivo wp-config.php
cp /usr/local/bin/wp-config.php /var/www/html/wp-config.php

echo 'Wp initial config ready'

while true; do
  if mysqladmin ping -h "database" -u "root" -pmypassword > /dev/null 2>&1; then
    echo "Database is ready..."
    break
  else
    echo "Waiting for Database initial config..."
    sleep 5
  fi
done

# Copiar el default alpha-open-api.json
mkdir -p /var/www/html/wp-content/uploads/2023/09/
cp -r /usr/local/bin/api.json /var/www/html/wp-content/uploads/2023/09/api.json

# Extract data from backup.sql to the database (default site, user:root, pass:root...)
mysql -h database -u root -pmypassword wordpress < /usr/local/bin/backup.sql

echo 'backup.sql successfully restored'

# Download and extract plugin (Api Management)
wget https://github.com/granalacant/wp-azure-api-management/archive/refs/heads/main.zip -O /tmp/main.zip
unzip -o /tmp/main.zip -d /var/www/html/wp-content/plugins/
rm /tmp/main.zip # Removes .zip

# Init wordpress
exec docker-entrypoint.sh apache2-foreground
