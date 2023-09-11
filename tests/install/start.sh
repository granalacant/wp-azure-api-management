#!/bin/sh

# wget & unzip needed to download and extract plugin
apt-get update
apt-get install -y wget unzip

# Download and extract plugin
wget https://github.com/granalacant/wp-azure-api-management/archive/refs/heads/main.zip -O /tmp/main.zip
unzip -o /tmp/main.zip -d /var/www/html/wp-content/plugins/
rm /tmp/main.zip

# Init wordpress
exec docker-entrypoint.sh apache2-foreground