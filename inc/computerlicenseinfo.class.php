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
   static $rightname = 'license';


   /**
    * Get name of this type by language of the user connected
    *
    * @param integer $nb number of elements
    * @return string name of this type
    */
   static function getTypeName($nb = 0) {
      return __('FusionInventory', 'fusioninventory')
         .' - '._n('License', 'Licenses', $nb);
   }


   /**
    * Display form
    *
    * @global array $CFG_GLPI
    * @param integer $computers_id id of the computer
    * @return true
    */
   function showForm($computers_id) {
      global $CFG_GLPI, $DB;

      $iterator = $DB->request('glpi_plugin_fusioninventory_computerlicenseinfos',
                               ['computers_id' => $computers_id]);

      if ($iterator->numrows()) {

         echo '<div align="center">';
         echo "<form method='POST' action='".self::getFormURL()."'>";

         echo '<table class="tab_cadre_fixe">';
         echo '<tr>';
         echo '<th colspan="4">'.self::getTypeName($iterator->numrows()).'</th>';
         echo '</tr>';

         foreach ($iterator as $licenseInfo) {
            $is_linked = (!empty($licenseInfo['softwarelicenses_id']));

            echo "<tr class='tab_bg_1'>";
            echo "<td>".__('Name')."</td>";
            $license_options = '';
            if ($licenseInfo['is_update']
               || $licenseInfo['is_trial']
                  || $licenseInfo['is_oem']) {
               $options = [];
               $fields = ['is_update' => _sx('name', 'Update'),
                          'is_trial'  => __('Trial', 'fusioninventory'),
                          'is_oem'    => __('OEM', 'fusioninventory')];
               foreach ($fields as $field => $label) {
                  if ($licenseInfo[$field]) {
                     array_push($options, $label);
                  }
               }
               $license_options = __('Option', 'fusioninventory')
                  ."&nbsp;(".implode(', ', $options).")";
            }
            echo "<td>";
            if (!empty($licenseInfo['fullname'])) {
               echo $licenseInfo['fullname'];
            } else {
               echo $licenseInfo['name'];
            }
            echo $license_options."</td>";
            echo "<td>".__('Serial number')."</td>";
            echo "<td>".$licenseInfo['serial']."</td>";
            echo "</tr>";

            echo "<tr class='tab_bg_1'>";
            echo "<td>".__('Linked to')."</td>";
            echo "<td colspan='3'>";

            if ($is_linked) {
               $license = new SoftwareLicense();
               $license->getFromDB($licenseInfo['softwarelicenses_id']);

               echo Html::hidden('computers_id',
                                 ['value' => $licenseInfo['computers_id']]);
               echo $license->getLink(['comments'     => true,
                                       'completename' => true]);
               echo "&nbsp;";
               echo Html::submit(_sx('button', 'Dissociate'),
                                 ['name'  => "dissociate[".$licenseInfo['id']."]='1'",
                                  'image' => $CFG_GLPI['root_doc'].'/pics/delete.png',
                                  'class' => '',
                                 ]);
            } else {
               echo Html::hidden('computers_id',
                                 ['value' => $licenseInfo['computers_id']]);

               $rand                  = mt_rand();
               $pfComputerLicenseInfo = new self();

               $params = ['softwarelicenses_id' => $licenseInfo['softwarelicenses_id'],
                          'name'                => $licenseInfo['name'],
                          'fullname'            => $licenseInfo['fullname'],
                          'serial'              => $licenseInfo['serial'],
                          'licenseid'           => $licenseInfo['id']
                         ];
                $pfComputerLicenseInfo->dropdownSoftwareLicenses($params);
            }
            echo "</td>";
            echo "</tr>";
         }

         echo '<tr><th colspan="4"></th></tr>';
         echo '</table>';
         Html::closeForm();
         echo '</div>';
      }
      return true;
   }


   /**
    * Dropdown of software licenses found with information given
    *
    * @global object $DB
    * @param array $params
    */
   function dropdownSoftwareLicenses($params) {
      global $DB, $CFG_GLPI;

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
               OR `serial` = '".$params['serial']."')";

      $licenses = [];
      foreach ($DB->request($query) as $data) {
         $licenses[$data['lid']] = $data['sname']." (".$data['serial'].")";
      }

      if (!empty($licenses)) {
         Dropdown::showFromArray('softwarelicenses_id', $licenses,
                                 ['value' => $params['softwarelicenses_id']]);
         echo "&nbsp;";
         echo Html::submit(_sx('button', 'Associate'),
                           ['name'  => "associate[".$params['licenseid']."]='1'",
                            'image' => $CFG_GLPI['root_doc'].'/pics/add_dark.png',
                            'class' => ''
                           ]);
      } else {
         echo __("No item found");
      }

   }


   /**
    * Associate a license found on computer and a license managed in GLPI
    *
    * @param array $params
    */
   function associate($params) {

      if (isset($params['computers_id']) && is_array($params['associate'])) {
         $computer_slicense = new Item_SoftwareLicense();
         $pfLicenseInfo = new self();

         foreach ($params['associate'] as $key => $value) {
            $computer_slicense->add([
               'itemtype'              => 'Computer',
               'items_id'              => $params['computers_id'],
               'softwarelicenses_id'   => $params['softwarelicenses_id'],
               'is_deleted'            => 0,
               'is_dynamic'            => 1
            ]);

            $pfLicenseInfo->update([
                                    'id'                  => $key,
                                    'softwarelicenses_id' => $params['softwarelicenses_id']
            ]);
         }
      }
   }


   /**
    * Dissociate a license found on computer and a license managed in GLPI
    *
    * @param array $params
    */
   function dissociate($params) {

      if (isset($params['computers_id']) && is_array($params['dissociate'])) {
         $pfLicenseInfo     = new self();
         $computer_slicense = new Item_SoftwareLicense();

         foreach ($params['dissociate'] as $key => $value) {
            $pfLicenseInfo->getFromDB($key);
            $computer_slicense->deleteByCriteria([
               'itemtype'              => 'Computer',
               'items_id'              => $params['computers_id'],
               'softwarelicenses_id'   => $pfLicenseInfo->fields['softwarelicenses_id']], true);

            $pfLicenseInfo->update(['id'                  => $key,
                                    'softwarelicenses_id' => 0]);
         }
      }
   }


   /**
    * Delete all licenses linked to the computer (most cases when delete a
    * computer)
    *
    * @param integer $computers_id
    */
   static function cleanComputer($computers_id) {
      $license = new self();
      $license->deleteByCriteria(['computers_id' => $computers_id]);
   }


   /**
    * Delete all licenses linked to the computer (most cases when delete a
    * computer)
    *
    * @param integer $computers_id
    */
   static function cleanLicense(Item_SoftwareLicense $license) {
      $licenses = getAllDataFromTable('glpi_plugin_fusioninventory_computerlicenseinfos',
         [
            'softwarelicenses_id' => $license->fields['softwarelicenses_id'],
            'computers_id'        => $license->fields['computers_id'],
         ]);
      if (!empty($licenses)) {
         $lic = current($licenses);
         $pfLicenseInfo = new self();
         $pfLicenseInfo->update(['id' => $lic['id'], 'softwarelicenses_id' => 0]);
      }
   }


}

