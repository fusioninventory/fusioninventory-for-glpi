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
 * Common class to manage informations FI display on an asset
 */
class PluginFusioninventoryItem extends CommonDBTM {

   /**
    * The right name for this class
    *
    * @var string
    */
   static $rightname = '';

   /**
   * The itemtype we are processing
   */
   public $itemtype  = '';

   /**
   * Get automatic inventory info for a computer
   * @since 9.1+1.2
   * @param integer $items_id the item ID to look for
   * @return inventory computer infos or an empty array
   */
   function hasAutomaticInventory($items_id) {
      $fk     = getForeignKeyFieldForItemType($this->itemtype);
      $params = [$fk => $items_id];
      if ($this->getFromDBByCrit($params)) {
         return $this->fields;
      } else {
         return [];
      }
   }

   /**
   * Form to download the XML inventory file
   *
   * @param integer $items_id the item ID to look for
   */
   function showDownloadInventoryFile($items_id) {
      global $CFG_GLPI;
      $folder = substr($items_id, 0, -1);
      if (empty($folder)) {
         $folder = '0';
      }

      $file_found     = false;
      //Check if the file exists with the .xml extension (new format)
      $file           = PLUGIN_FUSIONINVENTORY_XML_DIR;
      $filename       = $items_id.'.xml';
      $file_shortname = strtolower($this->itemtype)."/".$folder."/".$filename;
      $file          .= $file_shortname;
      if (!file_exists($file)) {
         //The file doesn't exists, check without the extension (old format)
         $file           = PLUGIN_FUSIONINVENTORY_XML_DIR;
         $filename       = $items_id;
         $file_shortname = strtolower($this->itemtype)."/".$folder."/".$filename;
         $file          .= $file_shortname;
         if (file_exists($file)) {
            $file_found = true;
         }
      } else {
         $file_found = true;
      }
      if ($file_found) {
         echo "<table class='tab_cadre_fixe'>";
         echo "<tr class='tab_bg_1'>";
         echo "<th>";
         $url = Plugin::getWebDir('fusioninventory')."/front/send_inventory.php";
         $url.= "?itemtype=".get_class($this)
            ."&function=sendXML&items_id=".$file_shortname."&filename=".$filename;
         echo "<a href='$url' target='_blank'>";
         $message = __('Download inventory file', 'fusioninventory');
         echo "<img src=\"".$CFG_GLPI["root_doc"].
                 "/pics/icones/csv-dist.png\" alt='$message' title='$message'>";
         echo "&nbsp;$message</a>";
         echo "</th></tr></table>";
      }
   }

   /**
    * Display form
    *
    * @param CommonDBTM $item CommonDBTM instance
    * @param array $options optional parameters to be used for display purpose
    */
   function showForm(CommonDBTM $item, $options = []) {
      Session::checkRight($this::$rightname, READ);

      $fk     = getForeignKeyFieldForItemType($this->itemtype);
      $params = [$fk => $item->getID()];
      if (!$this->getFromDBByCrit($params)) {
         // Add in database if not exist
         $_SESSION['glpi_plugins_fusinvsnmp_table']
                = getTableForItemType($this->itemtype);
         $ID_tn = $this->add($params);
         $this->getFromDB($ID_tn);
      }

      // Form item informations

      echo "<div align='center'>";
      echo "<form method='post' name='snmp_form' id='snmp_form'
                 action=\"".$options['target']."\">";
      echo "<table class='tab_cadre' cellpadding='5' width='950'>";

      echo "<tr class='tab_bg_1'>";
      echo "<th colspan='4'>";
      echo __('SNMP information', 'fusioninventory');

      echo "</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>";
      echo __('Sysdescr', 'fusioninventory');

      echo "</td>";
      echo "<td>";
      echo "<textarea name='sysdescr' cols='45' rows='5'>";
      echo $this->fields['sysdescr'];
      echo "</textarea>";
      echo "</td><td>";

      echo "<table><tr>";
      echo "<td align='center'>";
      echo __('Last inventory', 'fusioninventory');
      echo "</td>";
      echo "<td>";
      echo Html::convDateTime(
              $this->fields['last_fusioninventory_update']);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "</td>";
      echo "<td align='center'>".__('SNMP authentication', 'fusioninventory')."</td>";
      echo "<td align='center'>";
      PluginFusioninventoryConfigSecurity::authDropdown(
              $this->fields["plugin_fusioninventory_configsecurities_id"]);
      echo "</td>";
      echo "</tr>";
      echo "</table></td></tr>";

      $this->addMoreInfos($options);

      echo "<tr class='tab_bg_2 center'>";
      echo "<td colspan='4'>";
      echo "<div align='center'>";
      echo Html::hidden('id', ['value' => $this->fields[$fk]]);
      echo Html::submit(__('Update'), ['name' => 'update']);
      echo "</td>";
      echo "</tr>";

      echo "</table>";
      Html::closeForm();
      echo "</div>";
   }

   /**
   * Add more info to the tab if needed
   * This method is used for child classes to add more specific infos
   * in the form
   * @since 9.2+2.0
   *
   * @param array $options display options
   */
   function addMoreInfos($options = []) {
   }

   /**
   * Get a FI class instance representing an itemtype
   * @since 9.2+2.0
   *
   * @param array $options display options
   */
   static function getFIItemClassInstance($itemtype) {
      switch ($itemtype) {
         case 'Computer':
            return new PluginFusioninventoryInventoryComputerComputer();

         case 'NetworkEquipment':
            return new PluginFusioninventoryNetworkEquipment();

         case 'Printer':
            return new PluginFusioninventoryPrinter();

         default:
            // Toolbox::logDebug("getFIItemClassInstance: there's no FI class for itemtype $itemtype");
            return false;
      }
   }

   /**
   * Computer uptime
   * @since 9.2+2.0
   *
   * @param string $uptime the uptime as read from the XML file
   * @return array an which contains values for day, hour, minute and second
   */
   public function computeUptime($uptime) {
      $day    = 0;
      $hour   = 0;
      $minute = 0;
      $sec    = 0;
      $ticks  = 0;
      if (strstr($uptime, "days")) {
         list($day, $hour, $minute, $sec, $ticks) = sscanf($uptime, "%d days, %d:%d:%d.%d");
      } else if (strstr($uptime, "hours")) {
         $day = 0;
         list($hour, $minute, $sec, $ticks) = sscanf($uptime, "%d hours, %d:%d.%d");
      } else if (strstr($uptime, "minutes")) {
         $day  = 0;
         $hour = 0;
         list($minute, $sec, $ticks) = sscanf($uptime, "%d minutes, %d.%d");
      } else if ($uptime == "0") {
         $day    = 0;
         $hour   = 0;
         $minute = 0;
         $sec    = 0;
      } else {
         list($hour, $minute, $sec, $ticks) = sscanf($uptime, "%d:%d:%d.%d");
         $day = 0;
      }
      return [
         'day'    => $day,
         'hour'   => $hour,
         'minute' => $minute,
         'second' => $sec
      ];
   }

   /**
   * Display uptime formatted in HTML
   * @since 9.2+2.0
   *
   * @param string $uptime the uptime as read from the XML file
   * @return string the uptime in a human readable format, formatted in HTML
   */
   public function displayUptimeAsString($uptime) {
      $uptime_values = $this->computeUptime($uptime);
      $output = "<b>".$uptime_values['day']."</b> "
         ._n('Day', 'Days', $uptime_values['day'])." ";
      $output.= "<b>".$uptime_values['hour']."</b> "
         ._n('Hour', 'Hours', $uptime_values['hour'])." ";
      $output.= "<b>".$uptime_values['minute']."</b> "
         ._n('Minute', 'Minutes', $uptime_values['minute'])." ";
      $output.=__('and');
      $output.= "<b>".$uptime_values['second']."</b> "
         .__('sec(s)', 'fusioninventory')." ";
      return $output;
   }
}
