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

class PluginFusinvinventoryImport_Software extends CommonDBTM  {


   /**
   * Add software
   *
   * @param $idmachine integer id of the computer
   * @param $array array all values of the section
   *
   * @return id of the software or false
   *
   **/
   function addSoftware($idmachine, $array) {
      global $DB;

      $pfConfig = new PluginFusioninventoryConfig();
      if ($pfConfig->getValue($_SESSION["plugin_fusinvinventory_moduleid"],
              "import_software") == '0') {
         return;
      }

      $manufacturer = NULL;
      if (isset($array['PUBLISHER'])) {
         $manufacturer = Manufacturer::processName($array['PUBLISHER']);
      }

      $rulecollection = new RuleDictionnarySoftwareCollection();
      $Software = new Software();

      $res_rule = $rulecollection->processAllRules(array("name"=>$array['name'],
                                                         "manufacturer"=>$manufacturer,
                                                         "old_version"=>$array['version'],
                                                         "entities_id"=>$_SESSION["plugin_fusinvinventory_entity"]));

      if (isset($res_rule['_ignore_ocs_import']) AND $res_rule['_ignore_ocs_import'] == "1") {
         // Ignrore import software
         return;
      }
      $modified_name = "";
      if (isset($res_rule["name"])) {
         $modified_name = $res_rule["name"];
      } else {
         $modified_name = $array['name'];
      }
      $modified_version = "";
      if (isset($res_rule["version"]) && $res_rule["version"]!= '') {
         $modified_version = $res_rule["version"];
      } else {
         $modified_version = $array['version'];
      }
      if (isset($res_rule["manufacturer"])  && $res_rule["manufacturer"]) {
         $manufacturer = Dropdown::getDropdownName("glpi_manufacturers", $res_rule["manufacturer"]);
      }
      $software_entity = $_SESSION["plugin_fusinvinventory_entity"];
      if (isset($res_rule['new_entities_id'])) {
         $software_entity = $res_rule['new_entities_id'];
      }      
      
      $software_id = $Software->addOrRestoreFromTrash($modified_name, 
                                                      $manufacturer, 
                                                      $software_entity);
      if ($software_id > 0
              AND isset($res_rule["is_helpdesk_visible"])
              AND strlen($res_rule["is_helpdesk_visible"])) {
         $inputsoftware = array();
         $inputsoftware['id'] = $software_id;
         $inputsoftware['is_helpdesk_visible'] = $res_rule["is_helpdesk_visible"];
         $Software->update($inputsoftware);
      }      
      
      $isNewVers = 0;
      $query = "SELECT `id`
                FROM `glpi_softwareversions`
                WHERE `softwares_id` = '$software_id'
                   AND `name` = '$modified_version' ";
      $result = $DB->query($query);
      if ($DB->numrows($result) > 0) {
         $data = $DB->fetch_array($result);
         $isNewVers = $data["id"];
      } else {
         $SoftwareVersion = new SoftwareVersion();
         $input = array();
         $input["softwares_id"] = $software_id;
         $input["name"] = $modified_version;
         if ($_SESSION["plugin_fusinvinventory_no_history_add"]) {
            $input['_no_history'] = $_SESSION["plugin_fusinvinventory_no_history_add"];
         }
         $isNewVers = $SoftwareVersion->add($input, array(), $_SESSION["plugin_fusinvinventory_history_add"]);
      }

      $Computer_SoftwareVersion = new Computer_SoftwareVersion;
      $array = array();
      $array['computers_id'] = $idmachine;
      $array['softwareversions_id'] = $isNewVers;
      if ($_SESSION["plugin_fusinvinventory_no_history_add"]) {
         $array['_no_history'] = $_SESSION["plugin_fusinvinventory_no_history_add"];
      }
      // Check if this software yet exist (See ticket http://forge.fusioninventory.org/issues/999)
      $a_soft = $Computer_SoftwareVersion->find("`computers_id`='".$array['computers_id']."'
               AND `softwareversions_id`='".$array['softwareversions_id']."' ", 
            "",
            1);
      if (count($a_soft) == 0) {      
         $Computer_SoftwareVersion_id = $Computer_SoftwareVersion->add($array, array(), $_SESSION["plugin_fusinvinventory_history_add"]);
         return $Computer_SoftwareVersion_id;
      }
   }



   /**
   * Delete software
   *
   * @param $items_id integer id of the software
   * @param $idmachine integer id of the computer
   *
   * @return nothing
   *
   **/
   function deleteItem($items_id, $idmachine) {
      $Computer_SoftwareVersion = new Computer_SoftwareVersion();
      $Computer_SoftwareVersion->getFromDB($items_id);
      if ($Computer_SoftwareVersion->fields['computers_id'] == $idmachine) {
         $input = array();
         $input['id'] = $items_id;
         if ($_SESSION["plugin_fusinvinventory_no_history_add"]) {
            $input['_no_history'] = $_SESSION["plugin_fusinvinventory_no_history_add"];
         }
         $Computer_SoftwareVersion->delete($input, 0, $_SESSION["plugin_fusinvinventory_history_add"]);
      }
   }   
}

?>