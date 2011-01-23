<?php
/*
 * @version $Id$
 ----------------------------------------------------------------------
 FusionInventory
 Copynetwork (C) 2003-2010 by the INDEPNET Development Team.

 http://www.fusioninventory.org/   http://forge.fusioninventory.org//
 ----------------------------------------------------------------------

 LICENSE

 This file is part of FusionInventory plugins.

 FusionInventory is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 FusionInventory is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with FusionInventory; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 ------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: Vincent MAZZONI
// Purpose of file:
// ----------------------------------------------------------------------

define('GLPI_ROOT', '../../..');

include (GLPI_ROOT."/inc/includes.php");

checkRight("config","w");

commonHeader($LANG['plugin_fusioninventory']['functionalities'][0],$_SERVER["PHP_SELF"],"plugins","fusioninventory","configuration");

if (isset($_POST['plugin_fusioninventory_config_set'])) {
   $config = new PluginFusioninventoryConfig;
   $plugins_id = PluginFusioninventoryModule::getModuleId('fusioninventory');
   $config->updateConfigType($plugins_id, 'ssl_only', $_POST['ssl_only']);
   $config->updateConfigType($plugins_id, 'inventory_frequence', $_POST['inventory_frequence']);
   $config->updateConfigType($plugins_id, 'delete_task', $_POST['delete_task']);
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

$configuration = new PluginFusioninventoryConfiguration;
$configuration->show();

commonFooter();

?>
