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
      $this->fp = @fsockopen("93.93.45.69", "9000");
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
                 
      $ret = fgets ($this->fp, 102400);
      if ($ret == "Hello\n") {

         $auth = array();
         $auth["auth"] = array(  "login" => "ddurieux",
                                 "password" => "touch",
                                 "key" => "3167429");
         $buffer = json_encode($auth);
         $buffer .= "\n";
         fputs ($this->fp, $buffer);
         $ret = fgets ($this->fp, 102400);
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
      return false;
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
      
//      echo "<tr class='tab_bg_1'>";
//      echo "<td align='center'>";
//      echo "<a href=''>Get All SNMP models (devel)</a>";
//      echo "</td>";
//      echo "</tr>";
//      
//      echo "<tr class='tab_bg_1'>";
//      echo "<td align='center'>";
//      echo "<a href=''>Get All SNMP models (stable)</a>";
//      echo "</td>";
//      echo "</tr>";

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
      global $CFG_GLPI;
      
      $getsysdescr = array();
      $getsysdescr['getsysdescr'] = array(
         "sysdescr" => $sysdescr,
         "itemtype" => $itemtype);
      
      $_SESSION['plugin_fusioninventory_itemtype'] = $itemtype;
      $buffer = json_encode($getsysdescr);
      $buffer .= "\n";
      fputs ($this->fp, $buffer);
      $ret = fgets ($this->fp, 102400);
      $data = json_decode($ret);
      $_SESSION['plugin_fusioninventory_sysdescr'] = $data->device->sysdescr;
      echo  "<table width='950' align='center'>
         <tr>
         <td>
         <a href='".$CFG_GLPI['root_doc']."/plugins/fusinvsnmp/front/constructmodel.php?reset=reset'>Revenir au menu principal</a>
         </td>
         </tr>
         </table>";
      $a_lock = explode("-", $data->device->lock);
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
      } else if ($data->device->lock != '0'
              AND $a_lock[0] != 'ddurieux') {
         echo "<table class='tab_cadre_fixe'>";
         echo "<tr class='tab_bg_1 center'>";
         echo "<th>";
         echo "<br/>Somebody work now on this, retry in 1 hour...<br/><br/>";
         echo "</th>";
         echo "</tr>";
         echo "</table>";
      } else {
         // Device exist, update it? get snmpmodels?
         echo "<table class='tab_cadre_fixe'>";

         echo "<tr class='tab_bg_1 center'>";
         echo "<th colspan='2' width='50%'>";
         echo "This device exist";
         echo "</th>";
         echo "<th colspan='2'>";
         echo "<a href='".$CFG_GLPI['root_doc']."/plugins/fusinvsnmp/front/constructmodel.php'>Edit oids</a>";
         echo "&nbsp; &nbsp; &nbsp; &nbsp; | &nbsp; &nbsp; &nbsp; &nbsp;";
         echo "<a href='".$CFG_GLPI['root_doc']."/plugins/fusinvsnmp/front/constructsendmodel.php?id=".$data->device->id."' target='_blank'>Get SNMP model</a>";
         echo "</th>";
         echo "</tr>";
         
         echo "<tr class='tab_bg_1 center'>";
         echo "<td>";
         echo "Sysdescr :";
         echo "</td>";
         echo "<td>";
         echo $data->device->sysdescr;
         echo "</td>";
         
         echo "<td>";
         echo "<strong>Released :</strong>";
         echo "</td>";
         echo "<td><strong>";
         echo Dropdown::getYesNo($data->device->released);
         echo "</strong></td>";
         echo "</tr>";
         
         echo "<tr class='tab_bg_1 center'>";
         echo "<td>";
         echo "Itemtype :";
         echo "</td>";
         echo "<td>";
         echo $data->device->itemtype;
         echo "</td>";
         
         echo "<td>";
         echo "Have serial number :";
         echo "</td>";
         echo "<td>";
         echo Dropdown::getYesNo($data->device->have_serialnumber);
         echo "</td>";
         echo "</tr>";
         
         echo "<tr class='tab_bg_1 center'>";
         echo "<td>";
         echo "Manufacturer :";
         echo "</td>";
         echo "<td>";
         echo $data->device->manufacturers_id;
         echo "</td>";

         echo "<td>";
         echo "Have network ports :";
         echo "</td>";
         echo "<td>";
         echo Dropdown::getYesNo($data->device->have_ports);
         echo "</td>";
         echo "</tr>";
         
         echo "<tr class='tab_bg_1 center'>";
         echo "<td>";
         echo "Firmware :";
         echo "</td>";
         echo "<td>";
         echo $data->device->firmwares_id;
         echo "</td>";
         
         echo "<td>";
         echo "Have network ports connections :";
         echo "</td>";
         echo "<td>";
         echo Dropdown::getYesNo($data->device->have_portsconnections);
         echo "</td>";
         echo "</td>";
         echo "</tr>";
         
         echo "<tr class='tab_bg_1 center'>";
         echo "<td>";
         echo "Model :";
         echo "</td>";
         echo "<td>";
         if ($data->device->itemtype == "NetworkEquipment") {
            echo $data->device->networkmodels_id;
         } else if ($data->device->itemtype == "Printer") {
            echo $data->device->printermodels_id;
         }
         echo "</td>";
         
         echo "<td>";
         echo "Have Vlan :";
         echo "</td>";
         echo "<td>";
         echo Dropdown::getYesNo($data->device->have_vlan);
         echo "</td>";
         echo "</tr>";
         
         echo "<tr class='tab_bg_1 center'>";
         echo "<td>";
         echo "</td>";
         echo "<td>";
         echo "</td>";
         
         echo "<td>";
         echo "Have network ports trunk/tagged :";
         echo "</td>";
         echo "<td>";
         echo Dropdown::getYesNo($data->device->have_trunk);
         echo "</td>";
         echo "</tr>";       
         
         
         echo "</table><br/>";
         
         echo "<table class='tab_cadre' width='900'>";

         echo "<tr class='tab_bg_1 center'>";
         echo "<th colspan='5'>";
         echo "Logs";
         echo "</th>";
         echo "</tr>";

         echo "<tr class='tab_bg_1 center'>";
         echo "<th>";
         echo "User";
         echo "</th>";
         echo "<th>";
         echo "Date";
         echo "</th>";
         echo "<th>";
         echo "Type";
         echo "</th>";
         echo "<th>";
         echo "Action";
         echo "</th>";
         echo "<th>";
         echo "Content";
         echo "</th>";
         echo "</tr>";

         $datalog = json_decode($ret, true);
         arsort($datalog['logs']);
         foreach ($datalog['logs'] as $ldata) {
            echo "<tr class='tab_bg_1'>";
            echo "<td>";
            echo $ldata['users_id'];
            echo "</td>";
            echo "<td>";
            echo $ldata['date'];
            echo "</td>";
            echo "<td>";
            echo $ldata['type'];
            echo "</td>";
            echo "<td>";
            echo $ldata['action'];
            echo "</td>";
            echo "<td>";
            echo $ldata['content'];
            echo "</td>";
            echo "</tr>";
         }

         echo "</table>";
         
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
   
   
   function sendMib($a_mib) {
      $buffer = json_encode($a_mib);
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
      $ret = fgets ($this->fp, 102400);
      return json_decode($ret);      
   }
   
   
   
   function setUnLock() {
      $unlock = array();
      $unlock['setUnLock'] = array(
         "devices_id" => $_SESSION['plugin_fusioninventory_snmpwalks_id']);
      $buffer = json_encode($unlock);
      $buffer .= "\n";
      fputs ($this->fp, $buffer);    
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
   
   
   
   function getSendModel() {
      $singleModel = array();
      $singleModel['createSingleModel']['id'] = $_GET['id'];
      
      $buffer = json_encode($singleModel);
      $buffer .= "\n";
      fputs ($this->fp, $buffer);
      $ret = fgets ($this->fp);
      $data = json_decode($ret);
      
      $mime = "text/xml";
      
      header("Expires: Mon, 26 Nov 1962 00:00:00 GMT");
      header('Pragma: private'); /// IE BUG + SSL
      header('Cache-control: private, must-revalidate'); /// IE BUG + SSL
      header("Content-disposition: filename=\"".$data->snmpmodel->name.".xml\"");
      header("Content-Type: application/force-download");
      //header("Content-type: ".$mime);
      
      echo $data->snmpmodel->model;
   }
   
}

?>
