<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2014 by the FusionInventory Development Team.

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
   along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    David Durieux
   @co-author
   @copyright Copyright (c) 2010-2013 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2014

   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusioninventoryTimeslotEntry extends CommonDBTM {

   public $dohistory = TRUE;

   static $rightname = 'plugin_fusioninventory_task';


   /**
   * Get name of this type
   *
   * @return text name of this type by language of the user connected
   *
   **/
   static function getTypeName($nb=0) {
      return __('Time slot entry', 'fusioninventory');
   }



   function getSearchOptions() {

      $tab = array();

      $tab['common'] = __('Time slot', 'fusioninventory');

      $tab[1]['table']     = $this->getTable();
      $tab[1]['field']     = 'name';
      $tab[1]['linkfield'] = 'name';
      $tab[1]['name']      = __('Name');
      $tab[1]['datatype']  = 'itemlink';

      $tab[2]['table']     = 'glpi_entities';
      $tab[2]['field']     = 'completename';
      $tab[2]['name']      = __('Entity');

      $tab[3]['table']     = $this->getTable();
      $tab[3]['field']     = 'is_recursive';
      $tab[3]['linkfield'] = 'is_recursive';
      $tab[3]['name']      = __('Child entities');
      $tab[3]['datatype']  = 'bool';

      $tab[4]['table']     = $this->getTable();
      $tab[4]['field']     = 'name';
      $tab[4]['linkfield'] = '';
      $tab[4]['name']      = __('Name');
      $tab[4]['datatype']  = 'string';

      return $tab;
   }



   function formEntry($timeslots_id) {

      $ID = 0;
      $options = array();
      $this->initForm($ID, $options);
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo __('Start time', 'fusioninventory');
      echo "</td>";
      echo "<td>";
      $days = array(
          '1' => __('Monday'),
          '2' => __('Tuesday'),
          '3' => __('Wednesday'),
          '4' => __('Thursday'),
          '5' => __('Friday'),
          '6' => __('Saturday'),
          '7' => __('Sunday')
      );
      echo '<div id="beginday">';
      Dropdown::showFromArray('beginday', $days);
      echo '</div>';
      $hours = array();
      $dec = 15 * 60;
      for ($timestamp = 0; $timestamp < (24 * 3600); $timestamp += $dec){
         $hours[$timestamp] = date('H:i', $timestamp);
      }
      PluginFusioninventoryToolbox::showHours('beginhours', array('step' => 15));
//      echo "<input type='text' name='begin' id='begintimeslot'> ";
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
      PluginFusioninventoryToolbox::showHours('lasthours', array('step' => 15));
//      echo "<input type='text' name='last' id='lasttimeslot'> ";
      echo Html::hidden('timeslots_id', array('value' => $timeslots_id));
      echo "</td>";
      echo "</tr>";

      $this->showFormButtons($options);

      echo "<div id='chart'></div>";
      echo "<div id='startperiod'></div>";
      echo "<div id='stopperiod'></div>";

      $daysofweek = Toolbox::getDaysOfWeekArray();
      $daysofweek[7] = $daysofweek[0];
      unset($daysofweek[0]);
      $dates = array(
          $daysofweek[1] => array(),
          $daysofweek[2] => array(),
          $daysofweek[3] => array(),
          $daysofweek[4] => array(),
          $daysofweek[5] => array(),
          $daysofweek[6] => array(),
          $daysofweek[7] => array(),
      );

      for ($day=1 ; $day <= 7; $day++) {
         $dbentries = getAllDatasFromTable(
                        'glpi_plugin_fusioninventory_timeslotentries',
                        "`plugin_fusioninventory_timeslots_id`='".$timeslots_id."'
                            AND `day`='".$day."'",
                        '',
                        '`begin` ASC');
         foreach ($dbentries as $entries) {
            $dates[$daysofweek[$day]] = array(array(
                'start' => $entries['begin'],
                'end'   => $entries['end']
            ));
         }
      }

      echo '<script>timeslot(\''.json_encode($dates).'\')</script>';
   }



   function addEntry($data) {

      if ($data['lastday'] < $data['beginday']) {
         return;
      } else if ($data['lastday'] == $data['beginday']
              && $data['lasthours'] <= $data['beginhours']) {
         return;
      }
      // else ok, we can update DB
      for ($day=$data['beginday']; $day <= $data['lastday']; $day++) {
         $range = array();

         $range['beginhours'] = $data['beginhours'];
         $range['lasthours'] = $data['lasthours'];
         if ($data['beginday'] < $day) {
            $range['beginhours'] = 0;
         }
         if ($data['lastday'] > $day) {
            $range['lasthours'] = (24 * 3600);
         }

         // now get from DB
         $dbentries = getAllDatasFromTable(
                        'glpi_plugin_fusioninventory_timeslotentries',
                        "`plugin_fusioninventory_timeslots_id`='".$data['timeslots_id']."'
                            AND `day`='".$day."'",
                        '',
                        '`begin` ASC');

         $rangeToUpdate = array();
         $rangeToAdd = array();

         foreach ($dbentries as $entries) {
            // the entry if before this db entry
            if ($range['lasthours'] < $entries['begin']) {
               break;
            }
            //the entry is more after end of this db entry
            if ($range['beginhours'] > $entries['end']) {
               continue;
            }

            // The entry is in this db entry
            if ($range['beginhours'] >= $entries['begin']
                    && $range['lasthours'] <= $entries['end']) {
               unset($range['beginhours']);
               break;
            }

            if ($range['beginhours'] < $entries['begin']) {
               if ($range['lasthours'] < $entries['end']) {
                  $rangeToUpdate = array(array(
                      'id'    => $entries['id'],
                      'begin' => $range['beginhours']
                  ));
                  break;
               } else {
                  $range['beginhours'] = $entries['end'];
               }
            } else if ($range['beginhours'] > $entries['begin']) {
               if ($range['lasthours'] > $entries['end']) {
                  $range['beginhours'] = $entries['end'];
               }
            }
         }
         if (isset($range['beginhours'])
                 && $range['beginhours'] != $range['lasthours']) {
            $rangeToAdd = array(array(
                'begin' => $range['beginhours'],
                'end'   => $range['lasthours'],
                'plugin_fusioninventory_timeslots_id' => $data['timeslots_id'],
                'day'   => $day
            ));
         }
         foreach ($rangeToAdd as $toadd) {
            $this->add($toadd);
         }
         foreach ($rangeToUpdate as $toupdate) {
            $this->update($toupdate);
         }
      }
   }
}

?>
