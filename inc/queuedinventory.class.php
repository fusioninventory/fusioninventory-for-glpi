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
 * This file is used to manage the collect (registry, file, wmi) module of
 * the agents
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
 * @author    Stanislas Kita
 * @copyright Copyright (c) 2010-2020 FusionInventory team
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
 * Manage the collect information by the agent.
 */
class PluginFusioninventoryQueuedinventory extends PluginFusioninventoryCommonView {


   CONST STATUS_PENDING    = 0;
   CONST STATUS_PROCESSED  = 1;
   CONST STATUS_CANCELED   = 2;
   CONST STATUS_ERROR      = 3;

   /**
    * We activate the history.
    * @var boolean
    */
   public $dohistory = true;

   /**
    * The right name for this class
    * @var string
    */
   static $rightname = 'plugin_fusioninventory_queuedinventory';


   /**
    * Get name of this type by language of the user connected
    * @param integer $nb number of elements
    * @return string name of this type
    */
   static function getTypeName($nb = 0) {
      return __('Inventory queue', 'fusioninventory');
   }

   /**
    * Check if user can create a task
    * @return boolean
    */
   static function canCreate() {
      return false;
   }

   /**
    * Get search function for the class
    * @return array
    */
   function rawSearchOptions() {

      $tab = [];
      $tab[] = [
         'id' => 'common',
         'name' => __('Queued inventory')
      ];

      $tab[] = [
         'id'        => '1',
         'table'     => $this->getTable(),
         'field'     => 'name',
         'name'      => __('Name'),
         'datatype'  => 'itemlink',
      ];

      $tab[] = [
         'id'        => '2',
         'table'     => PluginFusioninventoryAgent::getTable(),
         'field'     => 'name',
         'linkfield' => 'plugin_fusioninventory_agents_id',
         'name'      => __('Agent', 'fusioninventory'),
         'datatype'  => 'itemlink',
      ];

      $tab[] = [
         'id'        => '3',
         'table'     => $this->getTable(),
         'field'     => 'date_creation',
         'name'      => __('Import date', 'fusioninventory'),
         'datatype'  => 'datetime',
      ];

      $tab[] = [
         'id'           => '4',
         'table'        => $this->getTable(),
         'field'        => 'inventory_status',
         'name'         => __('Status', 'fusioninventory'),
         'searchtype'   => ['equals', 'notequals'],
         'datatype'     => 'specific',
      ];

      $tab[] = [
         'id'              => '5',
         'table'           => $this->getTable(),
         'field'           => 'xml_content',
         'name'            => __('Content', 'fusioninventory'),
         'massiveaction'   => false,
      ];

      $tab[] = [
         'id'              => '6',
         'table'           => $this->getTable(),
         'field'           => 'error',
         'name'            => __('Error message', 'fusioninventory'),
         'massiveaction'   => false,
      ];

      return $tab;
   }

   /**
    * Display form for agent configuration
    *
    * @param integer $agents_id ID of the agent
    * @param array $options
    * @return boolean
    */
   function showForm($inventory_id, $options = []) {

      $this->initForm($inventory_id, $options);
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Name')." :</td>";
      echo "<td align='left'>";
      echo $this->getField('name');
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".PluginFusioninventoryAgent::getTypeName()." :</td>";
      echo "<td align='left'>";
      Dropdown::show('PluginFusioninventoryAgent',
         ['value' => $this->getField('plugin_fusioninventory_agents_id'),
         'display_emptychoice' => false]);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Status')." :</td>";
      echo "<td align='left'>";
      echo self::dropdownStatus(['value' => $this->getField('inventory_status')]);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Content')." :</td>";
      echo "<td align='left'>";
      echo "<textarea cols='100' rows='20' id='xml_content' name='xml_content' disabled>".
      $this->getField("xml_content");
      echo "</textarea>";
      echo "</td>";
      echo "</tr>";

      if ($this->getField('error') != '') {
         echo "<tr class='tab_bg_1'>";
         echo "<td>".__('Error')." :</td>";
         echo "<td align='left'>";
         echo "<textarea cols='100' rows='10' id='error' name='error' disabled>".
         $this->getField("error");
         echo "</textarea>";
         echo "</td>";
         echo "</tr>";
      }

      $this->showFormButtons($options);
      return true;
   }

   function getSpecificMassiveActions($checkitem = null) {
      $actions = parent::getSpecificMassiveActions($checkitem);
      $class        = __CLASS__;
      $action_key   = "import_inventory";
      $action_label = __('Import XML now', 'fusioninventory');
      $actions[$class.MassiveAction::CLASS_ACTION_SEPARATOR.$action_key] = $action_label;
      return $actions;
   }

   static function processMassiveActionsForOneItemtype(MassiveAction $ma, CommonDBTM $item,
                                                    array $ids) {
      switch ($ma->getAction()) {
         case 'import_inventory':
            foreach ($ids as $id) {
               if ($item->getFromDB($id)) {

                  if ($item->fields['inventory_status'] == self::STATUS_PENDING) {

                     $xml = @simplexml_load_string($item->fields['xml_content'], 'SimpleXMLElement', LIBXML_NOCDATA);
                     //if load form string failed, update row to set status as error
                     if (!$xml) {
                        $error = __("Can't load xml from raw", "fusioninventory");
                        $item->update([
                           "id"                 => $item->fields['id'],
                           "inventory_status"   => self::STATUS_ERROR,
                           'error'              => $error
                        ]);

                        $ma->itemDone($item->getType(), $id, MassiveAction::ACTION_KO);
                        $ma->addMessage($error);

                     } else {
                        $arrayinventory = PluginFusioninventoryFormatconvert::XMLtoArray($xml);
                        $communication = new PluginFusioninventoryCommunication();

                        $_SESSION['plugin_fusioninventory_agents_id'] = $item->fields['plugin_fusioninventory_agents_id'];
                        $result = $communication->import($arrayinventory, $xml, true);

                        //if import return false, update row to set status as error
                        if (!$result) {
                           $item->update([
                              "id"                 => $item->fields['id'],
                              "inventory_status"   => self::STATUS_ERROR,
                              'error'              => $communication->getMessage()
                           ]);
                           $ma->itemDone($item->getType(), $id, MassiveAction::ACTION_KO);
                           $ma->addMessage(__("Error when importing : ", "fusioninventory").$communication->getMessage());
                        } else {
                           //else update row as processed
                           $item->update([
                              "id"                 => $item->fields['id'],
                              "inventory_status"   => self::STATUS_PROCESSED,
                              "xml_content"        => ''
                           ]);
                           $ma->itemDone($item->getType(), $id, MassiveAction::ACTION_OK);
                        }
                     }
                     //if status cancel -> do not import
                  } else if ($item->fields['inventory_status'] == self::STATUS_CANCELED) {
                     $ma->itemDone($item->getType(), $id, MassiveAction::ACTION_KO);
                     $ma->addMessage(__("Inventory previously canceled ", "fusioninventory").$item->getLink());
                     //if already processed do not import
                  } else if ($item->fields['inventory_status'] == self::STATUS_PROCESSED) {
                     $ma->itemDone($item->getType(), $id, MassiveAction::ACTION_KO);
                     $ma->addMessage(__("Inventory already processed ", "fusioninventory").$item->getLink());
                  }
               } else {
                  $ma->itemDone($item->getType(), $id, MassiveAction::ACTION_KO);
                  $ma->addMessage(__("Can't load inventory from DB ", "fusioninventory"));
               }
            }
            return;
      }
      parent::processMassiveActionsForOneItemtype($ma, $item, $ids);
   }

   function defineTabs($options = []) {
      $ong = [];
      $this->addDefaultFormTab($ong)
         ->addStandardTab('Log', $ong, $options);
      return $ong;
   }

   /**
    * Get the action for agent action
    * @param integer $action
    * @return string
    */
   static function getStatus($mode) {
      switch ($mode) {
         case self::STATUS_PENDING:
            return __('Pending', 'fusioninventory');

         case self::STATUS_PROCESSED:
            return __('Processed', 'fusioninventory');

         case self::STATUS_CANCELED:
               return __('Cancel', 'fusioninventory');

         case self::STATUS_ERROR:
            return __('Error', 'fusioninventory');
      }
   }

   /**
    * Get a specific value to display
    * @param string $field
    * @param array $values
    * @param array $options
    * @return string
    */
   static function getSpecificValueToDisplay($field, $values, array $options = []) {
      if (!is_array($values)) {
         $values = [$field => $values];
      }

      if ($field == 'inventory_status') {
         return self::getStatus($values[$field]);
      }

      return parent::getSpecificValueToDisplay($field, $values, $options);
   }

   /**
    * Get specific value to select
    *
    * @param string $field
    * @param string $name
    * @param string|array $values
    * @param array $options
    * @return string
    */
   static function getSpecificValueToSelect($field, $name = '', $values = '', array $options = []) {
      if (!is_array($values)) {
         $values = [$field => $values];
      }

      $options['display'] = false;
      if ($field == 'inventory_status') {
         $options['value'] = $values[$field];
         $options['name'] = $name;
         return self::dropdownStatus($options);
      }

      return parent::getSpecificValueToSelect($field, $name, $values, $options);
   }

   /**
    * Display dropdown to select dynamic of status
    *
    * @param string $name
    * @param string $value
    * @return string
    */
   static function dropdownStatus($options = []) {
      $values = [ self::STATUS_PENDING    => self::getStatus(self::STATUS_PENDING),
                  self::STATUS_PROCESSED  => self::getStatus(self::STATUS_PROCESSED),
                  self::STATUS_CANCELED   => self::getStatus(self::STATUS_CANCELED),
                  self::STATUS_ERROR      => self::getStatus(self::STATUS_ERROR) ];
      $options['display'] = false;
      return Dropdown::showFromArray('inventory_status', $values, $options);
   }

   /**
    * Give cron information
    * @param $name : task's name
    * @return array of information
   **/
   static function cronInfo($name) {
      switch ($name) {
         case 'queuedinventory' :
            return ['description' => __('Manage inventories in queue'),
                        'parameter'   => __('Maximum inventories to send at once')];
      }
      return [];
   }

   /**
    * Cron action on notification queue: send notifications in queue
    * @param CommonDBTM $task for log (default NULL)
    * @return integer either 0 or 1
   **/
   static function cronQueuedInventory($task = null) {
      global $DB;
      $inventory = new PluginFusioninventoryQueuedinventory();

      $condition = ['FIELDS'  => '*',
                    'ORDER'   => 'date_creation ASC',
                    'inventory_status'  => self::STATUS_PENDING];

      //if limit defined, use it
      if ($task->fields['param'] > 0) {
         $condition['LIMIT'] = $task->fields['param'];
      }

      $iter = $DB->request(self::getTable(), $condition);
      while ($row = $iter->next()) {

         $xml = @simplexml_load_string($row['xml_content'], 'SimpleXMLElement', LIBXML_NOCDATA);
         //if load form string failed, update row to set status as error
         if (!$xml) {
            $error = __("Can't load xml from raw for agent_id ", "fusioninventory").$row;
            $inventory->update([
               "id"     => $row['id'],
               "inventory_status" => self::STATUS_ERROR,
               'error'  => $error
            ]);
         } else {
            $arrayinventory = PluginFusioninventoryFormatconvert::XMLtoArray($xml);
            $communication = new PluginFusioninventoryCommunication();

            $_SESSION['plugin_fusioninventory_agents_id'] = $row['plugin_fusioninventory_agents_id'];
            $result = $communication->import($arrayinventory, $xml, true);

            //if import return false, update row to set status as error
            if (!$result) {
               $inventory->update([
                  "id"     => $row['id'],
                  "inventory_status" => self::STATUS_ERROR,
                  'error'  => $communication->getMessage()
               ]);
            } else {
               //else update row as processed
               $inventory->update([
                  "id" => $row['id'],
                  "inventory_status" => self::STATUS_PROCESSED,
                  "xml_content" => ''
               ]);

               if (!is_null($task)) {
                  $task->addVolume(1);
               }
            }
         }
      }

      return true;
   }
}