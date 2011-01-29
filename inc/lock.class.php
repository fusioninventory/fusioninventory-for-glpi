<?php

/*
 * @version $Id$
 ----------------------------------------------------------------------
 FusionInventory
 Coded by the FusionInventory Development Team.

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

/// Plugin FusionInventory lock class
class PluginFusinvinventoryLock {

   static function deleteLock($item) {
      global $DB;

      $PluginFusinvinventoryLib = new PluginFusinvinventoryLib();

      // Get mapping
      $a_mapping = PluginFusinvinventoryLibhook::getMapping();
      $a_fieldList = importArrayFromDB($item->fields['tablefields']);

      for ($i=0; $i < count($a_fieldList); $i++) {
         foreach ($a_mapping as $datas) {
            if (($item->fields['tablename'] == getTableForItemType($datas['glpiItemtype']))
                  AND ($a_fieldList[$i] == $datas['glpiField'])) {

               // Get serialization
               $query = "SELECT * FROM `glpi_plugin_fusinvinventory_libserialization`
                  WHERE `external_id`='".$item->fields['items_id']."'
                     LIMIT 1";
               if ($result = $DB->query($query)) {
                  if ($DB->numrows($result) == '1') {
                     $a_serialized = $DB->fetch_assoc($result);
                     $infoSections = $PluginFusinvinventoryLib->_getInfoSections($a_serialized['internal_id']);

                     // Modify fields
                     $itemtype = $datas['glpiItemtype'];
                     $class = new $itemtype();
                     $class->getFromDB($item->fields['items_id']);
                     $libunserialized = unserialize($infoSections["sections"][$datas['xmlSection']."/".$item->fields['items_id']]);
                     $class->fields[$datas['glpiField']] = $libunserialized[$datas['xmlSectionChild']];
                     $class->update($class->fields);

                  }
               }               
            }
         }
      }
   }

}

?>