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
 * This file is used to manage and display extended information of
 * network equipments.
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
 * @author    Vincent Mazzoni
 * @author    David Durieux
 * @copyright Copyright (c) 2010-2021 FusionInventory team
 * @license   AGPL License 3.0 or (at your option) any later version
 *            http://www.gnu.org/licenses/agpl-3.0-standalone.html
 * @link      http://www.fusioninventory.org/
 * @link      https://github.com/fusioninventory/fusioninventory-for-glpi
 *
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access this file directly");
}

/**
 * Manage and display extended information of cron tasks.
 */
class PluginFusioninventoryCronTask extends PluginFusioninventoryItem
{
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
   static $rightname = 'plugin_fusioninventory_crontask';

   public $itemtype = 'PluginFusioninventoryCronTask';

   /**
    * Get the type of the itemtype
    *
    * @return string
    */
   static function getType() {
      return "PluginFusioninventoryCronTask";
   }

   /**
    * Get name of this type by language of the user connected
    *
    * @param integer $nb number of elements
    * @return string name of this type
    */
   static function getTypeName($nb = 0) {
      return __('Cron tasks', 'fusioninventory');
   }

   /**
    * Get menu name
    *
    * @return string
    */
   static function getMenuName() {
      return self::getTypeName();
   }

   /**
    * Get content menu breadcrumb
    *
    * @return array
    */
   static function getMenuContent() {
      $menu = [];
      if (Session::haveRight(static::$rightname, READ)) {
         $menu['title'] = self::getTypeName();
         $menu['page'] = self::getSearchURL(false);
         $menu['links']['search'] = self::getSearchURL(false);
      }
      return $menu;
   }

   /**
    * Get the tab name used for item
    *
    * @param CommonGLPI $item the item object
    * @param integer $withtemplate 1 if is a template form
    * @return string name of the tab
    */
   function getTabNameForItem(CommonGLPI $item, $withtemplate = 0) {
      if ($this->canView()) {
         $cnt = PluginFusioninventoryCrontask::countForItem($item);
         return self::createTabEntry(__('Cron tasks', 'fusioninventory'), $cnt);
      }
      return '';
   }

   /**
    * Count number of elements
    *
    * @param object $item
    * @return integer
    */
   static function countForItem(CommonDBTM $item) {
      return countElementsInTable('glpi_plugin_fusioninventory_crontasks',
         [
            'computers_id' => $item->getField('id')
         ]);
   }

   /**
    * Display the content of the tab
    *
    * @param object $item
    * @param integer $tabnum number of the tab to display
    * @param integer $withtemplate 1 if is a template form
    * @return boolean
    */
   static function displayTabContentForItem($item, $tabnum = 1, $withtemplate = 0) {
      self::showForCronTask($item, $withtemplate);
      return true;
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
         'name' => __('CronTask', 'fusioninventory')
      ];

      $tab[] = [
         'id' => '1',
         'table' => $this->getTable(),
         'field' => 'name',
         'name' => __('Name'),
         'datatype' => 'itemlink',
         'autocomplete' => true,
      ];

      $tab[] = [
         'id' => '2',
         'table' => $this->getTable(),
         'field' => 'comment',
         'name' => __('Comments'),
         'datatype' => 'text',
      ];

      $tab[] = [
         'id' => '3',
         'table' => $this->getTable(),
         'field' => 'status',
         'name' => __('Enabled'),
         'datatype' => 'bool',
      ];

      $tab[] = [
         'id' => '4',
         'table' => $this->getTable(),
         'field' => 'user_execution',
         'name' => __('Execution user'),
         'datatype' => 'text',
      ];

      $tab[] = [
         'id' => '5',
         'table' => $this->getTable(),
         'field' => 'command',
         'name' => __('Command'),
         'datatype' => 'text',
      ];

      $tab[] = [
         'id' => '6',
         'table' => $this->getTable(),
         'field' => 'execution_minute',
         'name' => __('Minute'),
         'datatype' => 'text',
      ];

      $tab[] = [
         'id' => '7',
         'table' => $this->getTable(),
         'field' => 'execution_hour',
         'name' => __('Hour'),
         'datatype' => 'text',
      ];

      $tab[] = [
         'id' => '8',
         'table' => $this->getTable(),
         'field' => 'execution_day',
         'name' => __('Day'),
         'datatype' => 'text',
      ];

      $tab[] = [
         'id' => '9',
         'table' => $this->getTable(),
         'field' => 'execution_month',
         'name' => __('Month'),
         'datatype' => 'text',
      ];

      $tab[] = [
         'id' => '10',
         'table' => $this->getTable(),
         'field' => 'execution_weekday',
         'name' => __('Week day'),
         'datatype' => 'text',
      ];

      $tab[] = [
         'id' => '11',
         'table' => $this->getTable(),
         'field' => 'execution_year',
         'name' => __('Year'),
         'datatype' => 'text',
      ];

      $tab[] = [
         'id' => '12',
         'table' => $this->getTable(),
         'field' => 'storage',
         'name' => __('Storage'),
         'datatype' => 'text',
      ];

      $tab[] = [
         'id' => '13',
         'table' => $this->getTable(),
         'field' => 'user_storage',
         'name' => __('Storage user'),
         'datatype' => 'text',
      ];

      $tab[] = [
         'id' => '14',
         'table' => 'glpi_computers',
         'field' => 'name',
         'name' => __('Computer'),
         'datatype' => 'dropdown',
      ];

      $tab[] = [
         'id'        => '15',
         'table'     => $this->getTable(),
         'field'     => 'creation_date',
         'name'      => __('Creation date'),
         'datatype'  => 'datetime',
      ];

      return $tab;
   }

   /**
    * Delete cron tasks of computer
    *
    * @param integer $computers_id
    */
   static function cleanComputer($computers_id) {
      $crontask = new self();
      $crontask->deleteByCriteria(['computers_id' => $computers_id], true, false);
   }

   /**
    * Print the crontasks
    *
    * @param $item                  CommonDBTM object
    * @param $withtemplate          boolean  Template or basic item (default 0)
    *
    * @return false
    */
   static function showForCronTask(CommonDBTM $item, $withtemplate = 0) {
      global $DB;

      $ID = $item->fields['id'];
      $itemtype = $item->getType();

      if (!$item->getFromDB($ID)
         || !$item->can($ID, READ)) {
         return false;
      }

      $canedit = $item->canEdit($ID);
      if ($canedit
         && !(!empty($withtemplate) && ($withtemplate == 2))) {
         echo "<div class='center firstbloc'>" .
            "<a class='vsubmit' href='" . self::getFormURL() . "?itemtype=$itemtype&items_id=$ID&amp;withtemplate=" .
            $withtemplate . "'>";
         echo __('Add a cron task');
         echo "</a></div>\n";
      }

      echo "<div class='center'>";
      $iterator = $DB->request([
         'SELECT' => [
            self::getTable() . '.*'
         ],
         'FROM' => self::getTable(),
         'WHERE' => [
            'computers_id' => $_GET['id']
         ]
      ]);

      echo "<table class='tab_cadre_fixehov'>";
      $colspan = 7;
      if (Plugin::haveImport()) {
         $colspan++;
      }
      echo "<tr class='noHover'><th colspan='$colspan'>" . self::getTypeName(count($iterator)) .
         "</th></tr>";

      if (count($iterator)) {

         $header = "<tr><th>" . __('Name') . "</th>";
         $header .= "<th>" . __('Enabled') . "</th>";
         $header .= "<th>" . __('Execution user') . "</th>";
         $header .= "<th>" . __('Minute') . "</th>";
         $header .= "<th>" . __('Hour') . "</th>";
         $header .= "<th>" . __('Day') . "</th>";
         $header .= "<th>" . __('Month') . "</th>";
         $header .= "<th>" . __('Year') . "</th>";
         $header .= "<th>" . __('Week day') . "</th>";
         $header .= "<th>" . __('Storage') . "</th>";
         $header .= "<th>". __('Creation date')."</th>";
         $header .= "</tr>";
         echo $header;

         $crontask = new self();
         foreach ($iterator as $data) {
            $crontask->getFromResultSet($data);
            echo "<tr class='tab_bg_2" . (isset($data['is_deleted']) && $data['is_deleted'] ? " tab_bg_2_2'" : "'") . "'>";
            echo "<td>" . $crontask->getLink() . "</td><td>";
            echo Dropdown::getYesNo($data['status']);
            echo "</td><td>" . $data['user_execution'] . "</td>";
            echo "<td>" . $data['execution_minute'] . "</td>";
            echo "<td>" . $data['execution_hour'] . "</td>";
            echo "<td>" . $data['execution_day'] . "</td>";
            echo "<td>" . $data['execution_month'] . "</td>";
            echo "<td>" . $data['execution_year'] . "</td>";
            echo "<td>" . $data['execution_weekday'] . "</td>";
            echo "<td>" . $data['storage'] . "</td>";
            echo "<td>" . $data['creation_date']."</td>";
            echo "</tr>";
            Session::addToNavigateListItems(__CLASS__, $data['id']);
         }
         echo $header;
      } else {
         echo "<tr class='tab_bg_2'><th colspan='$colspan'>" . __('No item found') . "</th></tr>";
      }

      echo "</table>";
      echo "</div>";
   }

   /**
    * Display form
    *
    * @param integer $id
    * @param array $options
    * @return true
    */
   function showForm($id, $options = []) {

      $this->initForm($id, $options);
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td>" . __('Name') . "</td>";
      echo "<td>";
      echo Html::input("name", ['size' => 66, 'value' => $this->fields['name']]);
      echo "</td><td>" . __('Enabled') . "</td>";
      echo "<td>";
      Dropdown::showYesNo("status", $this->fields["status"]);
      echo "</td></tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>" . __('Command') . "</td>";
      echo "<td>";
      echo "<textarea cols='64' rows='4' name='command' >" . $this->fields["command"] . "</textarea>";
      echo "</td></tr><tr></tr><td>" . __('Execution user') . "</td>";
      echo "<td>";
      echo Html::input("user_execution", ['size' => 66, 'value' => $this->fields['user_execution']]);
      echo "</td></tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>" . __('Storage') . "</td>";
      echo "<td>";
      echo Html::input("storage", ['size' => 66, 'value' => $this->fields['storage']]);
      echo "</td></tr>";
      echo "<tr><td>" . __('Storage user') . "</td>";
      echo "<td>";
      echo Html::input("user_storage", ['size' => 66, 'value' => $this->fields['user_storage']]);
      echo "</td></tr>";

      echo "<tr>";
      echo "<td colspan='4'>";
      echo "<table>";
         echo "<tr>";
         echo "<td>".__('Minute')."</td>";
         echo "<td>".__('Hour')."</td>";
         echo "<td>".__('Day')."</td>";
         echo "<td>".__('Month')."</td>";
         echo "<td>".__('Year')."</td>";
         echo "<td>".__('Week day')."</td>";
         echo "</tr>";

         echo "<tr>";
         echo "<td>".Html::input("execution_minute", ['option' => 'size="8"','display' => false, 'value' => $this->fields['execution_minute']])."</td>";
         echo "<td>".Html::input("execution_hour", ['option' => 'size="8"','display' => false, 'value' => $this->fields['execution_hour']])."</td>";
         echo "<td>".Html::input("execution_day", ['option' => 'size="8"','display' => false, 'value' => $this->fields['execution_day']])."</td>";
         echo "<td>".Html::input("execution_month", ['option' => 'size="8"','display' => false, 'value' => $this->fields['execution_month']])."</td>";
         echo "<td>".Html::input("execution_year", ['option' => 'size="8"','display' => false, 'value' => $this->fields['execution_year']])."</td>";
         echo "<td>".Html::input("execution_weekday", ['option' => 'size="8"','display' => false, 'value' => $this->fields['execution_weekday']])."</td>";
         echo "</tr>";
      echo "</table>";
      echo "</td>";
      echo "</tr>";

      echo "<tr><td>" . __('Creation date') . "</td>";
      echo "<td>";
      Html::showDateTimeField("creation_date", ['value' => $this->fields['creation_date'], 'timestep' => 1]);
      echo "</td></tr>";
      echo "</td></tr>";

      echo "<tr><td>" . __('Comments') . "</td>";
      echo "<td class='middle'>";
      echo "<textarea cols='50' rows='4' name='comment' >" . $this->fields["comment"] . "</textarea>";
      echo "</td>";
      echo "<tr><td>" . __('Computer') . "</td>";
      echo "<td>";
      Computer::dropdown(['value' => $this->fields["computers_id"]]);
      echo "</td></tr>";

      $options['canedit'] = true;
      $this->showFormButtons($options);

      return true;
   }
}
