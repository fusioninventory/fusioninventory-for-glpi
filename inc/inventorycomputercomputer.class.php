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
 * This file is used to manage the extended information of a computer.
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
 * Manage the extended information of a computer.
 */
class PluginFusioninventoryInventoryComputerComputer extends CommonDBTM {


   /**
    * The right name for this class
    *
    * @var string
    */
   static $rightname = 'computer';


   /**
    * Get name of this type by language of the user connected
    *
    * @param integer $nb number of elements
    * @return string name of this type
    */
   static function getTypeName($nb=0) {
      return "";
   }


   static function isAFusionInventoryDevice($item) {
      return $item->fields['is_dynamic'] == 1
         && countElementsInTable('glpi_plugin_fusioninventory_inventorycomputercomputers',
                                 "`computers_id`='".$item->getID()."'");
   }

   /**
    * Display information about computer (bios, last contact...)
    *
    * @global array $CFG_GLPI
    * @param object $item
    * @return true
    */
   static function showAgentInfo($item) {
      global $CFG_GLPI;

      $pfInventoryComputerComputer = new PluginFusioninventoryInventoryComputerComputer();
      $a_computerextend = current($pfInventoryComputerComputer->find(
                                              "`computers_id`='".$item->getID()."'",
                                              "", 1));
      if (empty($a_computerextend)) {
         return;
      }

      echo '<table class="tab_glpi" width="100%">';

      $pfAgent = new PluginFusioninventoryAgent();
      $pfAgent->showInfoForComputer($item->getID());

      if ($a_computerextend['operatingsystem_installationdate'] != '') {
         echo '<tr class="tab_bg_1">';
         echo "<td>".__('Operating system')." - ".__('Installation')." (".
                 strtolower(__('Date')).")</td>";
         echo '<td>'.Html::convDate($a_computerextend['operatingsystem_installationdate']).'</td>';
         echo '</tr>';
      }

      if ($a_computerextend['winowner'] != '') {
         echo '<tr class="tab_bg_1">';
         echo '<td>'.__('Owner', 'fusioninventory').'</td>';
         echo '<td>'.$a_computerextend['winowner'].'</td>';
         echo '</tr>';
      }

      if ($a_computerextend['wincompany'] != '') {
         echo '<tr class="tab_bg_1">';
         echo '<td>'.__('Company', 'fusioninventory').'</td>';
         echo '<td>'.$a_computerextend['wincompany'].'</td>';
         echo '</tr>';
      }
      return TRUE;
   }

   /**
   * Get automatic inventory info for a computer
   * @since 9.1+1.2
   * @param computers_id the computer ID to look for
   * @return inventory computer infos or an empty array
   */
   function hasAutomaticInventory($computers_id) {
      $a_computerextend = current($this->find("`computers_id`='$computers_id'",
                                              "", 1));
      if (empty($a_computerextend)) {
         return [];
      } else {
         return $a_computerextend;
      }
   }

   /**
    * Display information about computer (bios, last contact...)
    *
    * @global array $CFG_GLPI
    * @param object $item
    * @return true
    */
   static function showComputerInfo($item) {
      global $CFG_GLPI;

      if ($item->isNewID($item->getID())) {
         return true;
      }
      // Manage locks pictures
      PluginFusioninventoryLock::showLockIcon('Computer');

      $pfInventoryComputerComputer = new PluginFusioninventoryInventoryComputerComputer();

      if (!countElementsInTable('glpi_plugin_fusioninventory_agents',
                                "`computers_id`=".$item->getID())) {
         return true;
      }

      echo '<tr>';
      echo '<th colspan="4">'.__('FusionInventory', 'fusioninventory').'</th>';
      echo '</tr>';

      $pfAgent = new PluginFusioninventoryAgent();
      $pfAgent->showInfoForComputer($item->getID());

      //Check if an inventory has already been sent
      $a_computerextend = $pfInventoryComputerComputer->hasAutomaticInventory($item->getID());
      if (empty($a_computerextend)) {
         return true;
      }

      echo '<tr class="tab_bg_1">';
      echo '<td>';
      echo __('Last inventory', 'fusioninventory');
      echo '</td>';
      echo '<td>';
      echo Html::convDateTime($a_computerextend['last_fusioninventory_update']);
      echo '</td>';
      echo '</tr>';

      if ($a_computerextend['remote_addr'] != '') {
         echo '<tr class="tab_bg_1">';
         echo '<td>'.__('Public contact address', 'fusioninventory').'</td>';
         echo '<td>'.$a_computerextend['remote_addr'].'</td>';
         echo '</tr>';
      }

      //TODO : to delete in 9.2
      if ($a_computerextend['bios_date'] != '') {
         echo '<tr class="tab_bg_1">';
         echo '<td>'.__('BIOS date', 'fusioninventory').'</td>';
         echo '<td>'.Html::convDate($a_computerextend['bios_date']).'</td>';
         echo '</tr>';
      }

      if ($a_computerextend['bios_version'] != '') {
         echo '<tr class="tab_bg_1">';
         echo '<td>'.__('BIOS version', 'fusioninventory').'</td>';
         echo '<td>'.$a_computerextend['bios_version'].'</td>';
         echo '</tr>';
      }

      if ($a_computerextend['bios_manufacturers_id'] > 0) {
         echo '<tr class="tab_bg_1">';
         echo '<td>'.__('Manufacturer').'&nbsp;:</td>';
         echo '<td>';
         echo Dropdown::getDropdownName("glpi_manufacturers",
                                        $a_computerextend['bios_manufacturers_id']);
         echo '</td>';
         echo '</tr>';
      }
      //END TODO : to delete in 9.2

      // Display automatic entity transfer
      if (Session::isMultiEntitiesMode()) {
         echo '<tr class="tab_bg_1">';
         echo '<td>'.__('Automatic entity transfer', 'fusioninventory').'</td>';
         echo '<td>';
         $pfEntity = new PluginFusioninventoryEntity();
         if ($pfEntity->getValue('transfers_id_auto', $item->fields['entities_id']) == 0) {
            echo __('No, locked (by entity configuration)', 'fusioninventory');
         } else {
            if ($a_computerextend['is_entitylocked'] == 1) {
               echo __('No, locked manually', 'fusioninventory');
               echo " [ <a href='".$CFG_GLPI['root_doc']."/plugins/fusioninventory/front/computerentitylock.form.php?id=".
                     $a_computerextend['id']."&lock=0'>".__('Unlock it', 'fusioninventory')."</a> ]";
            } else {
               echo __('Yes');
               echo " [ <a href='".$CFG_GLPI['root_doc']."/plugins/fusioninventory/front/computerentitylock.form.php?id=".
                     $a_computerextend['id']."&lock=1'>".__('Lock it', 'fusioninventory')."</a> ]";
            }
         }
         echo '</td>';
         echo '</tr>';
      }

      $pfRemoteManagement = new PluginFusioninventoryComputerRemoteManagement();
      $pfRemoteManagement->showInformation($item->getID());
      //echo '</table>';
      return TRUE;
   }



   /**
    * Display a serialized inventory
    *
    * @global array $CFG_GLPI
    * @param integer $computers_id
    */
   function displaySerializedInventory($computers_id) {
      global $CFG_GLPI;

      $a_computerextend = current($this->find("`computers_id`='".$computers_id."'",
                                               "", 1));

      $this->getFromDB($a_computerextend['id']);

      $folder = substr($computers_id, 0, -1);
      if (empty($folder)) {
         $folder = '0';
      }

      if (empty($this->fields['serialized_inventory'])
              && !file_exists(GLPI_PLUGIN_DOC_DIR."/fusioninventory/xml/computer/".$folder."/".$computers_id)) {
         return;
      }

      $data = array();
      if (!empty($this->fields['serialized_inventory'])) {
         $data = unserialize(gzuncompress($this->fields['serialized_inventory']));
      }
      echo "<br/>";

      echo "<table class='tab_cadre_fixe'>";

      echo "<tr class='tab_bg_1'>";
      echo "<th colspan='2'>";
      echo __('Last inventory', 'fusioninventory');
      echo " (".Html::convDateTime($this->fields['last_fusioninventory_update']).")";
      echo "</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<th>";
      echo __('Download', 'fusioninventory');
      echo "</th>";
      echo "<th>";
      if (!empty($this->fields['serialized_inventory'])) {
         echo "<a href='".$CFG_GLPI['root_doc'].
              "/plugins/fusioninventory/front/send_inventory.php".
              "?itemtype=PluginFusioninventoryInventoryComputerComputer".
              "&function=sendSerializedInventory&items_id=".$a_computerextend['id'].
              "&filename=Computer-".$computers_id.".json'".
              "target='_blank'>".__('PHP Array', 'fusioninventory')."</a> ";
      }
      if (file_exists(GLPI_PLUGIN_DOC_DIR."/fusioninventory/xml/computer/".$folder."/".$computers_id)) {
         if (!empty($this->fields['serialized_inventory'])) {
            echo "/ ";
         }
         echo "<a href='".$CFG_GLPI['root_doc'].
        "/plugins/fusioninventory/front/send_inventory.php".
        "?itemtype=PluginFusioninventoryInventoryComputerComputer".
        "&function=sendXML&items_id=computer/".$folder."/".$computers_id.
        "&filename=Computer-".$computers_id.".xml'".
        "target='_blank'>XML</a>";
      }

      echo "</th>";
      echo "</tr>";

      if ($_SESSION['glpi_use_mode'] == Session::DEBUG_MODE) {
         PluginFusioninventoryToolbox::displaySerializedValues($data);
      }

      echo "</table>";
   }



   /**
    * Delete extended information of computer
    *
    * @param integer $computers_id
    */
   static function cleanComputer($computers_id) {
      $pfInventoryComputerComputer = new PluginFusioninventoryInventoryComputerComputer();
      $a_computerextend = current($pfInventoryComputerComputer->find(
                                              "`computers_id`='".$computers_id."'",
                                              "", 1));
      if (!empty($a_computerextend)) {
         $pfInventoryComputerComputer->delete($a_computerextend);
      }
   }



   /**
    * Get entity lock. If true, computer can't be transfered to another entity
    * by agent inventory (so in automatic)
    *
    * @param integer $computers_id
    * @return boolean
    */
   function getLock($computers_id) {

      $pfInventoryComputerComputer = new PluginFusioninventoryInventoryComputerComputer();
      $a_computerextend = current($pfInventoryComputerComputer->find(
                                              "`computers_id`='".$computers_id."'",
                                              "", 1));
      if (empty($a_computerextend)) {
         return FALSE;
      }
      return $a_computerextend['is_entitylocked'];
   }
}
