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
 * This file is used to manage the printer extended information.
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
 * @author    Vincent Mazzoni
 * @author    David Durieux
 * @copyright Copyright (c) 2010-2016 FusionInventory team
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
 * Manage the printer extended information.
 */
class PluginFusioninventoryPrinter extends PluginFusioninventoryItem {

   /**
    * The right name for this class
    *
    * @var string
    */
   static $rightname = 'plugin_fusioninventory_printer';

   public $itemtype  = 'Printer';


   /**
    * Get name of this type by language of the user connected
    *
    * @param integer $nb number of elements
    * @return string name of this type
    */
   static function getTypeName($nb = 0) {
      return '';
   }


   /**
    * Get the type
    *
    * @return string
    */
   static function getType() {
      return 'Printer';
   }


   /**
    * Get the tab name used for item
    *
    * @param object $item the item object
    * @param integer $withtemplate 1 if is a template form
    * @return string name of the tab
    */
   function getTabNameForItem(CommonGLPI $item, $withtemplate = 0) {
      if ($this->canView()) {
         return self::createTabEntry(__('FusionInventory SNMP', 'fusioninventory'));
      }
      return '';
   }


   /**
    * Display the content of the tab
    *
    * @param object $item
    * @param integer $tabnum number of the tab to display
    * @param integer $withtemplate 1 if is a template form
    * @return boolean
    */
   static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withtemplate = 0) {
      $fi_path = Plugin::getWebDir('fusioninventory');

      if ($item->fields['id'] > 0) {
         $pfPrinter = new PluginFusioninventoryPrinter();
         $pfPrinter->showForm($item,
                     ['target' => $fi_path.'/front/printer_info.form.php']);
         echo '<div id="overDivYFix" STYLE="visibility:hidden">fusinvsnmp_1</div>';

         $pfPrinterCartridge = new PluginFusioninventoryPrinterCartridge();
         $pfPrinterCartridge->showForm($item,
                     ['target' => $fi_path.'/front/printer_info.form.php']);

         $pfPrinterLog = new PluginFusioninventoryPrinterLog();
         $pfPrinterLog->showGraph($item->getID(),
                     ['target' => $fi_path.'/front/printer_info.form.php']);
         return true;
      }
      return false;
   }


   /**
    * Update an existing printer with last_fusioninventory_update value
    */
   function updateDB() {
      parent::updateDB();
      // update last_fusioninventory_update even if no other update
      $this->setValue('last_fusioninventory_update', date("Y-m-d H:i:s"));
      $this->updateDB();
   }


   /**
    * Display extended printer information
    *
    * @param object $item
    */
   static function showInfo($item) {

      // Manage locks pictures
      PluginFusioninventoryLock::showLockIcon('Printer');

      $pfPrinter = new PluginFusioninventoryPrinter();
      $a_printerextend = current($pfPrinter->find(['printers_id' => $item->getID()], [], 1));
      if (empty($a_printerextend)) {
         return;
      }

      echo '<table class="tab_glpi" width="100%">';
      echo '<tr>';
      echo '<th colspan="2">'.__('FusionInventory', 'fusioninventory').'</th>';
      echo '</tr>';

      echo '<tr class="tab_bg_1">';
      echo '<td>';
      echo __('Last inventory', 'fusioninventory');
      echo '</td>';
      echo '<td>';
      echo Html::convDateTime($a_printerextend['last_fusioninventory_update']);
      echo '</td>';
      echo '</tr>';

      echo '<tr class="tab_bg_1">';
      echo '<td>';
      echo __('Type');
      echo '</td>';
      echo '<td>';
      echo "SNMP";
      echo '</td>';
      echo '</tr>';

      echo "</table>";
   }

}
