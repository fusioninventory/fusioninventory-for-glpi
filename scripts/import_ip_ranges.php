<?php

/**
 * FusionInventory
 *
 * Copyright (C) 2010-2016 by the FusionInventory Development Team.
 *
 * http://www.fusioninventory.org/
 * https://github.com/fusioninventory/fusioninventory-for-glpi
 * http://forge.fusioninventory.org/
 *
 * ------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of FusionInventory project.
 *
 * FusionInventory is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * FusionInventory is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.
 *
 * ------------------------------------------------------------------------
 *
 * This file is used to manage the agents
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
 * @author    Frédéric Mohier
 * @copyright Copyright (c) 2010-2017 FusionInventory team
 * @license   AGPL License 3.0 or (at your option) any later version
 *            http://www.gnu.org/licenses/agpl-3.0-standalone.html
 * @link      http://www.fusioninventory.org/
 * @link      https://github.com/fusioninventory/fusioninventory-for-glpi
 *
 */

include ("../../../inc/includes.php");
$db_cfg_sec = new PluginFusioninventoryConfigSecurity();
$db_ip_range = new PluginFusioninventoryIPRange();
$db_ip_range_snmp = new PluginFusioninventoryIPRange_ConfigSecurity();
$db_agent = new PluginFusioninventoryAgent();
$db_entity = new Entity();

$file = './import_ip_ranges.csv';

// CVS default file format
$DELIMITER = "\t";
$ENCLOSURE = '"';

/**
 * Script for importing SNMP credentials into the GLPI Fusion Inventory
 * - search and open a CSV file
 * - import found IP ranges and relations with SNMP credentials
 */
$row = 1;
if (($handle = fopen($file, "r")) !== false) {
   while (($data = fgetcsv($handle, 0, $DELIMITER, $ENCLOSURE)) !== false) {
      $data[0] = trim($data[0]);
      if (strtolower($data[0]) == 'nom') {
         // File header
         echo "File header: " . serialize($data) . "\n";
         continue;
      }
      // Check fields count
      if (count($data) < 4) {
         // Skip empty line...
         echo "-> skipping empty line!\n";
         continue;
      }

      // Clean name field
      $name = trim($data[0]);
      if ($name == '') {
         // Skip empty name...
         echo "-> skipping empty name!\n";
         continue;
      }

      //        echo "Data: " . serialize($data) . "\n";
      echo "\n-----\nNew IP range: $name:\n";

      // Clean and check Entity field
      $entity = trim($data[1]);
      if ($entity != '') {
         $db_entities = $db_entity->find(['completename' => $entity], [], 1);
         if (count($db_entities) > 0) {
            $found_entity = current($db_entities);
            $entity_id = $found_entity["id"];
            echo "-> found " . count($db_entities) . " matching entity: " . $found_entity["completename"] . "\n";
         } else {
            echo "-> skipping not found entity: '$name / $entity'!\n";
            continue;
         }
      }

      // Clean and check range start
      $range_start = trim($data[2]);
      if ($range_start == '' OR (! check_valid_ip($range_start))) {
         // Skip invalid data...
         echo "-> skipping empty or invalid IP range start: '$name / $range_start'!\n";
         continue;
      }
      // Clean and check range stop
      $range_stop = trim($data[3]);
      if ($range_stop == '' OR (! check_valid_ip($range_stop))) {
         // Skip invalid data...
         echo "-> skipping empty or invalid IP range stop: '$name / $range_stop'!\n";
         continue;
      }
      echo "-> IP range from: $range_start to $range_stop\n";

      // Clean and check SNMP credentials fields
      $i = 4;
      $ar_snmp_auth = [];
      while ($i < count($data)) {
         $snmp_auth = trim($data[$i]);
         if ($snmp_auth != '') {
            $snmp_auths = $db_cfg_sec->find("`name`='".$snmp_auth."'", '', 1);
            if (count($snmp_auths) > 0) {
               $snmp = current($snmp_auths);
               echo "-> found " . count($snmp_auths) . " matching SNMP credentials: " . $snmp["name"] . "\n";
               array_push($ar_snmp_auth, $snmp["id"]);
            } else {
               echo "-> skipping missing SNMP credentials: '$name / $snmp_auth'!\n";
               continue;
            }
         } else {
            // No SNMP credentials for this IP range
            echo "-> empty SNMP credentials: '$name'!\n";
         }

         $i++;
      }
      /* If some more SNMP credentials are needed...
      // Clean and check SNMP credentials field #2
      $snmp_auth = trim($data[4]);
      if ($snmp_auth != '') {
          $snmp_auths = $db_cfg_sec->find(['name' => $snmp_auth], [], 1);
          if (count($snmp_auths) > 0) {
              $snmp = current($snmp_auths);
              $snmp_auth = $snmp["id"];
              echo "-> found " . count($snmp_auths) . " matching SNMP credentials: " . $snmp["name"] . "\n";
          } else {
              echo "-> skipping missing SNMP credentials: '$name / $snmp_auth'!\n";
              continue;
          }
      }
      if ($snmp_auth == '') {
          $snmp_auth = 0;
      }
      $snmp_auth2 = $snmp_auth;
      // Clean and check SNMP credentials field #3
      $snmp_auth = trim($data[5]);
      if ($snmp_auth != '') {
          $snmp_auths = $db_cfg_sec->find(['name' => $snmp_auth], [], 1);
          if (count($snmp_auths) > 0) {
              $snmp = current($snmp_auths);
              $snmp_auth = $snmp["id"];
              echo "-> found " . count($snmp_auths) . " matching SNMP credentials: " . $snmp["name"] . "\n";
          } else {
              echo "-> skipping missing SNMP credentials: '$name / $snmp_auth'!\n";
              continue;
          }
      }
      if ($snmp_auth == '') {
          $snmp_auth = 0;
      }
      $snmp_auth3 = $snmp_auth;
      */

      /*
       * Now we have all the fields to create a new IP range
       */
      $input = [
          'name'          => $name,
          'entities_id'   => $entity_id,
          'ip_start'      => $range_start,
          'ip_end'        => $range_stop
      ];

      $ipranges_id = -1;
      $ipranges = $db_ip_range->find(['name' => $name], [], 1);
      if (count($ipranges) > 0) {
         // Update an existing IP range
         $range = current($ipranges);
         $ipranges_id = $range["id"];
         $input['id'] = $ipranges_id;
         echo "-> updating an existing IP addresses range: '$name'...";
         $db_ip_range->update($input);
         echo " updated.\n";
      } else {
         // Create a new IP range
         echo "-> creating a new IP addresses range: '$name'...";
         $ipranges_id = $db_ip_range->add($input);
         if (! $ipranges_id) {
            echo " error when adding an IP range!\n";
            print_r($input);
         } else {
            echo " created.\n";
         }
      }

      foreach ($ar_snmp_auth as $snmp_auth_id) {
         // Relation between IP range and SNMP credentials (if it exists...)
         $input = [
             'plugin_fusioninventory_ipranges_id'            => $ipranges_id,
             'plugin_fusioninventory_configsecurities_id'    => $snmp_auth_id
         ];
         if ($ipranges_id != -1) {
            $ipranges_snmp = $db_ip_range_snmp->find(['plugin_fusioninventory_ipranges_id' => $ipranges_id]);
            if (count($ipranges_snmp) > 0) {
               if ($snmp_auth_id == -1) {
                  echo "-> deleting an existing IP addresses range / SNMP credentials relation...";
                  $range_snmp = current($ipranges_snmp);
                  $db_ip_range_snmp->getFromDB($range_snmp['id']);
                  $db_ip_range_snmp->deleteFromDB();
                  echo " deleted.\n";
                  continue;
               } else {
                  // Update an existing IP range / SNMP relation
                  $range_snmp = current($ipranges_snmp);
                  $input['id'] = $range_snmp["id"];
                  echo "-> updating an existing IP addresses range / SNMP credentials relation...";
                  $db_ip_range_snmp->update($input);
                  echo " updated.\n";
               }
            } else {
               if ($snmp_auth_id != -1) {
                  // Create a new IP range / SNMP relation
                  echo "-> creating a new IP addresses range / SNMP credentials relation...";
                  $ipranges_snmp_id = $db_ip_range_snmp->add($input);
                  if (! $ipranges_snmp_id) {
                     echo " error when adding an IP range / SNMP relation!\n";
                     print_r($input);
                  } else {
                     echo " created.\n";
                  }
               }
            }
         }
      }

   }
    fclose($handle);
}


function check_valid_ip($ip_address) {

    $valid_address = true;
    $ip_fields = explode(".", $ip_address);
   if (count($ip_fields) < 4) {
      return false;
   }
   foreach ($ip_fields as $ip_field) {
      if (!is_numeric($ip_field) OR $ip_field > 255) {
         $valid_address = false;
         break;
      }
   }

    return $valid_address;
}

