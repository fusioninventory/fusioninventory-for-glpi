<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2012 by the FusionInventory Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org/
   ------------------------------------------------------------------------

   LICENSE

   This file is part of FusionInventory project.

   FusionInventory is free software: you can redistribute it and/or modify
   it under the terms of the GNU Affero General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.

   FusionInventory is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
   GNU Affero General Public License for more details.

   You should have received a copy of the GNU Affero General Public License
   along with Behaviors. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    David Durieux
   @co-author 
   @comment   Not translate this file, use https://www.transifex.net/projects/p/FusionInventory/
   @copyright Copyright (c) 2010-2012 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010
 
   ------------------------------------------------------------------------
 */


$LANG['plugin_fusinvdeploy']['action'][0]="Add";
$LANG['plugin_fusinvdeploy']['action'][1]="Delete";
$LANG['plugin_fusinvdeploy']['action'][2]="OK";
$LANG['plugin_fusinvdeploy']['action'][3]="Select the file";
$LANG['plugin_fusinvdeploy']['action'][4]="File saved!";
$LANG['plugin_fusinvdeploy']['action'][5]="Or URL";
$LANG['plugin_fusinvdeploy']['action'][6]="Add return code";
$LANG['plugin_fusinvdeploy']['action'][7]="Delete return code";

$LANG['plugin_fusinvdeploy']['action_message'][1]="Title";
$LANG['plugin_fusinvdeploy']['action_message'][2]="Content";
$LANG['plugin_fusinvdeploy']['action_message'][4]="Informations";
$LANG['plugin_fusinvdeploy']['action_message'][5]="report of the install";

$LANG['plugin_fusinvdeploy']['check'][0]="Register key exist";
$LANG['plugin_fusinvdeploy']['check'][1]="Register key missing";
$LANG['plugin_fusinvdeploy']['check'][2]="Register key value";
$LANG['plugin_fusinvdeploy']['check'][3]="File exist";
$LANG['plugin_fusinvdeploy']['check'][5]="File size greater";
$LANG['plugin_fusinvdeploy']['check'][6]="SHA-512 hash value";
$LANG['plugin_fusinvdeploy']['check'][7]="Free space";
$LANG['plugin_fusinvdeploy']['check'][8]="Filesize equal to";
$LANG['plugin_fusinvdeploy']['check'][9]="Filesize lower than";

$LANG['plugin_fusinvdeploy']['command_envvariable'][1]="Environment variable";

$LANG['plugin_fusinvdeploy']['command_status'][0]="Make your choice...";
$LANG['plugin_fusinvdeploy']['command_status'][3]="Expected return code";
$LANG['plugin_fusinvdeploy']['command_status'][4]="Invalid return code";
$LANG['plugin_fusinvdeploy']['command_status'][5]="Expected regular expression";
$LANG['plugin_fusinvdeploy']['command_status'][6]="Invalid regular expression";

$LANG['plugin_fusinvdeploy']['config'][0]="Адрес сервера GLPI (без http://)";
$LANG['plugin_fusinvdeploy']['config'][1]="Корневая папка для отправки файлов с сервера";
$LANG['plugin_fusinvdeploy']['config'][2]="Enable alerts on the size of MS Windows paths?";

$LANG['plugin_fusinvdeploy']['deploystatus'][0]="Deployment status";
$LANG['plugin_fusinvdeploy']['deploystatus'][1]="Associated logs";
$LANG['plugin_fusinvdeploy']['deploystatus'][2]="Агент принял запрос на задание";
$LANG['plugin_fusinvdeploy']['deploystatus'][3]="Агент начал проверку зеркала для закачки файла";
$LANG['plugin_fusinvdeploy']['deploystatus'][4]="Подготовка рабочей директории";
$LANG['plugin_fusinvdeploy']['deploystatus'][5]="Агент в процессе выполнения задания";

$LANG['plugin_fusinvdeploy']['files'][0]="Управление файлами";
$LANG['plugin_fusinvdeploy']['files'][1]="Имя файла";
$LANG['plugin_fusinvdeploy']['files'][2]="Версия";
$LANG['plugin_fusinvdeploy']['files'][3]="Операционная система";
$LANG['plugin_fusinvdeploy']['files'][4]="Файл для загрузки";
$LANG['plugin_fusinvdeploy']['files'][5]="Folder in package";
$LANG['plugin_fusinvdeploy']['files'][6]="Максимальный размер файла";
$LANG['plugin_fusinvdeploy']['files'][7]="Upload From";
$LANG['plugin_fusinvdeploy']['files'][8]="Этот компьютер";
$LANG['plugin_fusinvdeploy']['files'][9]="Сервер";

$LANG['plugin_fusinvdeploy']['ftitle'][0]="Edit check";
$LANG['plugin_fusinvdeploy']['ftitle'][13]="Delete file";
$LANG['plugin_fusinvdeploy']['ftitle'][14]="Delete command";
$LANG['plugin_fusinvdeploy']['ftitle'][15]="during installation";
$LANG['plugin_fusinvdeploy']['ftitle'][16]="during uninstallation";
$LANG['plugin_fusinvdeploy']['ftitle'][17]="before installation";
$LANG['plugin_fusinvdeploy']['ftitle'][18]="before uninstallation";
$LANG['plugin_fusinvdeploy']['ftitle'][1]="Add check";
$LANG['plugin_fusinvdeploy']['ftitle'][2]="List of checks";
$LANG['plugin_fusinvdeploy']['ftitle'][3]="Files to copy on computer";
$LANG['plugin_fusinvdeploy']['ftitle'][4]="Add file";
$LANG['plugin_fusinvdeploy']['ftitle'][5]="Edit file";
$LANG['plugin_fusinvdeploy']['ftitle'][6]="Add command";
$LANG['plugin_fusinvdeploy']['ftitle'][7]="Edit command";
$LANG['plugin_fusinvdeploy']['ftitle'][8]="Actions to achieve";
$LANG['plugin_fusinvdeploy']['ftitle'][9]="Delete a check";

$LANG['plugin_fusinvdeploy']['group'][0]="Groups of computers";
$LANG['plugin_fusinvdeploy']['group'][1]="Static group";
$LANG['plugin_fusinvdeploy']['group'][2]="Dynamic group";
$LANG['plugin_fusinvdeploy']['group'][3]="Group of computers";
$LANG['plugin_fusinvdeploy']['group'][4]="Add group";
$LANG['plugin_fusinvdeploy']['group'][5]="If no line in the list is selected, the text fields on the left will be used for search.";

$LANG['plugin_fusinvdeploy']['label'][0]="Type";
$LANG['plugin_fusinvdeploy']['label'][10]="Id";
$LANG['plugin_fusinvdeploy']['label'][11]="Command";
$LANG['plugin_fusinvdeploy']['label'][12]="Disk or directory";
$LANG['plugin_fusinvdeploy']['label'][13]="Key";
$LANG['plugin_fusinvdeploy']['label'][14]="Key value";
$LANG['plugin_fusinvdeploy']['label'][15]="File missing";
$LANG['plugin_fusinvdeploy']['label'][16]="From";
$LANG['plugin_fusinvdeploy']['label'][17]="To";
$LANG['plugin_fusinvdeploy']['label'][18]="Removal";
$LANG['plugin_fusinvdeploy']['label'][19]="Uncompress";
$LANG['plugin_fusinvdeploy']['label'][1]="Name";
$LANG['plugin_fusinvdeploy']['label'][20]="Transfer error: the file size is too big";
$LANG['plugin_fusinvdeploy']['label'][21]="Filesize";
$LANG['plugin_fusinvdeploy']['label'][22]="Failed to copy file";
$LANG['plugin_fusinvdeploy']['label'][23]="Extract the file after the download";
$LANG['plugin_fusinvdeploy']['label'][2]="Value";
$LANG['plugin_fusinvdeploy']['label'][3]="Unit";
$LANG['plugin_fusinvdeploy']['label'][4]="Active";
$LANG['plugin_fusinvdeploy']['label'][5]="File";
$LANG['plugin_fusinvdeploy']['label'][6]="P2P deployment";
$LANG['plugin_fusinvdeploy']['label'][7]="Date added";
$LANG['plugin_fusinvdeploy']['label'][8]="Validity time";
$LANG['plugin_fusinvdeploy']['label'][9]="Data retention duration (days)";

$LANG['plugin_fusinvdeploy']['massiveactions'][0]="Target a task";
$LANG['plugin_fusinvdeploy']['massiveactions'][1]="Create a job for each computer";
$LANG['plugin_fusinvdeploy']['massiveactions'][2]="Create a job for each group";

$LANG['plugin_fusinvdeploy']['message'][0]="Empty form";
$LANG['plugin_fusinvdeploy']['message'][1]="Invalid form";
$LANG['plugin_fusinvdeploy']['message'][2]="Loading...";
$LANG['plugin_fusinvdeploy']['message'][3]="File already exist";
$LANG['plugin_fusinvdeploy']['message'][4]="Paths on MS Windows do not accept more than 255 characters, the value you entered exceeds the limit.<br /><br /><b>Do you want to continue?</b><br /><br /><div class";
$LANG['plugin_fusinvdeploy']['message'][5]="Attention";
$LANG['plugin_fusinvdeploy']['message'][6]="Wish to the command to install and uninstall is automatically added for your file?";

$LANG['plugin_fusinvdeploy']['mirror'][1]="Mirror servers";
$LANG['plugin_fusinvdeploy']['mirror'][3]="Mirror server address";

$LANG['plugin_fusinvdeploy']['package'][0]="Действия";
$LANG['plugin_fusinvdeploy']['package'][10]="Module";
$LANG['plugin_fusinvdeploy']['package'][11]="Проверки";
$LANG['plugin_fusinvdeploy']['package'][12]="Файлы";
$LANG['plugin_fusinvdeploy']['package'][14]="Установка";
$LANG['plugin_fusinvdeploy']['package'][15]="Удаление";
$LANG['plugin_fusinvdeploy']['package'][16]="Package deployment";
$LANG['plugin_fusinvdeploy']['package'][17]="Package uninstall";
$LANG['plugin_fusinvdeploy']['package'][18]="Переместить файл";
$LANG['plugin_fusinvdeploy']['package'][19]="pieces of files";
$LANG['plugin_fusinvdeploy']['package'][1]="Выполнить команду";
$LANG['plugin_fusinvdeploy']['package'][20]="Удалить файл";
$LANG['plugin_fusinvdeploy']['package'][21]="Показать диалог";
$LANG['plugin_fusinvdeploy']['package'][22]="Коды возврата";
$LANG['plugin_fusinvdeploy']['package'][23]="One or more active tasks (#task#) use this package. Deletion denied.";
$LANG['plugin_fusinvdeploy']['package'][24]="One or more active tasks (#task#) use this package. Edition denied.";
$LANG['plugin_fusinvdeploy']['package'][25]="Новое имя";
$LANG['plugin_fusinvdeploy']['package'][26]="Добавить пакет";
$LANG['plugin_fusinvdeploy']['package'][27]="Создать директорию";
$LANG['plugin_fusinvdeploy']['package'][28]="Копировать файл";
$LANG['plugin_fusinvdeploy']['package'][2]="Launch (running file in package)";
$LANG['plugin_fusinvdeploy']['package'][3]="Exécuter (system executable)";
$LANG['plugin_fusinvdeploy']['package'][4]="Store";
$LANG['plugin_fusinvdeploy']['package'][5]="Packages";
$LANG['plugin_fusinvdeploy']['package'][6]="Package management";
$LANG['plugin_fusinvdeploy']['package'][7]="Package";
$LANG['plugin_fusinvdeploy']['package'][9]="Number of fragments";

$LANG['plugin_fusinvdeploy']['packagefiles'][0]="Файлы связанные с пакетом";

$LANG['plugin_fusinvdeploy']['profile'][2]="Manage packages";

$LANG['plugin_fusinvdeploy']['setup'][17]="Plugin FusionInventory DEPLOY needs FusionInventory plugin activated before activation.";
$LANG['plugin_fusinvdeploy']['setup'][18]="Plugin FusionInventory DEPLOY needs FusionInventory plugin activated before uninstall.";
$LANG['plugin_fusinvdeploy']['setup'][19]="Plugin FusionInventory DEPLOY needs Webservices plugin (1.2.0 minimum) installed before activation.";
$LANG['plugin_fusinvdeploy']['setup'][20]="Plugin FusionInventory DEPLOY needs Webservices plugin (1.2.0 minimum) installed before uninstall.";
$LANG['plugin_fusinvdeploy']['setup'][21]="Plugin FusionInventory DEPLOY needs FusionInventory INVENTORY plugin installed before activation.";
$LANG['plugin_fusinvdeploy']['setup'][22]="Plugin FusionInventory DEPLOY needs FusionInventory INVENTORY plugin installed before uninstall.";

$LANG['plugin_fusinvdeploy']['task'][0]="Deployment tasks";
$LANG['plugin_fusinvdeploy']['task'][11]="Edit task";
$LANG['plugin_fusinvdeploy']['task'][12]="Add a task";
$LANG['plugin_fusinvdeploy']['task'][13]="Order list";
$LANG['plugin_fusinvdeploy']['task'][14]="Advanced options";
$LANG['plugin_fusinvdeploy']['task'][15]="Add order";
$LANG['plugin_fusinvdeploy']['task'][16]="Delete order";
$LANG['plugin_fusinvdeploy']['task'][17]="Edit order";
$LANG['plugin_fusinvdeploy']['task'][18]="---";
$LANG['plugin_fusinvdeploy']['task'][19]="Edit impossible, this task is active";
$LANG['plugin_fusinvdeploy']['task'][1]="Task";
$LANG['plugin_fusinvdeploy']['task'][20]="This task is active. delete denied";
$LANG['plugin_fusinvdeploy']['task'][3]="Add task";
$LANG['plugin_fusinvdeploy']['task'][8]="Actions list";

$LANG['plugin_fusinvdeploy']['title'][0]="FusionInventory DEPLOY";

?>