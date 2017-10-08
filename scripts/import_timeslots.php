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
 * This file is used to import timeslots from a CSV file.
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
$db_tss = new PluginFusioninventoryTimeslot();
$db_ts_entries = new PluginFusioninventoryTimeslotEntry();
$db_agent = new PluginFusioninventoryAgent();
$db_entity = new Entity();

if (!isset($file)) {
    $file = './import_timeslots.csv';
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
        if (count($data) < 5) {
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

        echo nl2br("\n-----\nNew TS entry: $name:" . PHP_EOL);

        // Clean and check Entity field
        $entity = trim($data[1]);
        $entity_id = -1;
        $is_recursive = '0';
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
        } else {
            echo nl2br("-> no entity specified, using GLPI Root entity (recursive for the timeslot)" . PHP_EOL);
            $entity_id = 0;
            $is_recursive = '1';
        }

        // Clean and check entry day
        $day = trim($data[2]);
        $day_index = check_valid_day($day);
        if ($day == '' OR ($day_index <= 0)) {
            // Skip invalid data...
            echo nl2br("***** skipping empty or invalid day: '$name / $day'!" . PHP_EOL);
            continue;
        }
        echo nl2br("-> TS entry day: $day ($day_index)");

        // Clean and check TS entry start
        $ts_entry_start = trim($data[3]);
        if ($ts_entry_start == '' OR (! check_valid_hour($ts_entry_start))) {
            // Skip invalid data...
            echo nl2br("***** skipping empty or invalid TS entry start: '$name / $ts_entry_start'!" . PHP_EOL);
            continue;
        }
        // Clean and check TS entry stop
        $ts_entry_stop = trim($data[4]);
        if ($ts_entry_stop == '' OR (! check_valid_hour($ts_entry_stop))) {
            // Skip invalid data...
            echo nl2br("***** skipping empty or invalid TS entry stop: '$name / $ts_entry_stop'!" . PHP_EOL);
            continue;
        }
        echo nl2br("-> timeslot from: $ts_entry_start to $ts_entry_stop" . PHP_EOL);

        /*
         * Now we have all the fields to create a new TS entry
         */
        $input_ts = array(
            'name'          => $name,
            'entities_id'   => $entity_id,
            'is_recursive'  => $is_recursive
        );

        $ts_id = -1;
        $tss = $db_tss->find("`name`='$name' AND `entities_id`='$entity_id'", '', 1);
        if (count($tss) > 0) {
            // Update an existing timeslot
            $ts = current($tss);
            $ts_id = $ts["id"];
            $input_ts['id'] = $ts_id;
            echo nl2br("-> updating an existing timeslot: '$name'...");
            $db_tss->update($input_ts);
            echo nl2br(" updated." . PHP_EOL);
        } else {
            // Create a new timeslot
            echo nl2br("-> creating a new timeslot: '$name'...");
            $ts_id = $db_tss->add($input_ts);
            if (! $ts_id) {
                echo nl2br(" ***** error when adding a timeslot!" . PHP_EOL);
                print_r($input_ts);
            } else {
                echo nl2br(" created." . PHP_EOL);
            }
        }

        $input_ts_entry = array(
            'timeslots_id'  => $ts_id,
            'entities_id'   => $entity_id,
            'beginday'      => $day_index,
            'lastday'       => $day_index,
            'beginhours'    => hour_to_seconds($ts_entry_start),
            'lasthours'     => hour_to_seconds($ts_entry_stop)
        );

        echo nl2br("-> updating an existing timeslot entry: '$name / $day / $ts_entry_start-$ts_entry_stop'...");
        $ts_entries = $db_ts_entries->addEntry($input_ts_entry);
        echo nl2br(" updated." . PHP_EOL);
    }
    fclose($handle);
}

function check_valid_day($day) {
    $day = strtolower($day);
    $days = array("lundi", "mardi", "mercredi", "jeudi", "vendredi", "samedi", "dimanche");
    if (! in_array($day, $days)) {
        return 0;
    }

    $found = array_search($day, $days) + 1;
    return $found;
}

function check_valid_hour($hour) {
    return preg_match("/(2[0-4]|[01][0-9]):([0-5][0-9])/", $hour);
}

function hour_to_seconds($hour) {
    sscanf($hour, "%d:%d", $hours, $minutes);

    return ($hours * 3600 + $minutes * 60 + $seconds);
}
?>
