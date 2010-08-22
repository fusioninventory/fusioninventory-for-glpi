<?php
/*
 * @version $Id$
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2010 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org
 -------------------------------------------------------------------------

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
class PluginFusinvinventorySoftwares {

   
   function addSoftware($idmachine, $array) {
      global $DB;
      // COMMENTS = Build tool from the boost.org
      // NAME = boost-jam
      // VERSION = 1.43.0<br />

      $rulecollection = new RuleDictionnarySoftwareCollection;
      $Software = new Software;

      $res_rule = $rulecollection->processAllRules(array("name"=>$array['name'],
                                                         "old_version"=>$array['version']));
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

      $software_id = $Software->addOrRestoreFromTrash($modified_name, 0, 0);


      $isNewVers = 0;
      $query = "SELECT `id`
                FROM `glpi_softwareversions`
                WHERE `softwares_id` = '$software_id'
                      AND `name` = '$modified_version'";
      $result = $DB->query($query);
      if ($DB->numrows($result) > 0) {
         $data = $DB->fetch_array($result);
         $isNewVers = $data["id"];
      }
      if (!$isNewVers) {
         $SoftwareVersion = new SoftwareVersion;
         // TODO : define a default state ? Need a new option in config
         // Use $cfg_ocs["states_id_default"] or create a specific one ?
         $input["softwares_id"] = $software_id;
         $input["name"] = $modified_version;
         $isNewVers = $SoftwareVersion->add($input);
      }

      $Computer_SoftwareVersion = new Computer_SoftwareVersion;
      $Computer_SoftwareVersion_id = $Computer_SoftwareVersion->add(array('computers_id'        => $idmachine,
                                           'softwareversions_id' => $isNewVers));
      return $Computer_SoftwareVersion_id;
   }



   function removeSoftware($array) {



   }

   
}

?>