#!/bin/bash

soft='GLPI - Example plugin'
version='0.84'
email=glpi-translation@gna.org
copyright='INDEPNET Development Team'

#xgettext *.php */*.php -copyright-holder='$copyright' --package-name=$soft --package-version=$version --msgid-bugs-address=$email -o locales/en_GB.po -L PHP --from-code=UTF-8 --force-po  -i --keyword=_n:1,2 --keyword=__ --keyword=_e

# Only strings with domain specified are extracted (use Xt args of keyword param to set number of args needed)

xgettext *.php */*.php -o locales/glpi.pot -L PHP --add-comments=TRANS --from-code=UTF-8 --force-po  \
      --keyword=_n:1,2,4t --keyword=__s:1,2t --keyword=__:1,2t --keyword=_e:1,2t --keyword=_x:1c,2,3t --keyword=_ex:1c,2,3t \
      --keyword=_sx:1c,2,3t --keyword=_nx:1c,2,3,4t


### for using tx :
##tx set --execute --auto-local -r GLPI_example.glpi-084-current 'locales/<lang>.po' --source-lang en --source-file locales/glpi.pot
## tx push -s
## tx pull -a


