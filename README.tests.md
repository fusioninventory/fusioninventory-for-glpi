# How to run tests


## Install GLPI tests

```
cd glpi/
php bin/console glpi:database:install --config-dir=tests --force
php bin/console glpi:plugin:install --config-dir=tests --username=glpi fusioninventory
php bin/console glpi:plugin:activate --config-dir=tests fusioninventory
```

## Run FusionInventory tests

```
cd plugins/fusioninventory/
php vendor/bin/phpunit --testdox tests/
```
