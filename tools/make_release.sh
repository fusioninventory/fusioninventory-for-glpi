#!/bin/bash
# /**
#  * ---------------------------------------------------------------------
#  * GLPI - Gestionnaire Libre de Parc Informatique
#  * Copyright (C) 2015-2018 Teclib' and contributors.
#  *
#  * http://glpi-project.org
#  *
#  * based on GLPI - Gestionnaire Libre de Parc Informatique
#  * Copyright (C) 2003-2014 by the INDEPNET Development Team.
#  *
#  * ---------------------------------------------------------------------
#  *
#  * LICENSE
#  *
#  * This file is part of GLPI.
#  *
#  * GLPI is free software; you can redistribute it and/or modify
#  * it under the terms of the GNU General Public License as published by
#  * the Free Software Foundation; either version 2 of the License, or
#  * (at your option) any later version.
#  *
#  * GLPI is distributed in the hope that it will be useful,
#  * but WITHOUT ANY WARRANTY; without even the implied warranty of
#  * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#  * GNU General Public License for more details.
#  *
#  * You should have received a copy of the GNU General Public License
#  * along with GLPI. If not, see <http://www.gnu.org/licenses/>.
#  * ---------------------------------------------------------------------
# */
if [ ! "$#" -eq 2 ]
then
 echo "Usage $0 fi_git_dir release";
 exit ;
fi

read -p "Are translations up to date? [Y/n] " -n 1 -r
echo    # (optional) move to a new line
if [[ ! $REPLY =~ ^[Yy]$ ]]
then
    [[ "$0" = "$BASH_SOURCE" ]] && exit 1 || return 1 # handle exits from shell or function but don't exit interactive shell
fi

INIT_DIR=$1;
RELEASE=$2;

# test glpi_cvs_dir
if [ ! -e $INIT_DIR ]
then
 echo "$1 does not exist";
 exit ;
fi

INIT_PWD=$PWD;

if [ -e /tmp/fusioninventory ]
then
 echo "Delete existing temp directory";
\rm -rf /tmp/fusioninventory;
fi

echo "Copy to  /tmp directory";
git checkout-index -a -f --prefix=/tmp/fusioninventory/

echo "Move to this directory";
cd /tmp/fusioninventory;

echo "Check version"
if grep --quiet $RELEASE setup.php; then
  echo "$RELEASE found in setup.php, OK."
else
  echo "$RELEASE has not been found in setup.php. Exiting."
  exit 1;
fi
if grep --quiet $RELEASE fusioninventory.xml; then
  echo "$RELEASE found in fusioninventory.xml, OK."
else
  echo "$RELEASE has not been found in fusioninventory.xml. Exiting."
  exit 1;
fi
if grep --quiet $RELEASE js/footer.js; then
  echo "$RELEASE found in js/footer.js, OK."
else
  echo "$RELEASE has not been found in js/footer.js. Exiting."
  exit 1;
fi

echo "Check XML WF"
if ! xmllint --noout fusioninventory.xml; then
   echo "XML is *NOT* well formed. Exiting."
   exit 1;
fi

echo "Retrieve PHP vendor"
composer install --no-dev --optimize-autoloader --prefer-dist --quiet

echo "Set version and official release"
sed \
   -e 's/"PLUGIN_FUSIONINVENTORY_OFFICIAL_RELEASE", "0"/"PLUGIN_FUSIONINVENTORY_OFFICIAL_RELEASE", "1"/' \
   -e 's/ SNAPSHOT//' \
   -i '' setup.php

echo "Minify stylesheets and javascripts"
$INIT_PWD/vendor/bin/robo minify

echo "Compile locale files"
./tools/update_mo.pl

echo "Delete various scripts and directories"
\rm -rf vendor;
\rm -rf RoboFile.php;
\rm -rf tools;
\rm -rf phpunit;
\rm -rf tests;
\rm -rf .gitignore;
\rm -rf .travis.yml;
\rm -rf .coveralls.yml;
\rm -rf phpunit.xml.dist;
\rm -rf composer.json;
\rm -rf composer.lock;
\rm -rf .composer.hash;
\rm -rf ISSUE_TEMPLATE.md;
\rm -rf PULL_REQUEST_TEMPLATE.md;
\rm -rf .tx;
\rm -rf fusioninventory.xml;
\rm -rf screenshots;
\find pics/ -type f -name "*.eps" -exec rm -rf {} \;

echo "Creating tarball";
cd ..;
tar cjf "fusioninventory-$RELEASE.tar.bz2" fusioninventory

cd $INIT_PWD;

echo "Deleting temp directory";
\rm -rf /tmp/fusioninventory;

echo "The Tarball is in the /tmp directory";
