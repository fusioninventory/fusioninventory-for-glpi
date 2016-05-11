<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2016 by the FusionInventory Development Team.

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
   @copyright Copyright (c) 2010-2016 FusionInventory team
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

class PluginFusioninventoryTimeslot extends CommonDBTM {

   public $dohistory = TRUE;

   static $rightname = 'plugin_fusioninventory_task';


   /**
   * Get name of this type
   *
   * @return text name of this type by language of the user connected
   *
   **/
   static function getTypeName($nb=0) {
      return __('Time slot', 'fusioninventory');
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
      $tab[2]['datatype']  = 'dropdown';

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



   function defineTabs($options=array()){

      $ong = array();
      $this->addDefaultFormTab($ong);

      return $ong;
   }

   /*
    * Get Timeslot entries according to the requested day of week.
    * @since 0.85+1.0
    * @param timeslot_ids  A list of timeslot's ids.
    * @param weekdays      The day of week (ISO-8601 numeric representation).
    * return The list of timeslots entries organized by timeslots ids :
    *    array(
    *       [timeslot #0] => array(
    *          [timeslot_entry #2] => array(
    *             ...timeslot_entry fields...
    *          )
    *          [timeslot_entry #3] => array(
    *             ...timeslot_entry fields...
    *          )
    *       ),
    *       [timeslot #5] => array(
    *          [timeslot_entry #9] => array(
    *             ...timeslot_entry fields...
    *          )
    *          [timeslot_entry #66] => array(
    *             ...timeslot_entry fields...
    *          )
    *       )
    *    )
    */

   function getTimeslotEntries($timeslot_ids = array(), $weekdays = null) {

      $condition = array(
         "`plugin_fusioninventory_timeslots_id` in ('".implode("','",$timeslot_ids)."')",
      );
      if ( !is_null($weekdays) ) {
         $condition[] = "and `day` = '".$weekdays."'";
      }

      $results = array();

      $timeslot_entries = getAllDatasFromTable(
         "glpi_plugin_fusioninventory_timeslotentries",
         implode("\n", $condition),
         false, ''
      );

      foreach ( $timeslot_entries as $timeslot_entry ) {
         $timeslot_id = $timeslot_entry['plugin_fusioninventory_timeslots_id'];
         $timeslot_entry_id = $timeslot_entry['id'];
         $results[$timeslot_id][$timeslot_entry_id] = $timeslot_entry;
      }

      return $results;
   }

   /**
   * Get all current active timeslots
   * @since 0.85+1.0
   */
   function getCurrentActiveTimeslots() {
      global $DB;

      $timeslots   = array();
      $date        = new DateTime('NOW');
      $day_of_week = $date->format("N");
      $timeinsecs  = $date->format('H') * HOUR_TIMESTAMP
                        + $date->format('i') * MINUTE_TIMESTAMP
                        + $date->format('s');

      //Get all timeslots currently active
      $query_timeslot = "SELECT `t`.`id`
                         FROM `glpi_plugin_fusioninventory_timeslots` as t
                         INNER JOIN `glpi_plugin_fusioninventory_timeslotentries` as te
                           ON (`te`.`plugin_fusioninventory_timeslots_id`=`t`.`id`)
                         WHERE $timeinsecs BETWEEN `te`.`begin`
                            AND `te`.`end`
                            AND `day`='".$day_of_week."'";
      foreach ($DB->request($query_timeslot) as $timeslot) {
         $timeslots[] = $timeslot['id'];
      }

      return $timeslots;
   }

   /**
    * Get Timeslot cursor (ie. seconds since 00:00) according to a certain datetime
    * @param date    The date and time we want to transform into cursor. If null the default value
    *                is now()
    * @since 0.85+1.0
    */

   function getTimeslotCursor(DateTime $datetime = null) {
      if (is_null($datetime)) {
         $datetime = new DateTime();
      }
      $dateday = new DateTime( $datetime->format("Y-m-d 0:0:0") );
      $timeslot_cursor = date_create('@0')->add($dateday->diff($datetime,true))->getTimestamp();
      return $timeslot_cursor;
   }

   /**
   * Display form for agent configuration
   *
   * @param $computers_id integer ID of the agent
   * @param $options array
   *
   * @return bool TRUE if form is ok
   *
   **/
   function showForm($ID, $options=array()) {

      $this->initForm($ID, $options);
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      //TRANS: %1$s is a string, %2$s a second one without spaces between them : to change for RTL
      echo "<td>".sprintf(__('%1$s%2$s'),__('Name'),
                          (isset($options['withtemplate']) && $options['withtemplate']?"*":"")).
           "</td>";
      echo "<td>";
      $objectName = autoName($this->fields["name"], "name",
                             (isset($options['withtemplate']) && ( $options['withtemplate']== 2)),
                             $this->getType(), $this->fields["entities_id"]);
      Html::autocompletionTextField($this, 'name', array('value' => $objectName));
      echo "</td>";
      echo "<td>".__('Comments')."</td>";
      echo "<td class='middle'>";
      echo "<textarea cols='45' rows='4' name='comment' >".$this->fields["comment"];
      echo "</textarea></td></tr>\n";

      $this->showFormButtons($options);

      if ($ID > 0) {
         $pf = new PluginFusioninventoryTimeslotEntry();
         $pf->formEntry($ID);
      }
      return true;
   }
}

?>
