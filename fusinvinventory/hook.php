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

function plugin_fusinvinventory_getAddSearchOptions($itemtype) {
   global $LANG;

   $sopt = array();
   if ($itemtype == 'Computer') {

         $sopt[5150]['table']     = 'glpi_plugin_fusinvinventory_libserialization';
         $sopt[5150]['field']     = 'last_fusioninventory_update';
         $sopt[5150]['linkfield'] = '';
         $sopt[5150]['name']      = $LANG['plugin_fusioninventory']['title'][1]." - ".
            $LANG['plugin_fusinvinventory']['computer'][0];
         $sopt[5150]['datatype']  = 'datetime';
         $sopt[5150]['itemlink_type'] = 'PluginFusinvinventoryLib';

         $sopt[5151]['table']     = 'glpi_plugin_fusinvinventory_antivirus';
         $sopt[5151]['field']     = 'name';
         $sopt[5151]['linkfield'] = '';
         $sopt[5151]['name']      = 'Antivirus name';
         $sopt[5151]['datatype']  = 'text';

         $sopt[5152]['table']     = 'glpi_plugin_fusinvinventory_antivirus';
         $sopt[5152]['field']     = 'version';
         $sopt[5152]['linkfield'] = '';
         $sopt[5152]['name']      = 'Antivirus version';
         $sopt[5152]['datatype']  = 'text';

         $sopt[5153]['table']     = 'glpi_plugin_fusinvinventory_antivirus';
         $sopt[5153]['field']     = 'is_active';
         $sopt[5153]['linkfield'] = '';
         $sopt[5153]['name']      = 'Antivirus activé';
         $sopt[5153]['datatype']  = 'bool';

         $sopt[5154]['table']     = 'glpi_plugin_fusinvinventory_antivirus';
         $sopt[5154]['field']     = 'uptodate';
         $sopt[5154]['linkfield'] = '';
         $sopt[5154]['name']      = 'Antivirus à jour';
         $sopt[5154]['datatype']  = 'bool';
         
         $sopt[5155]['table']     = 'glpi_plugin_fusinvinventory_computers';
         $sopt[5155]['field']     = 'bios_date';
         $sopt[5155]['linkfield'] = '';
         $sopt[5155]['name']      = $LANG['plugin_fusinvinventory']['bios'][0]."-".$LANG['common'][27];
         $sopt[5155]['datatype']  = 'date';

         $sopt[5160]['table']     = 'glpi_plugin_fusinvinventory_computers';
         $sopt[5160]['field']     = 'bios_assettag';
         $sopt[5160]['linkfield'] = '';
         $sopt[5160]['name']      = $LANG['plugin_fusinvinventory']['bios'][0]."-Asset TAG";
         $sopt[5160]['datatype']  = 'text';

         $sopt[5156]['table']     = 'glpi_plugin_fusinvinventory_computers';
         $sopt[5156]['field']     = 'bios_version';
         $sopt[5156]['linkfield'] = '';
         $sopt[5156]['name']      = $LANG['plugin_fusinvinventory']['bios'][0]."-".$LANG['rulesengine'][78];
         
         $sopt[5157]['table']     = 'glpi_plugin_fusinvinventory_computers';
         $sopt[5157]['field']     = 'operatingsystem_installationdate';
         $sopt[5157]['linkfield'] = '';
         $sopt[5157]['name']      = $LANG['computers'][9]." - ".$LANG['install'][3]." (".strtolower($LANG['common'][27]).")";
         $sopt[5157]['datatype']  = 'date';

         $sopt[5158]['table']     = 'glpi_plugin_fusinvinventory_computers';
         $sopt[5158]['field']     = 'winowner';
         $sopt[5158]['linkfield'] = '';
         $sopt[5158]['name']      = $LANG['plugin_fusinvinventory']['computer'][1];

         $sopt[5159]['table']     = 'glpi_plugin_fusinvinventory_computers';
         $sopt[5159]['field']     = 'wincompany';
         $sopt[5159]['linkfield'] = '';
         $sopt[5159]['name']      = $LANG['plugin_fusinvinventory']['computer'][2];
         
   }
   return $sopt;
}



function plugin_fusinvinventory_install() {

   $a_plugin = plugin_version_fusinvinventory();

   include (GLPI_ROOT . "/plugins/fusinvinventory/install/update.php");
   $version_detected = pluginfusinvinventoryGetCurrentVersion($a_plugin['version']);
   if ((isset($version_detected))
           AND ($version_detected != $a_plugin['version'])
           AND $version_detected!='0') {

      // Update
      pluginFusinvinventoryUpdate($version_detected);
   } else if ((isset($version_detected)) AND ($version_detected == $a_plugin['version'])) {
      return true;
   } else {
      include (GLPI_ROOT . "/plugins/fusinvinventory/install/install.php");
      pluginFusinvinventoryInstall(PLUGIN_FUSINVINVENTORY_VERSION);
   }

   return true;
}



// Uninstall process for plugin : need to return true if succeeded
function plugin_fusinvinventory_uninstall() {
   include (GLPI_ROOT . "/plugins/fusinvinventory/install/install.php");
   pluginFusinvinventoryUninstall();
}



/**
* Check if Fusinvinventory need to be updated
*
* @param
*
* @return 0 (no need update) OR 1 (need update)
**/
function plugin_fusinvinventory_needUpdate() {
   include (GLPI_ROOT . "/plugins/fusinvinventory/install/update.php");
   $version_detected = pluginFusinvinventoryGetCurrentVersion(PLUGIN_FUSINVINVENTORY_VERSION);
   if ((isset($version_detected)) 
      AND ($version_detected != PLUGIN_FUSINVINVENTORY_VERSION)
      AND $version_detected!='0') {
      return 1;
   } else {
      return 0;
   }
}



function plugin_fusinvinventory_addLeftJoin($itemtype, $ref_table, $new_table, $linkfield, 
                                            &$already_link_tables) {
   
   if ($itemtype == 'Computer') {
      return " LEFT JOIN `$new_table` ON (`$ref_table`.`id` = `$new_table`.`computers_id`) ";
   }

   return "";
}

function plugin_pre_item_purge_fusinvinventory($item) {
   
   switch (get_class($item)) {

      case 'Computer' :
         $pfLib = new PluginFusinvinventoryLib();
         $pfLib->removeExternalid($item->getField('id'));
         // Remove antivirus if set
         PluginFusinvinventoryAntivirus::cleanComputer($item->getField('id'));
         break;

   }
}



function plugin_fusinvinventory_registerMethods() {
   global $WEBSERVICES_METHOD;
   
   $WEBSERVICES_METHOD['fusioninventory.test'] = array('PluginFusinvinventoryWebservice', 
                                                       'methodTest');
}

?>
