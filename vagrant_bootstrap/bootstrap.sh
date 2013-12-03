#!/usr/bin/env bash

# Update the box
# --------------
# Downloads the package lists from the repositories
# and "updates" them to get their info on the newest
# versions and their dependencies.
apt-get update

# Install VIM
apt-get install -y vim

# APACHE
# ------
# Install
apt-get install -y apache2
# Remove /var/www default
rm -rf /var/www
# Symlink /vagrant to /var/www
# Add ServerName to httpd.conf
echo "ServerName localhost" > /etc/apache2/httpd.conf
# Setup hosts file
VHOST=$(cat <<EOF
<VirtualHost *:80>
  DocumentRoot "/vagrant/public"
  ServerName localhost
  <Directory /vagrant/public>
    Options Indexes FollowSymLinks
    AllowOverride All
    Order Allow, Deny
    Allow from All
  </Directory>
</VirtualHost>
EOF
)
echo "${VHOST}" > /etc/apache2/sites-enabled/000-default
# Enable mod_rewrite
a2enmod rewrite
# Restart Apache
service apache2 restart


# PHP Latest Version
# ------------------
apt-get install -y libapache2-mod-php5
# Add add-apt-repository binary
apt-get install -y python-software-properties
# Add latest PHP
add-apt-repository ppa:ondrej/php5
# Update
apt-get update

# PHP stuff
# ---------
# Command-Line Interpreter
apt-get install -y php5-cli
# MySQL database connections directly from PHP
apt-get install -y php5-mysql
# cURL support
apt-get install -y php5-curl
# Module for MCrypt functions in PHP
apt-get install -y php5-mcrypt
# gd
apt-get install -y php5-gd
# xdebug
apt-get install -y php5-xdebug
# APC
apt-get install -y php-apc

# cURL
# ----
apt-get install -y curl

# Mysql
# -----
# Ignore the post install questions
export DEBIAN_FRONTEND=noninteractive
# Install MySQL quietly
apt-get -q -y install mysql-server-5.5

# Git
# ---
apt-get install git-core

# Install Composer
# ----------------
curl -s https://getcomposer.org/installer | php
# Make Composer available globally
mv composer.phar /usr/local/bin/composer


# Setup a database
if [ ! -f /var/log/databasesetup ];
then
    echo "CREATE DATABASE IF NOT EXISTS bonfire" | mysql
    echo "CREATE USER 'bonfire'@'localhost' IDENTIFIED BY ''" | mysql
    echo "GRANT ALL PRIVILEGES ON bonfire.* TO 'bonfire'@'localhost' IDENTIFIED BY ''" | mysql

    touch /var/log/databasesetup
fi