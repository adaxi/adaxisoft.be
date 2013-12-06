#!/usr/bin/env bash

apt-get update
apt-get install -y apache2 libapache2-mod-php5
rm -rf /var/www
ln -fs /vagrant /var/www
a2enmod rewrite


echo <<EOF > /var/www/api/.htaccess
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(.*)$ index.php [QSA,L]
EOF;