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
 * This file is used to import jobs and tasks from a CSV file.
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
$db_timeslot = new PluginFusioninventoryTimeslot();
$db_ip_range = new PluginFusioninventoryIPRange();
$db_job = new PluginFusioninventoryTaskjob();
$db_task = new PluginFusioninventoryTask();

if (!isset($file)) {
    $file = './import_jobs.csv';
}

// CVS default file format
$DELIMITER = ",";
if (isset($_SESSION["glpicsv_delimiter"])) {
    $DELIMITER = $_SESSION["glpicsv_delimiter"];
}
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
            echo nl2br("File header: " . serialize($data) . PHP_EOL);
            continue;
        }
        // Check fields count
        if (count($data) < 4) {
            // Skip empty line...
            echo nl2br("***** skipping empty line!" . PHP_EOL);
            continue;
        }

        // Clean name field
        $name = trim($data[0]);
        if ($name == '') {
            // Skip empty name...
            echo nl2br("***** skipping empty name!" . PHP_EOL);
            continue;
        }

        echo nl2br("\n-----\nNew job entry: $name:" . PHP_EOL);

        // Clean and check Entity field
        $entity = trim($data[1]);
        $entity_id = -1;
        if ($entity != '') {
            $db_entities = $db_entity->find("`completename`='".$entity."'", '', 1);
            if (count($db_entities) > 0) {
                $found_entity = current($db_entities);
                $entity_id = $found_entity["id"];
                echo nl2br("-> found " . count($db_entities) . " matching entity: " . $found_entity["completename"] . PHP_EOL);
            } else {
                echo nl2br("***** skipping not found entity: '$name / $entity'!" . PHP_EOL);
                continue;
            }
        }
        // List of allowed entities
        $ar_entities = getSonsOf("glpi_entities", $entity_id);

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
            echo nl2br("***** skipping empty or invalid method: '$name / $method'!" . PHP_EOL);
            continue;
        }
        echo nl2br("-> found job method: $method" . PHP_EOL);

        // Clean and check computer
        $computer = trim($data[3]);
        $computer_id = -1;
        if ($computer != '') {
            $db_computers = $db_computer->find("`name`='$computer'", '', 1);
            if (count($db_computers) > 0) {
                $found_computer = current($db_computers);
                $computer_id = $found_computer["id"];
                echo nl2br("-> found " . count($db_computers) . " matching computer: " . $found_computer["name"] . PHP_EOL);
            } else {
                echo nl2br("***** skipping not found computer: '$name / $computer'!" . PHP_EOL);
                continue;
            }
        }
        $ar_agents = array();
        $db_agents = $db_agent->find("`computers_id`='".$computer_id."'", '', 1);
        if (count($db_agents) > 0) {
            $found_agent = current($db_agents);
            $agent_id = $found_agent["id"];
            echo nl2br("-> found " . count($db_agents) . " matching agent: " . $found_agent["name"] . PHP_EOL);
            array_push($ar_agents, array("PluginFusioninventoryAgent" => $found_agent["id"]));
        } else {
            echo nl2br("***** skipping because computer do not have an agent: '$name / $computer'!" . PHP_EOL);
            continue;
        }
        // Check if the agent can act in our entity
        $agent_entity = $found_agent['entities_id'];
        if ($found_agent['is_recursive']) {
            $db_entities = $db_entity->find("`id`='$agent_entity'", '', 1);
            if (count($db_entities) > 0) {
                $found_entity = current($db_entities);
                // List of allowed entities
                $agent_entities = getSonsOf("glpi_entities", $found_entity['id']);
            }
        } else {
            $agent_entities = array($found_agent['entities_id']);
        }
        if (! in_array($entity_id, $agent_entities)) {
            echo nl2br("***** skipping because our agent cannot act in the required entity!" . PHP_EOL);
            continue;
        }

        // Clean and check Timeslot
        $timeslot = trim($data[4]);
        $timeslot_id = -1;
        if ($timeslot != '') {
            // Search in the agent entities from deepest to highest
            $db_timeslots = $db_timeslot->find("`name`='$timeslot' AND `entities_id` IN ('".implode("', '", $agent_entities)."')", "`entities_id` DESC", 1);
            // Only for the required entity
//            $db_timeslots = $db_timeslot->find("`name`='$timeslot' AND `entities_id`='$entity_id'", '', 1);
            if (count($db_timeslots) > 0) {
                $found_timeslot = current($db_timeslots);
                $timeslot_id = $found_timeslot["id"];
                echo nl2br("-> found " . count($db_timeslots) . " matching timeslot: " . $found_timeslot["name"] . " in entity " . $found_timeslot['entities_id'] . PHP_EOL);
            } else {
                echo nl2br("-> no timeslot, the job task will be enabled all the time." . PHP_EOL);
                $timeslot_id = 0;
            }
        }

        // Clean and check entry active
        $active = trim($data[5]);
        if (empty($active)) {
            $active = "non";
        }
        $active_index = check_valid_active($active);
        if ($active_index < 0) {
            // Skip invalid data...
            echo nl2br("***** skipping empty or invalid active: '$name / $active'!" . PHP_EOL);
            continue;
        }
        echo nl2br("-> the job task is active: $active ($active_index)" . PHP_EOL);

        // Clean and check IP ranges
        $i = 6;
        $ar_ip_ranges = array();
        while ($i < count($data)) {
            $ip_range = trim($data[$i]);
            if ($ip_range != '') {
                // Search in the agent entities from deepest to highest
                $ip_ranges = $db_ip_range->find("`name`='$timeslot' AND `entities_id` IN ('".implode("', '", $agent_entities)."')", "`entities_id` DESC", 1);
                // Only for the required entity
//                $ip_ranges = $db_ip_range->find("`name`='$ip_range' AND `entities_id`='$entity_id'", '', 1);
                if (count($ip_ranges) > 0) {
                    $found_ip_range = current($ip_ranges);
                    echo nl2br("-> found " . count($ip_ranges) . " matching IP range: " . $found_ip_range["name"] . PHP_EOL);
                    array_push($ar_ip_ranges, array("PluginFusioninventoryIPRange" => $found_ip_range["id"]));
                } else {
                    echo nl2br("***** skipping not found IP range: '$name / $ip_range'!" . PHP_EOL);
                    $i++;
                    continue;
                }
            } else {
                // No IP range
                echo nl2br("-> empty IP range: '$name'!" . PHP_EOL);
            }

            $i++;
        }

        /*
         * Now we have all the fields to create a new task
         */
        $input = array(
            'name'                                  => $name,
            'entities_id'                           => $entity_id,
            'is_active'                             => $active_index,
            'plugin_fusioninventory_timeslots_id'   => $timeslot_id
        );

        $task_id = -1;
        $tasks = $db_task->find("`name`='$name' AND `entities_id`='$entity_id'", '', 1);
        if (count($tasks) > 0) {
            // Update an existing task
            $task = current($tasks);
            $task_id = $task["id"];
            $input['id'] = $task_id;
            if (count($ar_ip_ranges) <= 0) {
                echo nl2br("-> no IP ranges, deleting an existing task...");
                $db_task->deleteFromDB();
                echo nl2br(" deleted." . PHP_EOL);
            } else {
                echo nl2br("-> updating an existing task: '$name'...");
                $db_task->update($input);
                echo nl2br(" updated." . PHP_EOL);
            }
        } else {
            if (count($ar_ip_ranges) <= 0) {
                echo nl2br("-> do not create the task: '$name' because no IP ranges exist!" . PHP_EOL);
            } else {
                // Create a new task
                echo nl2br("-> creating a new task: '$name'...");
                $task_id = $db_task->add($input);
                if (! $task_id) {
                    echo nl2br(" ***** error when adding a task!" . PHP_EOL);
                    print_r($input);
                    continue;
                } else {
                    echo nl2br(" created." . PHP_EOL);
                }
            }
        }

        /*
         * Now we have all the fields to create a new Job
         */
        $input = array(
            'name'                              => $name,
            'entities_id'                       => $entity_id,
            'plugin_fusioninventory_tasks_id'   => $task_id,
            'method'                            => $method,
            'targets'                           => exportArrayToDB($ar_ip_ranges),
            'actors'                            => exportArrayToDB($ar_agents)
        );

        $job_id = -1;
        $jobs = $db_job->find("`name`='$name' AND `plugin_fusioninventory_tasks_id`='$task_id'", '', 1);
        if (count($jobs) > 0) {
            // Update an existing job
            $job = current($jobs);
            $job_id = $job["id"];
            $input['id'] = $job_id;
            if (count($ar_ip_ranges) <= 0) {
                echo nl2br("-> no IP ranges, deleting an existing job...");
                $db_job->deleteFromDB();
                echo nl2br(" deleted." . PHP_EOL);
            } else {
                echo nl2br("-> updating an existing job: '$name'...");
                $db_job->update($input);
                echo nl2br(" updated." . PHP_EOL);
            }
        } else {
            if (count($ar_ip_ranges) <= 0) {
                echo nl2br("-> do not create the job: '$name' because no IP ranges exist!" . PHP_EOL);
            } else {
                // Create a new job
                echo nl2br("-> creating a new job: '$name'...");
                $job_id = $db_job->add($input);
                if (! $job_id) {
                    echo nl2br(" ***** error when adding a job!" . PHP_EOL);
                    print_r($input);
                } else {
                    echo nl2br(" created." . PHP_EOL);
                }
            }
        }
    }
    fclose($handle);
}

function check_valid_active($active) {
    $active = strtolower($active);
    $actives = array("non", "oui");

    if (! in_array($active, $actives)) {
        return -1;
    }

    return array_search($active, $actives);
}
?>
