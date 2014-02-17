#!/bin/sh

curl -sS https://getcomposer.org/installer | php -- --install-dir=./tools/

if [ -d "./tools/composer.src" ]; then
    rm -rf ./tools/composer
fi
php -r '$phar = new Phar("./tools/composer.phar"); $phar->extractTo("./tools/composer.src/");'

rm ./tools/composer.phar
