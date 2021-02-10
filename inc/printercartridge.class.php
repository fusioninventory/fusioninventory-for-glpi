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
 * This file is used to manage the printer cartridge filled by inventory
 * like type, state of ink in cartridge, number pages...
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
 * Manage the printer cartridge filled by inventory like type, state of
 * ink in cartridge, number pages...
 */
class PluginFusioninventoryPrinterCartridge extends CommonDBTM {


   /**
    * Display form
    *
    * @global object $DB
    * @param object $item Printer instance
    * @param array $options
    * @return true
    */
   function showForm(Printer $item, $options = []) {
      global $DB;

      // ** Get link OID fields
      $mapping_name=[];

      $id = $item->getID();
      $a_cartridges = $this->find(['printers_id' => $id]);

      $printer = new Printer();
      $printer->getFromDB($id);

      $query_cartridges = "SELECT `id` FROM `glpi_cartridgeitems`
                           WHERE `id` NOT IN (SELECT `c`.`cartridgeitems_id`
                                              FROM `glpi_cartridgeitems_printermodels` AS c, `glpi_printers` AS p
                                              WHERE `c`.`printermodels_id`=`p`.`printermodels_id` and `p`.`id`='$id')";

      $result_cartridges = $DB->query($query_cartridges);
      $exclude_cartridges = [];

      if ($result_cartridges !== false) {
         while ($cartridge = $DB->fetchArray($result_cartridges)) {
            $exclude_cartridges[] = $cartridge['id'];
         }
      }
      echo "<div align='center'>";
      echo "<table class='tab_cadre' cellpadding='5' width='950'>";
      echo "<tr class='tab_bg_1'>";
      echo "<th align='center' colspan='3'>";
      echo __('Cartridge(s)', 'fusioninventory');

      echo "</th>";
      echo "</tr>";

      asort($mapping_name);
      $mapping = new PluginFusioninventoryMapping();
      foreach ($a_cartridges as $a_cartridge) {
         echo "<tr class='tab_bg_1'>";
         echo "<td align='center'>";
         $mapping->getFromDB($a_cartridge['plugin_fusioninventory_mappings_id']);
         echo $mapping->getTranslation($mapping->fields);
         echo " : ";
         echo "</td>";
         echo "<td align='center'>";
         echo "<form method='post' action=\"".$options['target']."\">";
         Dropdown::show('CartridgeItem', ['name'     => 'cartridges_id',
                                               'value'    => $a_cartridge['cartridges_id'],
                                               'comments' => false,
                                               'entity'   => $printer->fields['entities_id'],
                                               'entity_sons' => $this->isRecursive(),
                                               'used'     => $exclude_cartridges]);

         echo "&nbsp;<input type='hidden' name='id' value='".$a_cartridge["id"]."'/>";
         echo "<input type='submit' name='update_cartridge' value=\"".__('Update')."\" class='submit'>";
         Html::closeForm();

         echo "</td>";
         echo "<td align='center'>";
         if ($a_cartridge['state'] == 100000) {
            echo __('OK');
         } else if ($a_cartridge['state'] < 0) {
            $a_cartridge['state'] = $a_cartridge['state'] * -1;
            echo $a_cartridge['state'];
            echo ' '.__('remaining pages', 'fusioninventory');
         } else if ($mapping->fields['name'] == 'paperrollinches') {
            echo $a_cartridge['state']." inches";
         } else if ($mapping->fields['name'] == 'paperrollcentimeters') {
            echo $a_cartridge['state']." centimeters";
         } else {
            PluginFusioninventoryDisplay::bar($a_cartridge['state']);
         }
         echo "</td>";
         echo "</tr>";
      }
      echo "</table>";
      echo "</div>";
      return true;
   }
}
