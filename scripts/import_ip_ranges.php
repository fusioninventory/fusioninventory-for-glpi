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
 * This file is used to import IP ranges from a CSV file.
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

if (!isset($file)) {
    $file = './import_ip_ranges.csv';
}

// CVS default file format
$DELIMITER = ",";
if (isset($_SESSION["glpicsv_delimiter"])) {
    $DELIMITER = $_SESSION["glpicsv_delimiter"];
}
$ENCLOSURE = '"';

/**
 * Script for importing SNMP authentications into the GLPI Fusion Inventory
 * - search and open a CSV file
 * - import found IP ranges and relations with SNMP authentications
 */
$row = 1;
if (($handle = fopen($file, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 0, $DELIMITER, $ENCLOSURE)) !== FALSE) {
        $data[0] = trim($data[0]);
        if (strtolower($data[0]) == 'nom') {
            // File header
            echo nl2br("File header: " . serialize($data) . PHP_EOL);
            continue;
        }
        // Check fields count
        if (count($data) < 4) {
            // Skip empty line...
            echo nl2br("-> skipping empty line!" . PHP_EOL);
            continue;
        }

        // Clean name field
        $name = trim($data[0]);
        if ($name == '') {
            // Skip empty name...
            echo nl2br("-> skipping empty name!" . PHP_EOL);
            continue;
        }

        echo nl2br("\n-----\nNew IP range: $name:" . PHP_EOL);

        // Clean and check Entity field
        $entity = trim($data[1]);
        if ($entity != '') {
            $db_entities = $db_entity->find("`completename`='".$entity."'", '', 1);
            if (count($db_entities) > 0) {
                $found_entity = current($db_entities);
                $entity_id = $found_entity["id"];
                echo nl2br("-> found " . count($db_entities) . " matching entity: " . $found_entity["completename"] . PHP_EOL);
            } else {
                echo nl2br("-> skipping not found entity: '$name / $entity'!" . PHP_EOL);
                continue;
            }
        }

        // Clean and check range start
        $range_start = trim($data[2]);
        if ($range_start == '' OR (! check_valid_ip($range_start))) {
            // Skip invalid data...
            echo nl2br("-> skipping empty or invalid IP range start: '$name / $range_start'!" . PHP_EOL);
            continue;
        }
        // Clean and check range stop
        $range_stop = trim($data[3]);
        if ($range_stop == '' OR (! check_valid_ip($range_stop))) {
            // Skip invalid data...
            echo nl2br("-> skipping empty or invalid IP range stop: '$name / $range_stop'!" . PHP_EOL);
            continue;
        }
        echo nl2br("-> IP range from: $range_start to $range_stop" . PHP_EOL);

        // Clean and check SNMP authentication fields
        $i = 4;
        $ar_snmp_auth = array();
        while ($i < count($data)) {
            $snmp_auth = trim($data[$i]);
            if ($snmp_auth != '') {
                $snmp_auths = $db_cfg_sec->find("`name`='".$snmp_auth."'", '', 1);
                if (count($snmp_auths) > 0) {
                    $snmp = current($snmp_auths);
                    echo nl2br("-> found " . count($snmp_auths) . " matching SNMP authentication: " . $snmp["name"] . PHP_EOL);
                    array_push($ar_snmp_auth, $snmp["id"]);
                } else {
                    echo nl2br("-> skipping not found SNMP authentication: '$name / $snmp_auth'!" . PHP_EOL);
                    $i++;
                    continue;
                }
            } else {
                // No SNMP authentication for this IP range
                echo nl2br("-> empty SNMP authentication: '$name'!" . PHP_EOL);
            }

            $i++;
        }

        /*
         * Now we have all the fields to create a new IP range
         */
        $input = array(
            'name'          => $name,
            'entities_id'   => $entity_id,
            'ip_start'      => $range_start,
            'ip_end'        => $range_stop
        );

        $ipranges_id = -1;
        $ipranges = $db_ip_range->find("`name`='$name' AND `entities_id`='$entity_id'", '', 1);
        if (count($ipranges) > 0) {
            // Update an existing IP range
            $range = current($ipranges);
            $ipranges_id = $range["id"];
            $input['id'] = $ipranges_id;
            echo nl2br("-> updating an existing IP addresses range: '$name'...");
            $db_ip_range->update($input);
            echo nl2br(" updated." . PHP_EOL);
        } else {
            // Create a new IP range
            echo nl2br("-> creating a new IP addresses range: '$name'...");
            $ipranges_id = $db_ip_range->add($input);
            if (! $ipranges_id) {
                echo nl2br(" ***** error when adding an IP range!" . PHP_EOL);
                print_r($input);
            } else {
                echo nl2br(" created." . PHP_EOL);
            }
        }

        foreach($ar_snmp_auth as $snmp_auth_id) {
            // Relation between IP range and SNMP authentication (if it exists...)
            $input = array(
                'plugin_fusioninventory_ipranges_id'            => $ipranges_id,
                'plugin_fusioninventory_configsecurities_id'    => $snmp_auth_id
            );
            if ($ipranges_id != -1) {
                $ipranges_snmp = $db_ip_range_snmp->find("`plugin_fusioninventory_ipranges_id`='".$ipranges_id."'");
                if (count($ipranges_snmp) > 0) {
                    if ($snmp_auth_id == -1) {
                        echo nl2br("-> deleting an existing IP addresses range / SNMP authentication relation...");
                        $range_snmp = current($ipranges_snmp);
                        $db_ip_range_snmp->getFromDB($range_snmp['id']);
                        $db_ip_range_snmp->deleteFromDB();
                        echo nl2br(" deleted." . PHP_EOL);
                        continue;
                    } else {
                        // Update an existing IP range / SNMP relation
                        $range_snmp = current($ipranges_snmp);
                        $input['id'] = $range_snmp["id"];
                        echo nl2br("-> updating an existing IP addresses range / SNMP authentication relation...");
                        $db_ip_range_snmp->update($input);
                        echo nl2br(" updated." . PHP_EOL);
                    }
                } else {
                    if ($snmp_auth_id != -1) {
                        // Create a new IP range / SNMP relation
                        echo nl2br("-> creating a new IP addresses range / SNMP authentication relation...");
                        $ipranges_snmp_id = $db_ip_range_snmp->add($input);
                        if (! $ipranges_snmp_id) {
                            echo nl2br(" ***** error when adding an IP range / SNMP relation!" . PHP_EOL);
                            print_r($input);
                        } else {
                            echo nl2br(" created." . PHP_EOL);
                        }
                    }
                }
            }
        }

    }
    fclose($handle);
}

function check_valid_ip($ip_address) {

    $valid_address = TRUE;
    $ip_fields = explode(".", $ip_address);
    if (count($ip_fields) < 4) {
        return FALSE;
    }
    foreach ($ip_fields as $ip_field) {
        if (!is_numeric($ip_field) OR $ip_field > 255) {
            $valid_address = FALSE;
            break;
        }
    }

    return $valid_address;
}

?>
