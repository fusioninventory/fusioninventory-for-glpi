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
// Original Author of file: MAZZONI Vincent
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusioninventorySetup {

   // Uninstallation function
   static function uninstall() {
      global $DB;

      CronTask::Unregister('fusioninventory');

      if (file_exists(GLPI_PLUGIN_DOC_DIR.'/fusioninventory')) {
         deleteDir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory');
      }

      $query = "SHOW TABLES;";
      $result=$DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         if (strstr($data[0],"glpi_plugin_fusioninventory_")){
            $query_delete = "DROP TABLE `".$data[0]."`;";
            $DB->query($query_delete) or die($DB->error());
         }
      }

      $query="DELETE FROM `glpi_displaypreferences`
              WHERE `itemtype` LIKE 'PluginFusioninventory%';";
      $DB->query($query) or die($DB->error());

      return true;
   }

}

?>