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
// Original Author of file: DURIEUX David
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusinvdeployFile extends CommonDBTM {
   
   private $split_size = 1000000;
   
   static function getTypeName() {
      global $LANG;

      return $LANG['plugin_fusinvdeploy']['package'][12];
   }

   function canCreate() {
      return true;
   }

   function canView() {
      return true;
   }

   function getEmpty() {
      $this->fields['retention'] = 0;
   }
   
   function prepareInputForAdd($input) {
      if (isset($result['p2p-retention-duration'])) {
         $tmp['p2p-retention-duration'] = 0;
      }
      return $input;
   }
   
   static function cleanForPackage($orders_id) {
      global $DB;
      $query = "DELETE FROM `glpi_plugin_fusinvdeploy_files` 
                WHERE `plugin_fusinvdeploy_orders_id`='$orders_id'";
      $DB->query($query);
   }
   
   static function getForOrder($orders_id) {
      $results = getAllDatasFromTable('glpi_plugin_fusinvdeploy_files',
                                      "`plugin_fusinvdeploy_orders_id`='$orders_id'");
      
      $files = array();
      foreach ($results as $result) {
         $tmp['uncompress']                = $result['uncompress'];
         $tmp['name']                      = $result['name'];
         $tmp['is_p2p']                    = $result['is_p2p'];
         $mirrors = PluginFusinvdeployFile_Mirror::getForFile($result['id']);
         if (!empty($mirrors)) {
            $tmp['mirrors'] = $mirrors;
         }
         
         $fileparts = PluginFusinvdeployFilepart::getForFile($result['id']);
         if (!empty($fileparts)) {
            $tmp['multiparts'] = $fileparts;
         }
         if (isset($result['p2p-retention-duration'])) {
            $tmp['p2p-retention-duration'] = $result['p2p-retention-duration'];
         } else {
            $tmp['p2p-retention-duration'] = 0;
         }
         $files[$result['sha512']]         = $tmp;
      }
      
      return $files;
   }
   
   function getNumberOfPartsFromFilesize($size){
      
     return ceil($size / $this->split_size);
   
   }
   
   
   function getExtension($file){
      $extension        = explode(".", $file);
      $extension        = $extension[count($extension) - 1];
   
      return $extension;
   }
   
   /**
    * Split a file into fragments
    * @param file_name name of the file to split
    * @param part_num number of fragments to do
    * @param orders_id indicates the order for which the file is uploaded
    * @param files_id  the ID of the file updated
    * @return OK
    */
   function splitFile($file_name, $parts_num, $order_id, $file_id) {
      global $DB;
      
      $PluginFusinvdeployFilepart = new PluginFusinvdeployFilepart;
      
      $handle = fopen($file_name, 'rb') or die("error opening file");
      $file_size = filesize($file_name);
      $parts_size = $this->split_size;
      $modulus=$file_size % $parts_num;
      for($i=0;$i<$parts_num;$i++) {
         if($modulus!=0 & $i==$parts_num-1) {
            $parts[$i] = fread($handle,$parts_size+$modulus) or die("error reading file");
         }
         else {
            $parts[$i] = fread($handle,$parts_size) or die("error reading file");
         }
      }

      fclose($handle) or die("error closing file handle");
      for($i=0;$i<$parts_num;$i++) {
         $filename = "splited_".time()."_order_{$order_id}_file_{$file_id}_{$i}.gz";
         $part_handle = fopen(GLPI_PLUGIN_DOC_DIR."/fusinvdeploy/files/{$filename}", 'wb') 
            or die("error opening file for writing");
         fwrite($part_handle,gzencode($parts[$i])) or die("error writing splited file");
            
         $sum       = hash_file('sha512', GLPI_PLUGIN_DOC_DIR."/fusinvdeploy/files/".$filename);
         $part_data = array('name'                          => $filename,
                            'sha512'                        => $sum,
                            'order'                         => $i, 
                            'plugin_fusinvdeploy_orders_id' => $order_id, 
                            'plugin_fusinvdeploy_files_id'  => $file_id);  
         
         $PluginFusinvdeployFilepart->add($part_data);
      }
      return 'OK';
   }

}
?>