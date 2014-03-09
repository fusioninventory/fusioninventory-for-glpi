#!/bin/sh

find \
    -type f \
    -not \( \
        -path "*composer.src*" \
        -or -name "docopt.php" \
    \) \
    -name "*.php" \
    -exec sed -i -e "s/ *$//" {} \;
