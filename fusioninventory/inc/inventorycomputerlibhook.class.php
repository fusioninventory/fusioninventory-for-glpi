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

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusioninventoryInventoryComputerLibhook {


    function __construct() {
       if (!isset($_SESSION["plugin_fusinvinventory_history_add"])) {
         $_SESSION["plugin_fusinvinventory_history_add"] = true;
       }
       if (!isset($_SESSION["plugin_fusinvinventory_no_history_add"])) {
         $_SESSION["plugin_fusinvinventory_no_history_add"] = false;
       }
       $_SESSION["plugin_fusinvinventory_userdefined"] = 0;
    }



   /**
   * Write XML file into files/_plugins/fusinvinventory
   *
   * @param $items_id integer id of the computer
   *
   * @return nothing
   *
   **/
    function writeXMLFusion($items_id,$xml='') {
      if ($xml != '') {

         $folder = substr($items_id,0,-1);
         if (empty($folder)) {
            $folder = '0';
         }
         if (!file_exists(GLPI_PLUGIN_DOC_DIR."/fusioninventory/xml/computer/".$folder)) {
            mkdir(GLPI_PLUGIN_DOC_DIR."/fusioninventory/xml/computer/".$folder);
         }
         $fileopen = fopen(GLPI_PLUGIN_DOC_DIR."/fusioninventory/xml/computer/".$folder."/".$items_id, 'w');
         fwrite($fileopen, $xml);
         fclose($fileopen);
       }
    }



   /**
   * Define Mapping for unlock fields
   *
   * @return array of the mapping
   *
   **/
    static function getMapping() {
       $opt = array();

       $i = 0;

       // ** HARDWARE
       $opt[$i]['xmlSection']       = 'HARDWARE';
       $opt[$i]['xmlSectionChild']  = 'NAME';
       $opt[$i]['glpiItemtype']     = 'Computer';
       $opt[$i]['glpiField']        = 'name';

       $i++;
       $opt[$i]['xmlSection']       = 'HARDWARE';
       $opt[$i]['xmlSectionChild']  = 'OSNAME';
       $opt[$i]['glpiItemtype']     = 'Computer';
       $opt[$i]['glpiField']        = 'operatingsystems_id';

       $i++;
       $opt[$i]['xmlSection']       = 'HARDWARE';
       $opt[$i]['xmlSectionChild']  = 'OSVERSION';
       $opt[$i]['glpiItemtype']     = 'Computer';
       $opt[$i]['glpiField']        = 'operatingsystemversions_id';

       $i++;
       $opt[$i]['xmlSection']       = 'HARDWARE';
       $opt[$i]['xmlSectionChild']  = 'WINPRODID';
       $opt[$i]['glpiItemtype']     = 'Computer';
       $opt[$i]['glpiField']        = 'os_licenseid';

       $i++;
       $opt[$i]['xmlSection']       = 'HARDWARE';
       $opt[$i]['xmlSectionChild']  = 'WINPRODKEY';
       $opt[$i]['glpiItemtype']     = 'Computer';
       $opt[$i]['glpiField']        = 'os_license_number';

       $i++;
       $opt[$i]['xmlSection']       = 'HARDWARE';
       $opt[$i]['xmlSectionChild']  = 'WORKGROUP';
       $opt[$i]['glpiItemtype']     = 'Computer';
       $opt[$i]['glpiField']        = 'domains_id';

       $i++;
       $opt[$i]['xmlSection']       = 'HARDWARE';
       $opt[$i]['xmlSectionChild']  = 'OSCOMMENTS';
       $opt[$i]['glpiItemtype']     = 'Computer';
       $opt[$i]['glpiField']        = 'operatingsystemservicepacks_id';

       $i++;
       $opt[$i]['xmlSection']       = 'HARDWARE';
       $opt[$i]['xmlSectionChild']  = 'UUID';
       $opt[$i]['glpiItemtype']     = 'Computer';
       $opt[$i]['glpiField']        = 'uuid';

       $i++;
       $opt[$i]['xmlSection']       = 'HARDWARE';
       $opt[$i]['xmlSectionChild']  = 'DESCRIPTION';
       $opt[$i]['glpiItemtype']     = 'Computer';
       $opt[$i]['glpiField']        = 'comment';


       // ** USERS
       $i++;
       $opt[$i]['xmlSection']       = 'USERS';
       $opt[$i]['xmlSectionChild']  = 'LOGIN';
       $opt[$i]['glpiItemtype']     = 'Computer';
       $opt[$i]['glpiField']        = 'users_id';

       $i++;
       $opt[$i]['xmlSection']       = 'USERS';
       $opt[$i]['xmlSectionChild']  = 'LOGIN';
       $opt[$i]['glpiItemtype']     = 'Computer';
       $opt[$i]['glpiField']        = 'contact';


       // ** BIOS
       $i++;
       $opt[$i]['xmlSection']       = 'BIOS';
       $opt[$i]['xmlSectionChild']  = 'SMANUFACTURER';
       $opt[$i]['glpiItemtype']     = 'Computer';
       $opt[$i]['glpiField']        = 'manufacturers_id';

       $i++;
       $opt[$i]['xmlSection']       = 'BIOS';
       $opt[$i]['xmlSectionChild']  = 'SMODEL';
       $opt[$i]['glpiItemtype']     = 'Computer';
       $opt[$i]['glpiField']        = 'computermodels_id';

       $i++;
       $opt[$i]['xmlSection']       = 'BIOS';
       $opt[$i]['xmlSectionChild']  = 'SSN';
       $opt[$i]['glpiItemtype']     = 'Computer';
       $opt[$i]['glpiField']        = 'serial';

       $i++;
       $opt[$i]['xmlSection']       = 'BIOS';
       $opt[$i]['xmlSectionChild']  = 'TYPE';
       $opt[$i]['glpiItemtype']     = 'Computer';
       $opt[$i]['glpiField']        = 'computertypes_id';

       return $opt;
    }



    /**
    * Update model for HP for suppliertag plugin
    *
    * @param $items_id integer id of the computer
    * @param $partnumber value HP partnumber
    *
    * @return nothing
    *
    **/
    function Suppliertag($items_id, $partnumber) {
      if ($partnumber != 'Not Specified') {
         $a_partnumber = explode("#", $partnumber);
         $Plugin = new Plugin();
         if ($Plugin->isActivated('manufacturersimports')) {
            if (class_exists("PluginManufacturersimportsModel")) {
               $PluginManufacturersimportsModel = new PluginManufacturersimportsModel();
               $PluginManufacturersimportsModel->addModel($items_id, 'Computer', $a_partnumber[0]);
            }
         }
      }
    }



   static function importGroup($value, $entities_id) {
      global $DB;

      if (empty ($value)) {
         return 0;
      }

      $query2 = "SELECT `id`
                 FROM `glpi_groups`
                 WHERE `name` = '$value'
                       AND `entities_id` = '$entities_id'";
      $result2 = $DB->query($query2);

      if ($DB->numrows($result2) == 0) {
         $group                = new Group();
         $input = array();
         $input["name"]        = $value;
         $input["entities_id"] = $entities_id;
         return $group->add($input);
      }
      $line2 = $DB->fetch_array($result2);
      return $line2["id"];
   }
}

?>
