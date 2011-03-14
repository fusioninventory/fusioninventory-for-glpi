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
   Original Author of file: Vincent Mazzoni
   Co-authors of file: David DURIEUX
   Purpose of file:
   ----------------------------------------------------------------------
 */

define('GLPI_ROOT', '../../..');

include (GLPI_ROOT."/inc/includes.php");

checkRight("config","w");

commonHeader($LANG['plugin_fusioninventory']['functionalities'][0],$_SERVER["PHP_SELF"],"plugins","fusioninventory","configuration");

if (isset($_POST['plugin_fusioninventory_config_set'])) {
   $config = new PluginFusioninventoryConfig();
   $plugins_id = PluginFusioninventoryModule::getModuleId('fusioninventory');
   $config->updateConfigType($plugins_id, 'ssl_only', $_POST['ssl_only']);
   $config->updateConfigType($plugins_id, 'inventory_frequence', $_POST['inventory_frequence']);
   $config->updateConfigType($plugins_id, 'delete_task', $_POST['delete_task']);
   $config->updateConfigType($plugins_id, 'agent_port', $_POST['agent_port']);
   $config->updateConfigType($plugins_id, 'extradebug', $_POST['extradebug']);
   glpi_header($_SERVER['HTTP_REFERER']);
}


// modules
if (isset($_SESSION['glpi_plugin_fusioninventory']['configuration'])) {
   foreach($_SESSION['glpi_plugin_fusioninventory']['configuration']['moduletabforms'] as $module=>$form) {
      foreach($form as $title=>$code) {
         if (isset($_POST[$code['submitbutton']])) {
            $obj = new $code['class'];
            $obj->$code['submitmethod']($_POST);
            glpi_header($_SERVER['HTTP_REFERER']);
         }
      }
   }
}

$configuration = new PluginFusioninventoryConfiguration();
if (isset($_GET['glpi_tab'])) {
   $_SESSION['glpi_tabs']['pluginfusioninventoryconfiguration'] = $_GET['glpi_tab'];
}
$configuration->showForm();
unset($_SESSION['glpi_tabs']['pluginfusioninventoryconfiguration']);

commonFooter();

?>