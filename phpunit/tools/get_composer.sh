#!/bin/sh
which composer
composer_exists=$?
if [ $composer_exists -ne 1 ]; then
  echo "Composer already installed";
  composer self-update
else
  echo "Installing composer ..."
  php -r "readfile('https://getcomposer.org/installer');" | php -- --filename=composer
fi
