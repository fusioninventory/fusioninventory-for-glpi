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
 * This file is used to manage the hours in the timeslot.
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
 * @author    David Durieux
 * @copyright Copyright (c) 2010-2016 FusionInventory team
 * @license   AGPL License 3.0 or (at your option) any later version
 *            http://www.gnu.org/licenses/agpl-3.0-standalone.html
 * @link      http://www.fusioninventory.org/
 * @link      https://github.com/fusioninventory/fusioninventory-for-glpi
 *
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

/**
 * Manage the hours in the timeslot.
 */
class PluginFusioninventoryTimeslotEntry extends CommonDBTM {

   /**
    * We activate the history.
    *
    * @var boolean
    */
   public $dohistory = true;

   /**
    * The right name for this class
    *
    * @var string
    */
   static $rightname = 'plugin_fusioninventory_task';


   /**
    * Get name of this type by language of the user connected
    *
    * @param integer $nb number of elements
    * @return string name of this type
    */
   static function getTypeName($nb = 0) {
      return __('Time slot entry', 'fusioninventory');
   }


   /**
    * Get search function for the class
    *
    * @return array
    */
   function rawSearchOptions() {

      $tab = [];

      $tab[] = [
         'id' => 'common',
         'name' => __('Time slot', 'fusioninventory')
      ];

      $tab[] = [
         'id'        => '1',
         'table'     => $this->getTable(),
         'field'     => 'name',
         'name'      => __('Name'),
         'datatype'  => 'itemlink',
      ];

      $tab[] = [
         'id'       => '2',
         'table'    => 'glpi_entities',
         'field'    => 'completename',
         'name'     => Entity::getTypeName(1),
         'datatype' => 'dropdown',
      ];

      $tab[] = [
         'id'        => '3',
         'table'     => $this->getTable(),
         'field'     => 'is_recursive',
         'name'      => __('Child entities'),
         'datatype'  => 'bool',
      ];

      $tab[] = [
         'id'        => '4',
         'table'     => $this->getTable(),
         'field'     => 'name',
         'name'      => __('Name'),
         'datatype'  => 'string',
      ];

      return $tab;
   }


   /**
    * Display form to add a new time entry in timeslot
    *
    * @param integer $timeslots_id
    */
   function formEntry($timeslots_id) {
      $ID = 0;
      $options = [];
      $this->initForm($ID, $options);
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo __('Start time', 'fusioninventory');
      echo "</td>";
      echo "<td>";
      $days = [
          '1' => __('Monday'),
          '2' => __('Tuesday'),
          '3' => __('Wednesday'),
          '4' => __('Thursday'),
          '5' => __('Friday'),
          '6' => __('Saturday'),
          '7' => __('Sunday')
      ];
      echo '<div id="beginday">';
      Dropdown::showFromArray('beginday', $days);
      echo '</div>';
      $hours = [];
      $dec = 15 * 60;
      for ($timestamp = 0; $timestamp < (24 * 3600); $timestamp += $dec) {
         $hours[$timestamp] = date('H:i', $timestamp);
      }
      PluginFusioninventoryToolbox::showHours('beginhours', ['step' => 15]);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo __('End time', 'fusioninventory');
      echo "</td>";
      echo "<td>";
      echo '<div id="beginday">';
      Dropdown::showFromArray('lastday', $days);
      echo '</div>';
      PluginFusioninventoryToolbox::showHours('lasthours', ['step' => 15]);
      echo Html::hidden('timeslots_id', ['value' => $timeslots_id]);
      echo "</td>";
      echo "</tr>";
      $this->showFormButtons($options);

      $this->formDeleteEntry($timeslots_id);

      $this->showTimeSlot($timeslots_id);
   }


   /**
    * Display delete form
    *
    * @todo rename this method in showTimeslots() since it's not only used to delete but also to
    *       show the list of Timeslot Entries. -- Kevin 'kiniou' Roy
    *
    * @param integer $timeslots_id
    */
   function formDeleteEntry($timeslots_id) {

      $dbentries = getAllDataFromTable(
         'glpi_plugin_fusioninventory_timeslotentries', [
            'WHERE'  => ['plugin_fusioninventory_timeslots_id' => $timeslots_id],
            'ORDER'  => ['day', 'begin ASC']
         ]
      );

      $options = [];
      $ID      = key($dbentries);
      $canedit = $this->getFromDB($ID)
                 && $this->can($ID, READ);
      $this->showFormHeader($options);

      foreach ($dbentries as $dbentry) {

         echo "<tr class='tab_bg_3'>";
         echo "<td>";
         $daysofweek = Toolbox::getDaysOfWeekArray();
         $daysofweek[7] = $daysofweek[0];
         unset($daysofweek[0]);
         echo $daysofweek[$dbentry['day']];
         echo "</td>";
         echo "<td>";
         echo PluginFusioninventoryToolbox::getHourMinute($dbentry['begin']);
         echo " - ";
         echo PluginFusioninventoryToolbox::getHourMinute($dbentry['end']);
         echo "</td>";
         echo "<td colspan='2'>";
         if ($canedit) {
            echo "<input type='submit' class='submit' name='purge-".$dbentry['id']."' value='delete' />";
         }
         echo "</td>";
         echo "</tr>";
      }
      $this->showFormButtons(['canedit' => false]);
   }


   /**
    * Display timeslot graph
    *
    * @todo This must be moved in Timeslot class since a Task class is linked to a Timeslot and not
    * directly to a TimeslotEntry. The Timeslot class must be the entry point of any other class.
    * -- Kevin 'kiniou' Roy
    *
    * @param integer $timeslots_id
    */
   function showTimeSlot($timeslots_id) {
      echo "<div id='chart'></div>";
      echo "<div id='startperiod'></div>";
      echo "<div id='stopperiod'></div>";

      $daysofweek = Toolbox::getDaysOfWeekArray();
      $daysofweek[7] = $daysofweek[0];
      unset($daysofweek[0]);
      $dates = [
          $daysofweek[1] => [],
          $daysofweek[2] => [],
          $daysofweek[3] => [],
          $daysofweek[4] => [],
          $daysofweek[5] => [],
          $daysofweek[6] => [],
          $daysofweek[7] => [],
      ];

      for ($day=1; $day <= 7; $day++) {
         $dbentries = getAllDataFromTable(
            'glpi_plugin_fusioninventory_timeslotentries', [
               'WHERE'  => [
                  'plugin_fusioninventory_timeslots_id' => $timeslots_id,
                  'day'                                 => $day,
               ],
               'ORDER'  => 'begin ASC'
            ]
         );
         foreach ($dbentries as $entries) {
            $dates[$daysofweek[$day]][] = [
                'start' => $entries['begin'],
                'end'   => $entries['end']
            ];
         }
      }
      echo '<script>timeslot(\''.json_encode($dates).'\')</script>';
   }


   /**
    * Add a new entry
    *
    * @param array $data
    */
   function addEntry($data) {
      if ($data['lastday'] < $data['beginday']) {
         return;
      } else if ($data['lastday'] == $data['beginday']
              && $data['lasthours'] <= $data['beginhours']) {
         return;
      }
      // else ok, we can update DB
      for ($day=$data['beginday']; $day <= $data['lastday']; $day++) {
         $range = [];

         $range['beginhours'] = $data['beginhours'];
         $range['lasthours'] = $data['lasthours'];
         if ($data['beginday'] < $day) {
            $range['beginhours'] = 0;
         }
         if ($data['lastday'] > $day) {
            $range['lasthours'] = (24 * 3600);
         }

         // now get from DB
         $dbentries = getAllDataFromTable(
            'glpi_plugin_fusioninventory_timeslotentries', [
               'WHERE'  => [
                  'plugin_fusioninventory_timeslots_id' => $data['timeslots_id'],
                  'day'                                 => $day,
               ],
               'ORDER'  => 'begin ASC'
            ]
         );

         $inThePeriod = false;
         $afterPeriod = false;
         $updateEntries = [];
         $deleteEntries = [];
         $addEntries = [];

         foreach ($dbentries as $entries) {
            if ($afterPeriod) {
               continue;
            }

            if ($inThePeriod) {
               // So we need manage the end
               if ($range['lasthours'] < $entries['begin']) {
                  $addEntries[] = [
                     'plugin_fusioninventory_timeslots_id' => $data['timeslots_id'],
                     'day'   => $day,
                     'begin' => $range['beginhours'],
                     'end'   => $range['lasthours']
                  ];
                  $inThePeriod = false;
                  $afterPeriod = true;
                  continue;
               } else if ($range['lasthours'] > $entries['end']) {
                  $deleteEntries[] = $entries;
                  continue;
               } else {
                  $entries['begin'] = $range['beginhours'];
                  $updateEntries[] = $entries;
                  $inThePeriod = false;
                  $afterPeriod = true;
                  continue;
               }
            } else if (($range['lasthours'] < $entries['begin'])) {
               // We add
               $this->add([
                  'plugin_fusioninventory_timeslots_id' => $data['timeslots_id'],
                  'day'   => $day,
                  'begin' => $range['beginhours'],
                  'end'   => $range['lasthours']
               ]);
               continue 2;
            } else if ($range['beginhours'] > $entries['end']) {
               // Not manage, hop to next entry
               continue;
            }

            if ($range['beginhours'] < $entries['begin']) {
               $inThePeriod = true;

               if ($range['lasthours'] <= $entries['end']) {
                  $entries['begin'] = $range['beginhours'];
                  $updateEntries[] = $entries;
                  $inThePeriod = false;
                  $afterPeriod = true;
               } else {
                  $deleteEntries[] = $entries;
               }
            } else if ($range['beginhours'] < $entries['end']) {
               $inThePeriod = true;
               $range['beginhours'] = $entries['begin'];

               if ($range['lasthours'] <= $entries['end']) {
                  $entries['begin'] = $range['beginhours'];
                  $updateEntries[] = $entries;
                  $inThePeriod = false;
                  $afterPeriod = true;
               } else {
                  $deleteEntries[] = $entries;
               }
            }
         }
         if (count($dbentries) == 0) {
            $addEntries[] = [
               'plugin_fusioninventory_timeslots_id' => $data['timeslots_id'],
               'day'   => $day,
               'begin' => $range['beginhours'],
               'end'   => $range['lasthours']
            ];
         } else if ($inThePeriod || (count($updateEntries) == 0 && count($deleteEntries) == 0 & count($addEntries) == 0)) {
            $addEntries[] = [
               'plugin_fusioninventory_timeslots_id' => $data['timeslots_id'],
               'day'   => $day,
               'begin' => $range['beginhours'],
               'end'   => $range['lasthours']
            ];
         }

         foreach ($updateEntries as $entry) {
            $this->update($entry);
         }
         foreach ($deleteEntries as $entry) {
            $this->delete(['id' => $entry['id']]);
         }
         foreach ($addEntries as $entry) {
            $this->add($entry);
         }
      }
   }

}
