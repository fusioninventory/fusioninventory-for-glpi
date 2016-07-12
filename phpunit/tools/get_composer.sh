#!/bin/sh
which composer
composer_exists=$?
if [ $composer_exists -ne 1 ]; then
  echo "Composer already installed";
  composer self-update > /dev/null 2>&1
else
  echo "Installing composer ..."
  php -r "readfile('https://getcomposer.org/installer');" | php -- --filename=composer
fi
