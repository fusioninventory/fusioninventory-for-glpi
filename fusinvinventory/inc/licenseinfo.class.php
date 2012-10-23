<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2012 by the FusionInventory Development Team.

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
   along with Behaviors. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    Gonéri Le Bouder
   @co-author 
   @copyright Copyright (c) 2010-2012 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2012
 
   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusinvinventoryLicenseInfo extends CommonDBTM {
   
   static function getTypeName() {
      global $LANG;

      return $LANG['plugin_fusinvinventory']['licenseinfo'][0];
   }

   function canCreate() {
      return Session::haveRight('computer', 'w');
   }


   function canView() {
      return Session::haveRight('computer', 'r');
   }

   function showForm($computers_id) {
      global $LANG;

      $pfLicenseInfo = new self();
      $a_licenseInfo = $pfLicenseInfo->find("`computers_id`='".$computers_id."'");

      if (count($a_licenseInfo)) {

         echo '<div align="center">';
         echo '<table class="tab_cadre_fixe" style="margin: 0; margin-top: 5px;">';
         echo '<tr>';
         echo '<th colspan="4">'.$LANG['plugin_fusinvinventory']['licenseinfo'][0].'</th>';
         echo '</tr>';

         foreach ($a_licenseInfo as $licenseInfo) {
            $licence_link = $licence_endlink = "";
            if (!empty($licenseInfo['softwarelicenses_id'])) {
               $licence_link = "<a href='".GLPI_ROOT."/front/softwarelicense.form.php?id=".
                  $licenseInfo['softwarelicenses_id']."'>";
               $licence_endlink = "</a>";
            }

            echo "<tr class='tab_bg_1'>";
            echo "<td>".$LANG['plugin_fusinvinventory']['licenseinfo'][1]."&nbsp;:</td>";
            echo "<td>$licence_link".$licenseInfo['name']."$licence_endlink</td>";
            echo "<td>".$LANG['plugin_fusinvinventory']['licenseinfo'][3]."&nbsp;:</td>";
            echo "<td>".$licenseInfo['key']."</td>";
            echo "</tr>";

            echo "<tr class='tab_bg_1'>";
            echo "<td>".$LANG['plugin_fusinvinventory']['licenseinfo'][2]."&nbsp;:</td>";
            echo "<td>$licence_link".$licenseInfo['fullname']."$licence_endlink</td>";
            echo "<td>".$LANG['plugin_fusinvinventory']['licenseinfo'][4]."&nbsp;:</td>";
            echo "<td>";
            //echo $licenseInfo['productid'];//TODO Complete thid field in sql schema
            echo "</td>";            
            echo "</tr>";

            if ($licenseInfo['is_update']||$licenseInfo['is_trial']||$licenseInfo['is_oem']) {
                $options = array();

                if ($licenseInfo['is_update'])
                   array_push($options, 'update');

                if ($licenseInfo['is_trial'])
                   array_push($options, 'trial');

                if ($licenseInfo['is_oem'])
                   array_push($options, 'OEM');

                echo '<tr class="tab_bg_1">';
                echo '<td>'.$LANG['plugin_fusinvinventory']['licenseinfo'][5].'&nbsp;:</td>';
                echo '<td>'.implode(', ', $options).'</td>';
                echo '</tr>';
            }

            if (empty($licenseInfo['softwarelicenses_id'])) {
               echo '<tr class="tab_bg_1">';
               echo "<td>".$LANG['log'][116]."&nbsp;:</td>";
               echo "<td colspan='3'>";
               echo "<form method='post' action='".GLPI_ROOT.
                        "/plugins/fusinvinventory/front/licenseinfo.form.php'>";
               echo "<input type='hidden' name='computers_id' value='$computers_id'>";
               echo "<input type='hidden' name='fusinvinventory_licenseinfos_id' value='".
                  $licenseInfo['id']."'>";
               echo "<input type='hidden' name='key' value='".$licenseInfo['key']."'>";
               $rand = mt_rand();
               Dropdown::show('Software', array(
                  'toupdate'    => array(
                     'value_fieldname' => "__VALUE__",
                     'to_update'       => "softwarelicenses_id_$rand",
                     'url'             => GLPI_ROOT.
                        "/plugins/fusinvinventory/ajax/dropdownsoftwarelicenses.php".
                           "?key=".$licenseInfo['key']
                  ),
                  'condition'   => "name LIKE '%".$licenseInfo['name']."%' ".
                     "OR name LIKE '%".$licenseInfo['fullname']."%'"
               ));
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
   }

   function dropdownSoftwareLicenses($options, $key = "") {
      global $LANG;

      if (!isset($options['__VALUE__']) || empty($options['__VALUE__'])) return;

      $softwarelicences = new SoftwareLicense;
      $licenses_found = $softwarelicences->find(
         "glpi_softwarelicenses.softwares_id = '".$options['__VALUE__']."'
         /*AND serial = ''*/");

      $licenses = array();
      foreach($licenses_found as $softwarelicenses_id => $license) {
         $licenses[$softwarelicenses_id] = $license['name']." (".$license['serial'].")";
      }

      Dropdown::showFromArray('softwarelicenses_id', $licenses);

      echo "&nbsp;<input type='submit' class='button' name='associate' value='".
         $LANG['buttons'][3]."'>";

   }


   function associate($options) {

      $computer_slicense = new Computer_SoftwareLicense;
      $computer_slicense->add(array(
         'computers_id'        => $options['computers_id'],
         'softwarelicenses_id' => $options['softwarelicenses_id']
      ));

      $pfLicenseInfo = new self;
      $pfLicenseInfo->update(array(
         'id'                  => $options['fusinvinventory_licenseinfos_id'], 
         'softwarelicenses_id' => $options['softwarelicenses_id']
      ));
   }

}

?>
