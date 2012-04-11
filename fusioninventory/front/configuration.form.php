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
   @author    Vincent Mazzoni
   @co-author David Durieux
   @copyright Copyright (c) 2010-2012 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010
 
   ------------------------------------------------------------------------
 */

define('GLPI_ROOT', '../../..');

include (GLPI_ROOT."/inc/includes.php");

checkRight("config","w");

commonHeader($LANG['plugin_fusioninventory']['functionalities'][0], $_SERVER["PHP_SELF"], 
             "plugins", "fusioninventory", "configuration");

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
   foreach($_SESSION['glpi_plugin_fusioninventory']['configuration']['moduletabforms'] as $form) {
      foreach($form as $code) {
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
   glpi_header(getItemTypeFormURL($configuration->getType()));
}
$configuration->showForm();
unset($_SESSION['glpi_tabs']['pluginfusioninventoryconfiguration']);

commonFooter();

?>