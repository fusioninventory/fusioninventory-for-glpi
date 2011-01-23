<?php

/*
   ----------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2011 by the FusionInventory Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org/
   ----------------------------------------------------------------------

   LICENSE

   This file is part of FusionInventory.

   FusionInventory is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 2 of the License, or
   any later version.

   FusionInventory is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with FusionInventory.  If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------
   Original Author of file: Vincent MAZZONI
   Co-authors of file:
   Purpose of file:
   ----------------------------------------------------------------------
 */

define('GLPI_ROOT', '../../..');
include (GLPI_ROOT . "/inc/includes.php");
header("Content-Type: text/html; charset=UTF-8");
header_nocache();

if(!isset($_POST["id"])) {
   exit();
}
if(!isset($_POST["sort"])) $_POST["sort"] = "";
if(!isset($_POST["order"])) $_POST["order"] = "";
if(!isset($_POST["withtemplate"])) $_POST["withtemplate"] = "";




checkRight("config","w");

if (PluginFusioninventoryProfile::haveRight("fusioninventory", "configuration", "r")) {
   switch($_POST['glpi_tab']) {
      case -1 :
         $config = new PluginFusioninventoryConfig;
         $config->showForm('1', array('target'=>$_POST['target']));

         break;

//      case 2 :
//         $config_modules = new PluginFusioninventoryConfigModules;
//         $config_modules->showForm('1', array('target'=>$_POST['target']));
//         break;

//      case 7 :
//         // Historique
//         $configLogField = new PluginFusioninventoryConfigLogField();
//         $configLogField->showForm(array('target'=>$_POST['target']));
//         break;

      case 8 :
         // lockables
         $ptLockable = new PluginFusioninventoryLockable;
         $ptLockable->showForm(array('target'=>$_POST['target']));
         break;

      default :
         $computer = new Computer;
         $computer->getFromDB($_POST["id"]);
         if (!Plugin::displayAction($computer,$_POST['glpi_tab'],$_POST["withtemplate"])) {
            $config = new PluginFusioninventoryConfig;
            $config->showForm('1', array('target'=>$_POST['target']));
         }
         break;
   }
} else {
   echo $LANG['common'][83]."<br/>";
}
ajaxFooter();

?>
