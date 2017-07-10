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
 * This file is used to manage the software licenses found on the computer.
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
 * @author    Gonéri Le Bouder
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
 * Manage the software licenses found on the computer.
 */
class PluginFusioninventoryComputerLicenseInfo extends CommonDBTM {


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
      return __('License');
   }



   /**
    * Get the tab name used for item
    *
    * @param object $item the item object
    * @param integer $withtemplate 1 if is a template form
    * @return string name of the tab
    */
   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {

      if ($item->getID() > 0) {
         if (get_class($item) == 'Computer') {
            if (countElementsInTable('glpi_plugin_fusioninventory_computerlicenseinfos',
                             "`computers_id`='".$item->getID()."'") > 0) {
               return __('Software licenses', 'fusioninventory');
            }
         }
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
   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {
      $pfComputerLicenseInfo = new PluginFusioninventoryComputerLicenseInfo();
      if (get_class($item) == 'Computer') {
         $pfComputerLicenseInfo->showForm($item->getID());
      }
      return TRUE;
   }



   /**
    * Display form
    *
    * @global array $CFG_GLPI
    * @param integer $computers_id id of the computer
    * @return true
    */
   function showForm($computers_id) {
      global $CFG_GLPI;

      $pfLicenseInfo = new self();
      $a_licenseInfo = $pfLicenseInfo->find("`computers_id`='".$computers_id."'");

      if (count($a_licenseInfo)) {

         echo '<div align="center">';
         echo '<table class="tab_cadre_fixe" style="margin: 5px 0 0;">';
         echo '<tr>';
         echo '<th colspan="4">'.__('License').'</th>';
         echo '</tr>';

         foreach ($a_licenseInfo as $licenseInfo) {
            $licence_link = $licence_endlink = "";
            if (!empty($licenseInfo['softwarelicenses_id'])) {
               $licence_link = "<a href='".$CFG_GLPI['root_doc']."/front/softwarelicense.form.php?id=".
                  $licenseInfo['softwarelicenses_id']."'>";
               $licence_endlink = "</a>";
               $licence_endlink .= "<form method='post' action='".$CFG_GLPI['root_doc'].
                        "/plugins/fusioninventory/front/licenseinfo.form.php'>";

               $licence_endlink .= "<input type='hidden' name='fusioninventory_licenseinfos_id' ".
                                      "value='".$licenseInfo['id']."' />";
               $licence_endlink .= "<input type='hidden' name='softwarelicenses_id' value='0' />";
               $licence_endlink .= "<input type='submit' class='submit' name='associate' ".
                                      "value='".__('Dissociate')."'>";
               $licence_endlink .= Html::closeForm(FALSE);
            }

            echo "<tr class='tab_bg_1'>";
            echo "<td>".__('Name')."&nbsp;:</td>";
            echo "<td>$licence_link".$licenseInfo['name']."$licence_endlink</td>";
            echo "<td>".__('Serial number')."&nbsp;:</td>";
            echo "<td>".$licenseInfo['serial']."</td>";
            echo "</tr>";

            echo "<tr class='tab_bg_1'>";
            echo "<td>".__('Full name', 'fusioninventory')."&nbsp;:</td>";
            echo "<td>$licence_link".$licenseInfo['fullname']."$licence_endlink</td>";
            echo '<td>'.__('Option', 'fusioninventory').'&nbsp;:</td>';
            echo "<td>";
            if ($licenseInfo['is_update']||$licenseInfo['is_trial']||$licenseInfo['is_oem']) {
                $options = array();

                if ($licenseInfo['is_update']) {
                   array_push($options, 'update');
                }

                if ($licenseInfo['is_trial']) {
                   array_push($options, 'trial');
                }

                if ($licenseInfo['is_oem']) {
                   array_push($options, 'OEM');
                }

                echo implode(', ', $options);
            }
            echo "</td>";
            echo "</tr>";

            if (empty($licenseInfo['softwarelicenses_id'])) {
               echo '<tr class="tab_bg_1">';
               echo "<td>".__('Union between computer and license', 'fusioninventory').
                        "&nbsp;:</td>";
               echo "<td colspan='3'>";
               echo "<form method='post' action='".$CFG_GLPI['root_doc'].
                        "/plugins/fusioninventory/front/licenseinfo.form.php'>";
               echo "<input type='hidden' name='computers_id' value='$computers_id'>";
               echo "<input type='hidden' name='fusioninventory_licenseinfos_id' value='".
                  $licenseInfo['id']."'>";
               echo "<input type='hidden' name='key' value='".$licenseInfo['serial']."'>";
               $rand = mt_rand();
               $params = array(
                     'softwarelicenses_id' => $licenseInfo['softwarelicenses_id'],
                     'name' => $licenseInfo['name'],
                     'fullname' => $licenseInfo['fullname'],
                     'serial' => $licenseInfo['serial']
                  );
                Ajax::updateItem(
                        "softwarelicenses_id_$rand",
                        $CFG_GLPI["root_doc"]."/plugins/fusioninventory/ajax/".
                           "dropdownsoftwarelicenses.php",
                        $params,
                        FALSE);
               echo "<span id='softwarelicenses_id_$rand'></span>";
               Html::closeForm();
               echo "</td>";
               echo "</tr>";
            }
            echo "<tr class='tab_bg_3'>";
            echo "<td colspan='4'></td>";
            echo "</tr>";
         }
         echo '</table>';
         echo '</div>';
      }
      return TRUE;
   }



   /**
    * Dropdown of software licenses found with information given
    *
    * @global object $DB
    * @param array $params
    */
   function dropdownSoftwareLicenses($params) {
      global $DB;

      $query = "SELECT `glpi_softwares`.`name` as sname,
                       `glpi_softwarelicenses`.`name` as lname,
                       `glpi_softwarelicenses`.`id` as lid,
                       `glpi_softwarelicenses`.`serial` FROM `glpi_softwarelicenses`
         LEFT JOIN `glpi_softwares`
            ON `glpi_softwarelicenses`.`softwares_id` = `glpi_softwares`.`id`
         WHERE ((`glpi_softwarelicenses`.`name` LIKE '%".$params['name']."%'
                  OR `glpi_softwarelicenses`.`name` LIKE '%".$params['fullname']."%'
                  OR `glpi_softwares`.`name` LIKE '%".$params['name']."%'
                  OR `glpi_softwares`.`name` LIKE '%".$params['fullname']."%')
               AND `serial` = '".$params['serial']."')
         OR (`glpi_softwarelicenses`.`name` = '".$params['serial']."'
               AND `serial` = '".$params['serial']."')";

      $licenses = array();
      $result=$DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         $licenses[$data['lid']] = $data['sname']." (".$data['serial'].")";
      }

      Dropdown::showFromArray('softwarelicenses_id',
                              $licenses,
                              array('value' => $params['softwarelicenses_id']));

      echo "&nbsp;<input type='submit' class='submit' name='associate' ".
              "value='".__('Associate')."'>";
   }



   /**
    * Associate a license found on computer and a license managed in GLPI
    *
    * @param array $params
    */
   function associate($params) {

      if (isset($params['computers_id'])) {
         $computer_slicense = new Computer_SoftwareLicense;
         $computer_slicense->add(array(
            'computers_id'        => $params['computers_id'],
            'softwarelicenses_id' => $params['softwarelicenses_id']
         ));
      }

      $pfLicenseInfo = new self;
      $pfLicenseInfo->update(array(
         'id'                  => $params['fusioninventory_licenseinfos_id'],
         'softwarelicenses_id' => $params['softwarelicenses_id']
      ));
   }



   /**
    * Delete all licenses linked to the computer (most cases when delete a
    * computer)
    *
    * @param integer $computers_id
    */
   static function cleanComputer($computers_id) {
      $license = new self();
      $license->deleteByCriteria(array('computers_id' => $computers_id));
   }
}

?>
