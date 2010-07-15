<?php
/*
 * @version $Id$
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copynetwork (C) 2003-2010 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org/
 ----------------------------------------------------------------------

 LICENSE

 This file is part of GLPI.

 GLPI is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 GLPI is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI; if not, write to the Free Software
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

      $np = new NetworkPort;

      if (file_exists(GLPI_PLUGIN_DOC_DIR.'/fusioninventory')) {
         if($dir = @opendir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory')) {
            $current_dir = GLPI_PLUGIN_DOC_DIR.'/fusioninventory/';
            while (($f = readdir($dir)) !== false) {
               if($f > '0' and filetype($current_dir.$f) == "file") {
                  unlink($current_dir.$f);
               } else if ($f > '0' and filetype($current_dir.$f) == "dir") {
                  Plugin_Fusioninventory_delTree($current_dir.$f);
               }
            }
            closedir($dir);
            rmdir($current_dir);
         }
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
              WHERE `itemtype`='PluginFusioninventoryError'
                    OR `itemtype`='PluginFusioninventoryUnknownDevice'
                    OR `itemtype`='PluginFusioninventoryAgent'
                    OR `itemtype`='PluginFusioninventoryIPRange'
                    OR `itemtype`='PluginFusioninventoryConfig'
                    OR `itemtype`='PluginFusioninventoryTask'
                    OR `itemtype`='PluginFusioninventoryConstructDevices' ;";
      $DB->query($query) or die($DB->error());


      $a_netports = $np->find("`itemtype`='PluginFusioninventoryUnknownDevice' ");
      foreach ($a_netports as $NetworkPort){
         $np->cleanDBonPurge($NetworkPort['id']);
         $np->deleteFromDB($NetworkPort['id']);
      }

      return true;
   }

}

?>
