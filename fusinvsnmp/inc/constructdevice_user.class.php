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
   @author    David Durieux
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

class PluginFusinvsnmpConstructdevice_User extends CommonDBTM {

   function canCreate() {
      return PluginFusioninventoryProfile::haveRight("fusinvsnmp", "model", "w");
   }


   function canView() {
      return PluginFusioninventoryProfile::haveRight("fusinvsnmp", "model", "r");
   }

   

   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {
      global $LANG;

      if ($this->canView()) {
         return self::createTabEntry('SNMP model tool account');
      }
      return '';
   }

   
   
   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {

      if ($item->getID() > 0) {
         $pfConstructdevice_User = new self();
         $pfConstructdevice_User->showForm($item->getID());
      }
      return true;
   }
   

   
   function showForm($users_id, $options=array()) {
      
      $a_constructdeviceusers = current($this->find("`users_id`='".$users_id."'", '', 1));
      if (isset($a_constructdeviceusers['id'])) {
         $this->getFromDB($a_constructdeviceusers['id']);
      } else {
         $this->getEmpty();
      }
      
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td>Login&nbsp;:</td>";
      echo "<td align='center'>";
      echo "<input type='hidden' name='users_id' value='$users_id' />";
      echo "<input type='text' name='login' value='".$this->fields['login']."' />";
      echo "</td>";
      echo "<td>Password&nbsp;:</td>";
      echo "<td align='center'>";
      echo "<input type='password' name='password' value='".$this->fields['password']."' />";
      echo "</td>";
      echo "</tr>";
      
      echo "<tr class='tab_bg_1'>";
      echo "<td>Key&nbsp;:</td>";
      echo "<td align='center'>";
      echo "<input type='text' name='key' value='".$this->fields['key']."' />";
      echo "</td>";
      echo "<td colspan='2'></td>";
      echo "</tr>";
      
      $this->showFormButtons($options);

      return true;
   }
   
   
   
   static function getUserAccount($users_id) {
      $pfConstructdevice_User = new self();
      return current($pfConstructdevice_User->find("`users_id`='".$users_id."'", '', 1));
   }
}

?>