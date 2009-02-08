@echo off
echo Chemins php et du script a lancer

SET path_php="C:\OCSNG\xampp\php"
SET plugin_glpi="C:\OCSNG\xampp\htdocs\glpi\plugins\tracker\scripts"

echo Definition du path

PATH = %PATH%;%path_php%


IF EXIST %plugin_glpi%\run_bat.php GOTO RUN

IF NOT EXIST %plugin_glpi%\run_bat.php GOTO EXIT

:RUN
echo Lancement du script
php %plugin_glpi%\run_bat.php %1
GOTO FIN

:EXIT
echo Le chemin vers run_bat.php est incorrect
pause

:FIN




