<?php
/*
 * @version $Id$
 -------------------------------------------------------------------------
 FusionInventory
 Copyright (C) 2003-2010 by the INDEPNET Development Team.

 http://www.fusioninventory.org/   http://forge.fusioninventory.org/
 -------------------------------------------------------------------------

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
 --------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: MAZZONI Vincent
// Purpose of file: management of communication with agents
// ----------------------------------------------------------------------
/**
 * The datas are XML encoded and compressed with Zlib.
 * XML rules :
 * - XML tags in uppercase
 **/

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}


/**
 * Class 
 **/
class PluginFusinvinventoryImport_Software extends CommonDBTM  {

   
   function addSoftware($idmachine, $array) {
      global $DB;

      $PluginFusioninventoryConfig = new PluginFusioninventoryConfig();
      if ($PluginFusioninventoryConfig->getValue($_SESSION["plugin_fusinvinventory_moduleid"],
              "import_software") == '0') {
         return;
      }


      $rulecollection = new RuleDictionnarySoftwareCollection;
      $Software = new Software;

      $res_rule = $rulecollection->processAllRules(array("name"=>$array['name'],
                                                         "old_version"=>$array['version']));
      $modified_name = "";
      if (isset($res_rule["name"])) {
         $modified_name = $res_rule["name"];
      } else {
         $modified_name = $array['name'];
      }
      if (isset($res_rule["version"]) && $res_rule["version"]!= '') {
         $modified_version = $res_rule["version"];
      } else {
         $modified_version = $array['version'];
      }

      $manufacturer = 0;
      if (isset($array['PUBLISHER'])) {
         $manufacturer = Dropdown::importExternal('Manufacturer', $array['PUBLISHER']);
      }

      $software_id = $Software->addOrRestoreFromTrash($modified_name, $manufacturer, $_SESSION["plugin_fusinvinventory_entity"]);


      $isNewVers = 0;
      $query = "SELECT `id`
                FROM `glpi_softwareversions`
                WHERE `softwares_id` = '$software_id'
                      AND `name` = '$modified_version'
                      AND `entities_id` = '".$_SESSION["plugin_fusinvinventory_entity"]."' ";
      $result = $DB->query($query);
      if ($DB->numrows($result) > 0) {
         $data = $DB->fetch_array($result);
         $isNewVers = $data["id"];
      } else {
         $SoftwareVersion = new SoftwareVersion;
         // TODO : define a default state ? Need a new option in config
         // Use $cfg_ocs["states_id_default"] or create a specific one ?
         $input["softwares_id"] = $software_id;
         $input["name"] = $modified_version;
         if (isset($array['PUBLISHER'])) {
            $input["manufacturers_id"] = Dropdown::importExternal('Manufacturer', $array['PUBLISHER']);
         }
         $input['entities_id'] = $_SESSION["plugin_fusinvinventory_entity"];
         $isNewVers = $SoftwareVersion->add($input);
      }

      $Computer_SoftwareVersion = new Computer_SoftwareVersion;
      $Computer_SoftwareVersion_id = $Computer_SoftwareVersion->add(array('computers_id' => $idmachine,
                                         '_no_history'  => $_SESSION["plugin_fusinvinventory_no_history_add"],
                                           'softwareversions_id' => $isNewVers));
      return $Computer_SoftwareVersion_id;
   }



   function deleteItem($items_id, $idmachine) {
      $Computer_SoftwareVersion = new Computer_SoftwareVersion;
      $Computer_SoftwareVersion->getFromDB($items_id);
      if ($Computer_SoftwareVersion->fields['computers_id'] == $idmachine) {
         $Computer_SoftwareVersion->delete(array("id" => $items_id));
      }
   }
   
}

?>