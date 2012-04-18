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

commonHeader($LANG['plugin_fusioninventory']['functionalities'][0],$_SERVER["PHP_SELF"],"plugins","fusioninventory","summary");

if (isset($_POST['update'])) {

   if (empty($_POST['cleaning_days'])) {
      $_POST['cleaning_days'] = 0;
   }

   $_POST['id']=1;
   switch ($_POST['tabs']) {
      
      case 'config' :
         $PluginFusioninventoryConfig = new PluginFusioninventoryConfig();
         $PluginFusioninventoryConfig->update($_POST);
         break;

      case 'history' :
         $PluginFusinvsnmpConfigLogField = new PluginFusinvsnmpConfigLogField();
         foreach ($_POST as $key=>$val) {
            $split = explode("-", $key);
            if (isset($split[1]) AND is_numeric($split[1])) {
               $PluginFusinvsnmpConfigLogField->getFromDB($split[1]);
               $input = array();
               $input['id'] = $PluginFusinvsnmpConfigLogField->fields['id'];
               $input['days'] = $val;
               $PluginFusinvsnmpConfigLogField->update($input);
            }
         }
         break;

   }
   glpi_header($_SERVER['HTTP_REFERER']);
} else if ((isset($_POST['Clean_history']))) {
   $PluginFusinvsnmpNetworkPortLog = new PluginFusinvsnmpNetworkPortLog();
   $PluginFusinvsnmpNetworkPortLog->cronCleannetworkportlogs();
   glpi_header($_SERVER['HTTP_REFERER']);
}

commonFooter();

?>