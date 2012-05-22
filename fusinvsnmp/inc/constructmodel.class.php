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

class PluginFusinvsnmpConstructmodel extends CommonDBTM {
   private $fp;

   function connect() {
      $this->fp = @fsockopen("127.0.0.1", "9000");
      if ($this->fp) {
         return true;
      }
      echo "<table class='tab_cadre_fixe'>";

      echo "<tr class='tab_bg_1'>";
      echo "<th>";
      echo "Error";
      echo "</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>";
      echo "The server is not available !";
      echo "</td>";
      echo "</tr>";

      echo "</table>";
      return false;
   }
   
   
   
   function closeConnection() {
      fclose($this->fp);
   }
   
   
   
   function showAuth() {
                 
      $ret = fgets ($this->fp, 1024);
      if ($ret == "Hello\n") {

         $auth = array();
         $auth["auth"] = array(  "login" => "ddurieux",
                                 "password" => "touch",
                                 "key" => "3167429");
         $buffer = json_encode($auth);
         $buffer .= "\n";
         fputs ($this->fp, $buffer);
         $ret = fgets ($this->fp, 1024);
         if ($ret == "Authentication error\n") {
            echo "<table class='tab_cadre_fixe'>";

            echo "<tr class='tab_bg_1'>";
            echo "<th>";
            echo "Error";
            echo "</th>";
            echo "</tr>";

            echo "<tr class='tab_bg_1'>";
            echo "<td align='center'>";
            echo "Authentication is not right, verify login and password !";
            echo "</td>";
            echo "</tr>";

            echo "</table>";
            return false;
         }
         return true;
      }
   }
   
   
   
   function menu() {
      echo "<table class='tab_cadre_fixe'>";

      echo "<tr class='tab_bg_1'>";
      echo "<th>";
      echo "Menu";
      echo "</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>";
      echo "<a href='".$this->getSearchURL()."?action=checksysdescr'>Check a sysdescr</a>";
      echo "</td>";
      echo "</tr>";
      
      echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>";
      echo "<a href=''>Get All SNMP models (devel)</a>";
      echo "</td>";
      echo "</tr>";
      
      echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>";
      echo "<a href=''>Get All SNMP models (stable)</a>";
      echo "</td>";
      echo "</tr>";

      echo "</table>";
   }
   
   
   function showFormDefineSysdescr() {
      global $LANG,$CFG_GLPI;
      
      echo "<form name='form' method='post' action='".$this->getSearchURL()."'>";
      echo "<table class='tab_cadre_fixe'>";

      echo "<tr class='tab_bg_1'>";
      echo "<th colspan='2'>";
      echo "Sysdescr";
      echo "</th>";
      echo "</tr>";
      
      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo "Command to get the sysdescr";
      echo "</td>";
      echo "<td>";
      echo "snmpwalk -v [version] -c [community] [IP] sysdescr";
      echo "</td>";
      echo "</tr>";
      
      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo "Tags";
      echo "</td>";
      echo "<td>";
      echo "[version] = 1, 2c or 3<br/>
         [community] = community name<br/>
         [IP] = IP of the device to query";
      echo "</td>";
      echo "</tr>";
      
      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo "Itemtype";
      echo "</td>";
      echo "<td>";
      Dropdown::showItemType();
      echo "</td>";
      echo "</tr>";
      
      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo "Sysdescr";
      echo "</td>";
      echo "<td>";
      echo "<textarea name='sysdescr'  cols='100' rows='4' /></textarea>";
      echo "</td>";
      echo "</tr>";
      
      echo "<tr class='tab_bg_2'>";
      echo "<td align='center' colspan='2'>";
      echo "<input class='submit' type='submit' name='sendsnmpwalk'
                      value='" . $LANG['buttons'][26] . "'>";
      echo "</td>";
      echo "</tr>";
      
      echo "</table>";
      echo "</form>";      
   }
   
   
   
   function sendGetsysdescr($sysdescr, $itemtype) {
      $getsysdescr = array();
      $getsysdescr['getsysdescr'] = array(
         "sysdescr" => $sysdescr,
         "itemtype" => $itemtype);
      $buffer = json_encode($getsysdescr);
      $buffer .= "\n";
      fputs ($this->fp, $buffer);
      $ret = fgets ($this->fp, 1024);
      $data = json_decode($ret);
      if ($data->device->id == '0') {
         echo "<table class='tab_cadre_fixe'>";

         echo "<tr class='tab_bg_1 center'>";
         echo "<th colspan='2'>";
         echo "This device is not yet added";
         echo "</th>";
         echo "</tr>";
         
         echo "</table>";
         
         $this->showUploadSnmpwalk($sysdescr, $itemtype);
         // Upload snmpwalk
         // send to server (it add sysdescr and lock for this user)
         // server return oids, mapping, oids most used for this kind of device (check with sysdescr)
      } else if ($data->device->lock != '0') {
         echo "Somebody work now on this, retry in 1 hour...";
      } else {
         // Device exist, update it? get snmpmodels?
      }
   }
   
   
   
   function sendGetDevice($id) {
      $getDevice = array();
      $getDevice['getDevice'] = array(
         "id" => $id);
      $buffer = json_encode($getDevice);
      $buffer .= "\n";
      fputs ($this->fp, $buffer);
      $ret = fgets ($this->fp);
      return json_decode($ret);
   }
   
   
   
   function setLock($sysdescr, $itemtype) {
      $getsysdescr = array();
      $getsysdescr['setLock'] = array(
         "sysdescr" => $sysdescr,
         "itemtype" => $itemtype);
      $buffer = json_encode($getsysdescr);
      $buffer .= "\n";
      fputs ($this->fp, $buffer);
      $ret = fgets ($this->fp, 1024);
      return json_decode($ret);      
   }
   
   
   
   function showUploadSnmpwalk($sysdescr, $itemtype) {
      global $LANG;
      
      echo "<form method='post' name='' id=''  action='' enctype=\"multipart/form-data\">";
      echo "<table class='tab_cadre_fixe'>";

      echo "<tr class='tab_bg_3 center'>";
      echo "<th colspan='2'>";
      echo "Upload your SNMPWALK";
      echo "</th>";
      echo "</tr>";
      
      echo "<tr class='tab_bg_3 center'>";
      echo "<td colspan='2'>";
      echo "<i>IMPORTANT: This file keep in your GLPI server, and no data of this will be uploaded in central server</i>";
      echo "</td>";
      echo "</tr>";
      
      echo "<tr class='tab_bg_3'>";
      echo "<td>";
      echo "Command to create the snmpwalk";
      echo "</td>";
      echo "<td>";
      echo "snmpwalk -v [version] -c [community] [IP] .1 > file.log";
      echo "</td>";
      echo "</tr>";
      
      echo "<tr class='tab_bg_3'>";
      echo "<td>";
      echo "Tags";
      echo "</td>";
      echo "<td>";
      echo "[version] = 1, 2c or 3<br/>
         [community] = community name<br/>
         [IP] = IP of the device to query";
      echo "</td>";
      echo "</tr>";      

      echo "<tr class='tab_bg_3 center'>";
      echo "<td>";
      echo "Upload the file file.log&nbsp;:";
      echo "</td>";
      echo "<td>";
      echo "<input type='hidden' name='sysdescr' value='".$sysdescr."' />";
      echo "<input type='hidden' name='itemtype' value='".$itemtype."' />";
      echo "<input type='file' name='snmpwalkfile'/>";
      echo "</td>";
      echo "</tr>";
      
      echo "<tr class='tab_bg_3 center'>";
      echo "<td colspan='2'>";
      echo "<div align='center'><input type='submit' name='add' value=\"" . $LANG["buttons"][8] .
                 "\" class='submit' >";
      echo "</td>";
      echo "</tr>";
      
      echo "</table>";
      echo "</form>";
   }
   
   
   
   
}

?>
