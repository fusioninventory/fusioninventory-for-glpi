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

Session::checkRight("config","w");

Html::header($LANG['plugin_fusioninventory']['functionalities'][0], $_SERVER["PHP_SELF"], 
             "plugins", "fusioninventory", "configuration");

if (isset($_POST['update'])) {
   $config = new PluginFusioninventoryConfig();
   $plugins_id = PluginFusioninventoryModule::getModuleId('fusioninventory');
   $config->updateValue($plugins_id, 'ssl_only', $_POST['ssl_only']);
   $config->updateValue($plugins_id, 'inventory_frequence', $_POST['inventory_frequence']);
   $config->updateValue($plugins_id, 'delete_task', $_POST['delete_task']);
   $config->updateValue($plugins_id, 'agent_port', $_POST['agent_port']);
   $config->updateValue($plugins_id, 'extradebug', $_POST['extradebug']);
   Html::back();
}

$pfConfig = new PluginFusioninventoryConfig();
$a_config = current($pfConfig->find("", "", 1));
$pfConfig->getFromDB($a_config['id']);
if (isset($_GET['glpi_tab'])) {
   $_SESSION['glpi_tabs']['pluginfusioninventoryconfiguration'] = $_GET['glpi_tab'];
   Html::redirect(Toolbox::getItemTypeFormURL($pfConfig->getType()));
}
$pfConfig->showTabs(array());
$pfConfig->addDivForTabs();
unset($_SESSION['glpi_tabs']['pluginfusioninventoryconfiguration']);

Html::footer();

?>