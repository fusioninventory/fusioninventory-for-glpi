#!/bin/sh

pear config-set auto_discover 1
pear install -R ./pear/ \
    pear.phpunit.de/PHPUnit \
    phpunit/DbUnit \
    phpunit/PHP_Invoker \
    phpunit/PHPUnit_Selenium \
    phpunit/PHPUnit_Story \
    phpunit/PHPUnit_SkeletonGenerator \
    phpunit/PHPUnit_TestListener_DBUS \
    phpunit/PHPUnit_TestListener_XHProf


