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
$db_agent = new PluginFusioninventoryAgent();
$db_entity = new Entity();
$db_computer = new Computer();
$db_ip_range = new PluginFusioninventoryIPRange();
$db_job = new PluginFusioninventoryTaskjob();

$file = './import_jobs.csv';

// CVS default file format
$DELIMITER = "\t";
$ENCLOSURE = '"';

/**
 * Script for importing timeslots into the GLPI Fusion Inventory
 * - search and open a CSV file
 * - import found timeslot entries in the DB
 */
$row = 1;
if (($handle = fopen($file, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 0, $DELIMITER, $ENCLOSURE)) !== FALSE) {
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
        echo "\n-----\nNew job entry: $name:\n";

        // Clean and check Entity field
        $entity = trim($data[1]);
        $entity_id = -1;
        if ($entity != '') {
            $db_entities = $db_entity->find("`completename`='".$entity."'", '', 1);
            if (count($db_entities) > 0) {
                $found_entity = current($db_entities);
                $entity_id = $found_entity["id"];
                echo "-> found " . count($db_entities) . " matching entity: " . $found_entity["completename"] . "\n";
            } else {
                echo "-> skipping not found entity: '$name / $entity'!\n";
                continue;
            }
        }

        // Clean and check method
        $method = trim($data[2]);
        $module = '';
        $available_methods = PluginFusioninventoryStaticmisc::getmethods();
        foreach ($available_methods as $available_method) {
            if ($method == $available_method['name']) {
                $method = $available_method['method'];
                $module = $available_method['module'];
            }
        }
        if ($module == '') {
            // Skip invalid data...
            echo "-> skipping empty or invalid method: '$name / $method'!\n";
            continue;
        }
        echo "-> found job method: $method\n";

        // Clean and check computer
        $computer = trim($data[3]);
        $computer_id = -1;
        if ($computer != '') {
            $db_computers = $db_computer->find("`name`='".$computer."'", '', 1);
            if (count($db_computers) > 0) {
                $found_computer = current($db_computers);
                $computer_id = $found_computer["id"];
                echo "-> found " . count($db_computers) . " matching computer: " . $found_computer["name"] . "\n";
            } else {
                echo "-> skipping not found computer: '$name / $computer'!\n";
                continue;
            }
        }
        $ar_agents = array();
        $db_agents = $db_agent->find("`computers_id`='".$computer_id."'", '', 1);
        if (count($db_agents) > 0) {
            $found_agent = current($db_agents);
            $agent_id = $found_agent["id"];
            echo "-> found " . count($db_agents) . " matching agent: " . $found_agent["name"] . "\n";
            array_push($ar_agents, array("PluginFusioninventoryAgent" => $found_agent["id"]));
        } else {
            echo "-> skipping because computer do not have an agent: '$name / $computer'!\n";
            continue;
        }

        // Clean and check IP ranges
        $i = 4;
        $ar_ip_ranges = array();
        while ($i < count($data)) {
            $ip_range = trim($data[$i]);
            if ($ip_range != '') {
                $ip_ranges = $db_ip_range->find("`name`='".$ip_range."'", '', 1);
                if (count($ip_ranges) > 0) {
                    $found_ip_range = current($ip_ranges);
                    echo "-> found " . count($ip_ranges) . " matching IP range: " . $found_ip_range["name"] . "\n";
                    array_push($ar_ip_ranges, array("PluginFusioninventoryIPRange" => $found_ip_range["id"]));
                } else {
                    echo "-> skipping not found IP range: '$name / $ip_range'!\n";
                    continue;
                }
            } else {
                // No IP range
                echo "-> empty IP range: '$name'!\n";
            }

            $i++;
        }

        /*
         * Now we have all the fields to create a new Job
         */
        $input = array(
            'name'          => $name,
            'entities_id'   => $entity_id,
            'method'        => $method,
            'ip_end'        => $range_stop,
            'targets'       => exportArrayToDB($ar_ip_ranges),
            'actors'        => exportArrayToDB($ar_agents)
        );

        $job_id = -1;
        $jobs = $db_job->find("`name`='".$name."'", '', 1);
        if (count($jobs) > 0) {
            // Update an existing job
            $job = current($jobs);
            $job_id = $job["id"];
            $input['id'] = $job_id;
            echo "-> updating an existing job: '$name'...";
            $db_job->update($input);
            echo " updated.\n";
        } else {
            // Create a new job
            echo "-> creating a new job: '$name'...";
            $job_id = $db_job->add($input);
            if (! $job_id) {
                echo " error when adding a job!\n";
                print_r($input);
            } else {
                echo " created.\n";
            }
        }
    }
    fclose($handle);
}
?>
