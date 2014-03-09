<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2013 by the FusionInventory Development Team.

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
   along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    David Durieux
   @co-author
   @copyright Copyright (c) 2010-2013 FusionInventory team
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

class PluginFusioninventoryConstructmodel extends CommonDBTM {
   private $fp, $auth=array(), $key='';

   function connect() {
      global $CFG_GLPI;

      //$this->fp = curl_init('http://127.0.0.1:9000/');
      $this->fp = curl_init('http://snmp.fusioninventory.org/');
      curl_setopt($this->fp, CURLOPT_RETURNTRANSFER, 1);
      if ($CFG_GLPI['proxy_name'] != '') {
         curl_setopt($this->fp, CURLOPT_PROXYPORT, $CFG_GLPI['proxy_port']);
         curl_setopt($this->fp, CURLOPT_PROXYTYPE, 'HTTP');
         curl_setopt($this->fp, CURLOPT_PROXY, $CFG_GLPI['proxy_name']);
         if ($CFG_GLPI['proxy_user'] != '') {

            $proxy_passwd = Toolbox::decrypt($CFG_GLPI['proxy_passwd'], GLPIKEY);

            curl_setopt($this->fp,
                        CURLOPT_PROXYUSERPWD,
                        $CFG_GLPI['proxy_user'].":".$proxy_passwd);
         }
      }
      curl_setopt($this->fp, CURLOPT_POST, TRUE);
      curl_setopt($this->fp, CURLOPT_HTTPHEADER, array('Expect:'));
      return TRUE;
   }



   function closeConnection() {
      curl_close($this->fp);
   }



   function showAuth() {

      $auth = array();
      $a_userinfos = PluginFusioninventorySnmpmodelConstructdevice_User::getUserAccount($_SESSION['glpiID']);
      if (!isset($a_userinfos['login'])) {
         echo "<table class='tab_cadre_fixe'>";

         echo "<tr class='tab_bg_1'>";
         echo "<th>";
         echo "Error";
         echo "</th>";
         echo "</tr>";

         echo "<tr class='tab_bg_1'>";
         echo "<td align='center'>";
         echo "Authentication is not right, verify login and password !<br/>
            See <a href='http://www.fusioninventory.org/documentation/fi4g/snmpmodels'>".
                 "documentation</a>";
         echo "</td>";
         echo "</tr>";

         echo "</table>";
         return FALSE;
      } else {
         $this->key = $a_userinfos['key'];
         $auth["auth"] = array("login" => $a_userinfos['login'],
                               "password" => $a_userinfos['password'],
                               "key" => $a_userinfos['key']);
         $buffer = json_encode($auth);

         $this->auth = 'login='.$a_userinfos['login'].'&auth='.$this->mcryptText($buffer);
      }
      return TRUE;
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
      echo "<a href='".$this->getSearchURL()."?action=seemodels'>See All SNMP models</a>";
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>";
      echo "<a href='http://www.fusioninventory.org/documentation/fi4g/snmpmodels'>".
              "Documentation</a>";
      echo "</td>";
      echo "</tr>";

      echo "</table>";
   }


   function showFormDefineSysdescr($message = array()) {

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
      if (empty($message)) {
         echo "snmpwalk -v [version] -c [community] -t 30 -On [IP] sysdescr";
      } else {
         echo "<strong>This device need use another OID for 'sysdescr' (try in order and
            stop when OID exist and not empty):</strong><br/>";
         echo "<ul style='margin-left:0px; padding-left:20px; list-style-type:disc;'>";
         foreach ($message as $data) {
            echo "<li>";
            echo "snmpwalk -v [version] -c [community] -t 30 -On [IP] ".$data['oid'];
            if (isset($data['message'])) {
               echo " <i>( ".$data["message"]." )</i>";
            }
            echo "</li>";
         }
         echo "</ul>";
      }
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
      if (isset($_POST['itemtype'])) {
         Dropdown::showItemType('', array('value' => $_POST['itemtype']));
      } else {
         Dropdown::showItemType();
      }
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo "Sysdescr";
      echo "</td>";
      echo "<td>";
      $sysdescr = '';
      if (empty($message)
              AND isset($_POST['sysdescr'])) {
         $sysdescr = $_POST['sysdescr'];
      }
      echo "<textarea name='sysdescr' cols='100' rows='4' />".$sysdescr."</textarea>";
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_2'>";
      echo "<td align='center' colspan='2'>";
      echo "<input class='submit' type='submit' name='sendsnmpwalk'
                      value='".__('Send')."'>";
      echo "</td>";
      echo "</tr>";

      echo "</table>";
      Html::closeForm();
   }



   function sendGetsysdescr($sysdescr, $itemtype, $devices_id = 0) {
      global $CFG_GLPI, $DB;

      $getsysdescr = array();
      if ($devices_id > 0) {
         $getsysdescr['getdeviceid'] = array(
            "id" => $devices_id);
      } else {
         $getsysdescr['getsysdescr'] = array(
            "sysdescr" => $sysdescr,
            "itemtype" => $itemtype);

         $_SESSION['plugin_fusioninventory_itemtype'] = $itemtype;
      }
      $buffer = json_encode($getsysdescr);
      curl_setopt($this->fp, CURLOPT_POSTFIELDS, $this->auth."&json=".$buffer);
      $retserv = curl_exec($this->fp);

      $data = json_decode($retserv);

      $_SESSION['plugin_fusioninventory_sysdescr'] = $data->device->sysdescr;
      echo  "<table width='950' align='center'>
         <tr>
         <td>
         <a href='".$CFG_GLPI['root_doc'].
              "/plugins/fusioninventory/front/constructmodel.php?reset=reset'>Back to main menu</a>
         </td>
         </tr>
         </table>";
      $a_lock = explode("-", $data->device->lock);
      $a_userinfos = PluginFusioninventorySnmpmodelConstructdevice_User::getUserAccount($_SESSION['glpiID']);
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
         // server return oids, mapping, oids most used for this kind of device
         // (check with sysdescr)
      } else {

         $edit = 1;
         $id = 0;
         if ($data->device->lock != '0'
              AND $a_lock[0] != $a_userinfos['login']) {
            echo "<table class='tab_cadre_fixe'>";
            echo "<tr class='tab_bg_1 center'>";
            echo "<th>";
            echo "<br/>Somebody work now on this, retry in 1 hour...<br/><br/>";
            echo "</th>";
            echo "</tr>";
            echo "</table>";
            $edit = 0;
         }

         // Device exist, update it? get snmpmodels?
         echo "<table class='tab_cadre_fixe'>";

         echo "<tr class='tab_bg_1 center'>";
         echo "<th colspan='2' width='50%'>";
         echo "This device exist";
         echo "</th>";
         echo "<th colspan='2'>";

         if ($devices_id > 0) {
            $id = $devices_id;
         } else {
            $id = $data->device->id;
            Html::redirect($CFG_GLPI['root_doc'].
                              "/plugins/fusioninventory/front/constructmodel.php?devices_id=".$id);
         }
         $query = "SELECT * FROM `glpi_plugin_fusioninventory_snmpmodelconstructdevicewalks`
                   WHERE `plugin_fusioninventory_snmpmodelconstructdevices_id`='".$id."'
                   LIMIT 1";
         $result=$DB->query($query);
         if ($DB->numrows($result) == '0') {
            $edit = 0;
         } else {
            $sqldata = $DB->fetch_assoc($result);
            if (!file_exists(GLPI_PLUGIN_DOC_DIR."/fusioninventory/walks/".$sqldata['log'])) {
               $edit = 0;
               $querydel = "DELETE FROM `glpi_plugin_fusioninventory_snmpmodelconstructdevicewalks`
                   WHERE `plugin_fusioninventory_snmpmodelconstructdevices_id`='".$id."'";
               $DB->query($querydel);
            }
         }

         echo "<a href='".$CFG_GLPI['root_doc'].
                 "/plugins/fusioninventory/front/constructmodel.php?editoid=".$data->device->id."'>";
         if ($edit == '1') {
            echo "Edit oids";
         } else {
            echo "See oids";
         }
         echo "</a>";
         echo "&nbsp; &nbsp; | &nbsp; &nbsp;";
         echo "<a href='".$CFG_GLPI['root_doc'].
                 "/plugins/fusioninventory/front/constructsendmodel.php?id=".$data->device->id."' ".
                 "target='_blank'>".__('Get SNMP model', 'fusioninventory')."</a>";
         if ($data->device->snmpmodels_id > 0) {
            echo "&nbsp; &nbsp; | &nbsp; &nbsp;";
            echo "<a href='".$CFG_GLPI['root_doc'].
                    "/plugins/fusioninventory/front/constructsendmodel.php?models_id=".
                    $data->device->snmpmodels_id."' target='_blank'>".
                    __('Import SNMP model', 'fusioninventory')."</a>";
         }
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

         // * Manage SNMPWALK file
         if ($edit == '0') {
            $this->showUploadSnmpwalk($data->device->sysdescr, $data->device->itemtype);
         } else {
            echo "<table class='tab_cadre' width='900'>";

            echo "<tr class='tab_bg_1 center'>";
            echo "<th>";
            echo "Snmpwalk file";
            echo "</th>";
            echo "</tr>";

            echo "<tr class='tab_bg_1'>";
            echo "<td class='center'>";
            echo "snmpwalk file present";
            echo "<form method='post' name='' id=''  action=''>";
            echo "<input type='hidden' name='devices_id' value='".$devices_id."' />";
            echo "<div align='center'><input type='submit' name='deletesnmpwalkfile' ".
                    "value=\"".__('Delete permanently')."\" class='submit' >";
            Html::closeForm();
            echo "</td>";
            echo "</tr>";

            echo "</table><br/>";
         }

         // * Manage Logs
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

         $datalog = json_decode($retserv, TRUE);
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
      curl_setopt($this->fp, CURLOPT_POSTFIELDS, $this->auth."&json=".$buffer);
      $retserv = curl_exec($this->fp);
      return json_decode($retserv);
   }


   function sendMib($a_mib) {
      $buffer = json_encode($a_mib);
      curl_setopt($this->fp, CURLOPT_POSTFIELDS, $this->auth."&json=".$buffer);
      $retserv = curl_exec($this->fp);
      return json_decode($retserv);
   }



   function setLock($sysdescr, $itemtype) {
      $getsysdescr = array();
      $getsysdescr['setLock'] = array(
         "sysdescr" => $sysdescr,
         "itemtype" => $itemtype);
      $buffer = json_encode($getsysdescr);
      curl_setopt($this->fp, CURLOPT_POSTFIELDS, $this->auth."&json=".$buffer);
      $retserv = curl_exec($this->fp);
      return json_decode($retserv);
   }



   function setUnLock() {
      $unlock = array();
      $unlock['setUnLock'] = array(
         "devices_id" => $_SESSION['plugin_fusioninventory_snmpwalks_id']);
      $buffer = json_encode($unlock);
      curl_setopt($this->fp, CURLOPT_POSTFIELDS, $this->auth."&json=".$buffer);
      curl_exec($this->fp);
   }



   function showUploadSnmpwalk($sysdescr, $itemtype) {

      echo "<form method='post' name='' id=''  action='' enctype=\"multipart/form-data\">";
      echo "<table class='tab_cadre_fixe'>";

      echo "<tr class='tab_bg_3 center'>";
      echo "<th colspan='2'>";
      echo "Upload your SNMPWALK";
      echo "</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_3 center'>";
      echo "<td colspan='2'>";
      echo "<i>IMPORTANT: This file keep in your GLPI server, and no data of this will be ".
              "uploaded in central server</i>";
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_3'>";
      echo "<td>";
      echo "Command to create the snmpwalk";
      echo "</td>";
      echo "<td>";
      echo "snmpwalk -v [version] -c [community] -t 30 -Cc -On [IP] .1 > file.log";
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
      echo "<input type='hidden' name='sysdescr' value='".$sysdescr."' />";
      echo "<input type='hidden' name='itemtype' value='".$itemtype."' />";
      echo "<div align='center'><input type='submit' name='add' value=\"" . __('Add') .
                 "\" class='submit' >";
      echo "</td>";
      echo "</tr>";

      echo "</table>";
      Html::closeForm();
   }



   function getSendModel($write=0, $models_id=0) {
      $singleModel = array();
      if (is_array($models_id)) {
         $singleModel['getMultipleModel'] = $models_id;
      } else if (isset($_GET['models_id'])) {
         $singleModel['getSingleModel']['id'] = $_GET['models_id'];
      } else if ($models_id > 0) {
         $singleModel['getSingleModel']['id'] = $models_id;
      } else if (isset($_GET['id'])) {
         $singleModel['createSingleModel']['id'] = $_GET['id'];
      }

      $buffer = json_encode($singleModel);
      curl_setopt($this->fp, CURLOPT_POSTFIELDS, $this->auth."&json=".$buffer);
      $retserv = curl_exec($this->fp);
      $data = json_decode($retserv);

      if ($write == '0') {

         header("Expires: Mon, 26 Nov 1962 00:00:00 GMT");
         header('Pragma: private'); /// IE BUG + SSL
         header('Cache-control: private, must-revalidate'); /// IE BUG + SSL
         header("Content-disposition: filename=\"".$data->snmpmodel->name.".xml\"");
         header("Content-Type: application/force-download");

         echo $data->snmpmodel->model;
      } else {
         if (is_array($models_id)) {
            foreach ($data->snmpmodel as $model) {
               file_put_contents(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/tmpmodels/'.$model->name.'.xml',
                                 trim($model->model));
            }
         } else {
            file_put_contents(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/tmpmodels/'.$data->snmpmodel->name.
                                 '.xml',
                              trim($data->snmpmodel->model));
         }
      }
   }



   function showAllModels() {
      global $DB;

      $getsysdescr = array();
      $getsysdescr['getallmodels'] = array(
         'type' => 'all'); // all, stable, devel

      $buffer = json_encode($getsysdescr);
      curl_setopt($this->fp, CURLOPT_POSTFIELDS, $this->auth."&json=".$buffer);
      $retserv = curl_exec($this->fp);
      $data = json_decode($retserv, TRUE);

      echo "<center>";
      if (!isset($_SESSION['glpi_plugin_fusioninventory_constructmodelsort'])) {
         $_SESSION['glpi_plugin_fusioninventory_constructmodelsort'] = 'itemtype';
      }
      echo "<form name='sortform' id='sortform' method='post'>";
      echo __('Sorted by', 'fusioninventory')."&nbsp;: ";
      $array_sort = array();
      $array_sort['name'] = 'Model name';
      $array_sort['itemtype'] = 'Itemtype';
      $array_sort['stabledevel'] = 'Stable/devel';
      $array_sort['snmpfile'] = 'Snmpfile';
      Dropdown::showFromArray('sort',
                              $array_sort,
                     array(
                       'value' => $_SESSION['glpi_plugin_fusioninventory_constructmodelsort']
                     ));
      echo "&nbsp;<input type='submit' name='updatesort' class='submit' ".
              "value=\"".__('Update')."\" >";
      Html::closeForm();
      echo "</center>";

      $a_sort = array();
      $a_sort['name'] = array();
      $a_sort['itemtype'] = array();
      $a_sort['stabledevel'] = array();
      $a_sort['localglpi'] = array();
      $nb_devices = 0;
      foreach ($data as $key => $a_models) {
         $a_sort['name'][$key] = $a_models['name'];
         $a_sort['itemtype'][$key] = $a_models['itemtype'];
         $stable = 'ok';
         $local = 2;
         $snmpfile = 1;
         $nbnotinglpi = 0;
         foreach ($a_models['devices'] as $a_devices) {
            $nb_devices++;
            if ($a_devices['stable'] == '0') {
               $stable = 'part';
            }
            $query = "SELECT * FROM `glpi_plugin_fusioninventory_snmpmodeldevices`
                      LEFT JOIN `glpi_plugin_fusioninventory_snmpmodels`
                         ON `plugin_fusioninventory_snmpmodels_id`=`glpi_plugin_fusioninventory_snmpmodels`.`id`
                      WHERE `sysdescr` = '".$a_devices['sysdescr']."'
                      LIMIT 1";
            $result = $DB->query($query);
            if ($DB->numrows($result) != 0) {
               $datam = $DB->fetch_assoc($result);
               if ($datam['name'] != $a_models['name']) {
                  $local = 1;
                  $nbnotinglpi++;
               }
            } else {
               $local = 0;
               $nbnotinglpi++;
            }

            $query = "SELECT * FROM `glpi_plugin_fusioninventory_snmpmodelconstructdevicewalks`
                      WHERE `plugin_fusioninventory_snmpmodelconstructdevices_id` = '".$a_devices['id']."'
                      LIMIT 1";
            $result = $DB->query($query);
            if ($DB->numrows($result) == "1") {
               $sqldata = $DB->fetch_assoc($result);
               if (!file_exists(GLPI_PLUGIN_DOC_DIR."/fusioninventory/walks/".$sqldata['log'])) {
                  $querydel = "DELETE FROM `glpi_plugin_fusioninventory_snmpmodelconstructdevicewalks`
                      WHERE `plugin_fusioninventory_snmpmodelconstructdevices_id`='".$a_devices['id']."'";
                  $DB->query($querydel);
               } else {
                  $snmpfile = 0;
               }
            }
         }
         if (count($a_models['devices']) == $nbnotinglpi) {
            $a_sort['stabledevel'][$key] = 'not';
         } else if ($nbnotinglpi == '0') {
            $a_sort['stabledevel'][$key] = 'ok';
         } else {
            $a_sort['stabledevel'][$key] = 'part';
         }
         $a_sort['localglpi'][$key] = $local;
         $a_sort['snmpfile'][$key] = $snmpfile;
      }
      echo "<form name='form_model' id='form_model' method='post'>";
      echo "<input type='hidden' name='nbmodels' value='".count($data)."' />";

      echo "<br/>";
      echo "<table class='tab_cadre_fixe'>";
      echo "<tr class='tab_bg_1'>";
      echo "<th colspan='9'>";
      echo count($data)." models ! ".$nb_devices." devices supported !";
      echo "</th>";
      echo "</tr>";
      echo "</table>";

      $menu = "<br/><center>| <a href='#'>Top</a>
         | <a href='#not'>Models not imported</a>
         | <a href='#part'>Models to be updated</a>
         | <a href='#ok'>Models up to date</a>
         | <a href='#import'>Import button</a> |</center><br/>";

      echo "<a id='not'>".$menu."</a>";
      $this->displayModelsList($data, $a_sort, 'not');

      echo "<a id='part'>".$menu."</a>";
      $this->displayModelsList($data, $a_sort, 'part');

      echo "<a id='ok'>".$menu."</a>";
      $this->displayModelsList($data, $a_sort, 'ok');

      echo "<a id='import'>".$menu."</a>";
      Html::openArrowMassives("form_model", TRUE);
      Html::closeArrowMassives(array('import' => __('Import')),
                               array('import' => 'Import will update existing models'));
      Html::closeForm();
   }



   function displayModelsList($data, $a_sort, $modelimport) {
      global $CFG_GLPI, $DB;

      echo  "<table class='tab_cadre'>";

      echo "<tr class='tab_bg_1'>";
      echo "<th colspan='3'>";
      if ($modelimport == 'not') {
         echo "<img src='".$CFG_GLPI["root_doc"]."/plugins/fusioninventory/pics/box_red.png'/>";
         echo "</th>";
         echo "<th colspan='5'>";
         echo "Models not imported";
      } else if ($modelimport == 'part') {
         echo "<img src='".$CFG_GLPI["root_doc"]."/plugins/fusioninventory/pics/box_orange.png'/>";
         echo "</th>";
         echo "<th colspan='5'>";
         echo "Models to be updated";
      } else if ($modelimport == 'ok') {
         echo "<img src='".$CFG_GLPI["root_doc"]."/plugins/fusioninventory/pics/box_green.png'/>";
         echo "</th>";
         echo "<th colspan='5'>";
         echo "Models up to date";
      }

      echo "</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<th rowspan='2'>";
      echo "</th>";
      echo "<th rowspan='2'>";
      echo "Model name";
      echo "</th>";
      echo "<th rowspan='2'>";
      echo "Itemtype";
      echo "</th>";
      if ($modelimport == 'part') {
         echo "<th colspan='5'>";
      } else {
         echo "<th colspan='4'>";
      }
      echo "Equipements";
      echo "</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<th>";
      echo "sysdescr";
      echo "</th>";
      echo "<th>";
      echo "</th>";
      echo "<th>";
      echo "Stable/devel";
      echo "</th>";
      if ($modelimport == 'part') {
         echo "<th>";
   //      echo "In local GLPI";
         echo "Imported";
         echo "</th>";
      }
      echo "<th>";
      echo "snmpwalk file";
      echo "</th>";
      echo "</tr>";

      array_multisort($a_sort[$_SESSION['glpi_plugin_fusioninventory_constructmodelsort']],
                      SORT_ASC,
                      $a_sort['itemtype'],
                      SORT_ASC,
                      $a_sort['name'],
                      SORT_ASC,
                      $data);
      foreach ($data as $key => $a_models) {
         if ($a_sort['stabledevel'][$key] == $modelimport) {
            $nbdevices = count($a_models['devices']);
            echo "<tr class='tab_bg_3'>";
            echo "<td align='center' rowspan='".$nbdevices."'>";
            echo "<input type='checkbox' name='models[]' value='".$a_models['id']."'/>";
            echo "</td>";
            echo "<td align='center' rowspan='".$nbdevices."'>";
            echo "<a href='".$CFG_GLPI['root_doc'].
                    "/plugins/fusioninventory/front/constructsendmodel.php?models_id=".
                    $a_models['id']."'>";
            echo "<font color='#000000'>".$a_models['name']."</font>";
            echo "</a>";
            echo "</td>";
            echo "<td align='center' rowspan='".$nbdevices."'>";
            $a_itemtypes = array();
            $a_itemtypes[1] = __('Computer');
            $a_itemtypes[2] = __('Network');
            $a_itemtypes[3] = __('Printer');
            echo $a_itemtypes[$a_models['itemtype']];
            echo "</td>";
            $i = 0;
            foreach ($a_models['devices'] as $a_devices) {
               if ($i > 0) {
                  echo "<tr class='tab_bg_3'>";
               }
               $i = 1;
               echo "<td>";
               echo $a_devices['sysdescr'];
               echo "</td>";
               echo "<td align='center'>";
               echo "<a href='".$CFG_GLPI['root_doc'].
                       "/plugins/fusioninventory/front/constructmodel.php?devices_id=".
                       $a_devices['id']."'>";
               echo "<img src='".$CFG_GLPI["root_doc"].
                       "/pics/rapports.png' width='18' height='18' />";

               echo "</a>";
               echo "</td>";
               echo "<td align='center'>";
               if ($a_devices['stable'] == '0') {
                  echo "devel";
               } else {
                  echo "stable";
               }
               echo "</td>";
               if ($modelimport == 'part') {
                  echo "<td align='center'>";
                  $query = "SELECT * FROM `glpi_plugin_fusioninventory_snmpmodeldevices`
                            LEFT JOIN `glpi_plugin_fusioninventory_snmpmodels`
                               ON `plugin_fusioninventory_snmpmodels_id`=`glpi_plugin_fusioninventory_snmpmodels`.`id`
                            WHERE `sysdescr` = '".$a_devices['sysdescr']."'
                            LIMIT 1";
                  $result = $DB->query($query);
                  if ($DB->numrows($result) > 0) {
                     $datam = $DB->fetch_assoc($result);
                     if ($datam['name'] == $a_models['name']) {
                        echo "<img src='".$CFG_GLPI["root_doc"].
                                "/pics/ok.png' width='20' height='20'/>";
                     } else {
//                        echo "May be updated";
                     }
                  }
                  echo "</td>";
               }

               echo "<td align='center'>";
               $query = "SELECT * FROM `glpi_plugin_fusioninventory_snmpmodelconstructdevicewalks`
                         WHERE `plugin_fusioninventory_snmpmodelconstructdevices_id` = '".$a_devices['id']."'
                         LIMIT 1";
               $result = $DB->query($query);
               if ($DB->numrows($result) == "1") {
                  echo "<img src='".$CFG_GLPI["root_doc"]."/pics/ok.png' width='20' height='20'/>";
               }
               echo "</td>";
               echo "</tr>";
            }
         }
      }
      echo "</table>";
   }



   function importModels() {

      echo "<table class='tab_cadre_fixe'>";

      echo "<tr class='tab_bg_1'>";
      echo "<th>";
      echo __('Download SNMP models, please wait...', 'fusioninventory');
      echo "</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      Html::createProgressBar(__('Download SNMP models, please wait...', 'fusioninventory'));
      $i = 0;
      $nb = count($_POST['models']);
      foreach ($_POST['models'] as $models_id) {
         $this->connect();
         $this->showAuth();
         $this->getSendModel(1, $models_id);
         $this->closeConnection();

         $i++;
         Html::changeProgressBarPosition($i, $nb, "$i / $nb");
      }
      Html::changeProgressBarPosition($nb, $nb, "$nb / $nb");
      echo "</td>";
      echo "</tr>";
      echo "</table>";

      if (count($_POST['models']) == $_POST['nbmodels']) {
         // Import all models
         $pfModel = new PluginFusioninventorySNMPModel();
         $pfModel->importAllModels(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/tmpmodels');

      } else {
         // Import each model
         $pfImportExport = new PluginFusioninventorySnmpmodelImportExport();
         echo "<table class='tab_cadre_fixe'>";
         echo "<tr class='tab_bg_1'>";
         echo "<th align='center'>";
         echo "Importing SNMP models, please wait...";
         echo "</th>";
         echo "</tr>";
         echo "<tr class='tab_bg_1'>";
         echo "<td align='center'>";
         Html::createProgressBar("Importing SNMP models, please wait...");
         $nb = 0;
         foreach (glob(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/tmpmodels/*.xml') as $file) {
            $nb++;
         }
         $i = 0;
         foreach (glob(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/tmpmodels/*.xml') as $file) {
            $pfImportExport->import($file, 0);

            $i++;
            Html::changeProgressBarPosition($i, $nb, "$i / $nb");
         }
         Html::changeProgressBarPosition($nb, $nb, "$nb / $nb");
         echo "</td>";
         echo "</tr>";
         echo "</table>";
         PluginFusioninventorySnmpmodelImportExport::exportDictionnaryFile();
      }
      $dir = GLPI_PLUGIN_DOC_DIR.'/fusioninventory/tmpmodels/';
      $objects = scandir($dir);
      foreach ($objects as $object) {
         if ($object != "." && $object != "..") {
            unlink($dir."/".$object);
         }
      }
   }



   function showFormAddOid($mapping_name) {

      echo "<form name='form' method='post' action=''>";
      echo "<table class='tab_cadre_fixe'>";

      echo "<tr class='tab_bg_1'>";
      echo "<th colspan='3'>";
      echo "Add a new oid";
      echo "</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_3'>";
      echo "<td>";
      echo "Mapping&nbsp;:";
      echo "</td>";
      echo "<td>";
      echo $mapping_name;
      echo "<input type='hidden' name='mapping' value='".$mapping_name."' />";
      echo "</td>";
      echo "<td>";
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_3'>";
      echo "<td>";
      echo "Numeric oid&nbsp;:";
      echo "</td>";
      echo "<td>";
      echo "<input type='text' name='numeric_oid' value='' size='35'/>";
      echo "</td>";
      echo "<td>";
      echo "For example we use this oid to get <i>name</i> :<br/> ".
              "<strong>.1.3.6.1.2.1.1.5.0</strong>";
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_3'>";
      echo "<td>";
      echo "Mib oid&nbsp;:";
      echo "</td>";
      echo "<td>";
      echo "<input type='text' name='mib_oid' value='' size='35'/>";
      echo "</td>";
      echo "<td>";
      echo "For example we use this mib oid to get <i>name</i> :<br/> ".
              "<strong>SNMPv2-MIB::sysName.0</strong>";
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_3'>";
      echo "<td>";
      echo "Number numeric groups after&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::showNumber("nboids_after", array(
             'value' => 0,
             'min'   => 0,
             'max'   => 20)
      );
      echo "</td>";
      echo "<td>";
      echo "* For the oid for <i>name</i> there is no other thing after ".
              ".1.3.6.1.2.1.1.5.0, so it's <strong>0</strong><br/>
            * For the oid for <i>ifName</i>, we get the port id like ".
              ".1.3.6.1.2.1.31.1.1.1.1<strong>.10001</strong>,
            .1.3.6.1.2.1.31.1.1.1.1<strong>.10002</strong>... so it's <strong>1</strong>";
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td colspan='3' align='center'>";
      echo "<input type='submit' name='add' value=\"".__('Add')."\" class='submit'>";
      echo "</td>";
      echo "</table>";
      Html::closeForm();
   }



   function sendNewOid($data) {
      $addOid = array();
      $addOid['addOid']['mapping'] = $_POST['mapping'];
      $addOid['addOid']['numeric_oid'] = $_POST['numeric_oid'];
      $addOid['addOid']['mib_oid'] = $_POST['mib_oid'];
      $addOid['addOid']['nboids_after'] = $_POST['nboids_after'];

      $buffer = json_encode($addOid);
      curl_setopt($this->fp, CURLOPT_POSTFIELDS, $this->auth."&json=".$buffer);
      $retserv = curl_exec($this->fp);
      $data = json_decode($retserv, TRUE);

      echo "<table class='tab_cadre_fixe'>";
      echo "<tr class='tab_bg_1'>";
      echo "<th align='center'>";
      if ($data['oidcreation'] == 'succesfull') {
         echo "This oid is right created on server :)";
         echo '<script language="JavaScript">
         window.onunload = function() {
             if (window.opener && !window.opener.closed) {
                 window.opener.popUpClosed();
             }
         };
         </script>';
      } else if ($data['oidcreation'] == 'yetexist') {
         echo "This oid yet exist on server !";
      }
      echo "</th>";
      echo "</tr>";
      echo "</table>";
   }


   function mcryptText($text) {
      return $text;


      $td = mcrypt_module_open(MCRYPT_RIJNDAEL_256, "", MCRYPT_MODE_ECB, "");
      mcrypt_generic_init($td, $this->key, 0);
      $temp = mcrypt_generic($td, $text);
      mcrypt_generic_deinit($td);
      return $temp;
   }



   function detectWrongSysdescr($sysdescr) {
      $message = array();
      if (strstr($sysdescr, 'AXIS OfficeBasic Network Print Server')) {
         $message[] = array('oid' => '.1.3.6.1.4.1.2699.1.2.1.2.1.1.3.1',
                            'message' => 'Value between "MDL:" and ";" OR "MODEL:" and ";"');
      } else if (strstr($sysdescr, 'EPSON Built-in')) {
         $message[] = array('oid' => '.1.3.6.1.4.1.1248.1.1.3.1.3.8.0');
      } else if (strstr($sysdescr, 'EPSON Internal 10Base-T')) {
         $message[] = array('oid' => '.1.3.6.1.2.1.25.3.2.1.3.1');
      } else if (strstr($sysdescr, ', HP, JETDIRECT, J')) {
         $message[] = array('oid' => '.1.3.6.1.4.1.1229.2.2.2.1.15.1');
      } else if (strstr($sysdescr, 'SAMSUNG NETWORK PRINTER, ROM')) {
         $message[] = array('oid' => '.1.3.6.1.4.1.236.11.5.1.1.1.1.0');
      } else if (strstr($sysdescr, 'RICOH NETWORK PRINTER')) {
         $message[] = array('oid' => '.1.3.6.1.4.1.11.2.3.9.1.1.7.0');
      } else if (strstr($sysdescr, 'ZebraNet PrintServer')
              OR (strstr($sysdescr, 'ZebraNet Wired PS'))) {
         $message[] = array('oid' => '.1.3.6.1.4.1.10642.1.1.0',
                            'message' => 'If this OID exist');
         $message[] = array('oid' => '.1.3.6.1.4.1.11.2.3.9.1.1.7.0',
                            'message' => 'Value between "MDL:" and ";" OR "MODEL:" and ";"');
      } else if (strstr($sysdescr, 'HP ETHERNET MULTI-ENVIRONMENT')
              OR (strstr($sysdescr, 'A SNMP proxy agent, EEPROM'))) {
         $message[] = array('oid' => '.1.3.6.1.2.1.25.3.2.1.3.1',
                            'message' => 'If this OID exist');
         $message[] = array('oid' => '.1.3.6.1.4.1.11.2.3.9.1.1.7.0',
                            'message' => 'Value between "MDL:" and ";" OR "MODEL:" and ";"');
      } else if ($sysdescr == "Ethernet Switch") {
         $message[] = array('oid' => '.1.3.6.1.4.1.674.10895.3000.1.2.100.1.0');
      } else if ($sysdescr == "SB-110"
              OR $sysdescr == "KYOCERA MITA Printing System"
              OR $sysdescr == "KYOCERA Print I/F") {
         $message[] = array('oid' => '.1.3.6.1.4.1.1347.42.5.1.1.2.1',
                            'message' => 'If this OID exist');
         $message[] = array('oid' => '.1.3.6.1.4.1.1347.43.5.1.1.1.1',
                            'message' => 'If this OID exist');
         $message[] = array('oid' => '.1.3.6.1.4.1.11.2.3.9.1.1.7.0',
                            'message' => 'Value between "MDL:" and ";" OR "MODEL:" and ";"');
      } else if (strstr($sysdescr, 'Linux')) {
         $message[] = array('oid' => '.1.3.6.1.2.1.1.5.0',
                            'message' => 'If this OID exist');
         $message[] = array('oid' => '.1.3.6.1.4.1.714.1.2.5.6.1.2.1.6.1',
                            'message' => 'If this OID exist');
      } else if (preg_match("/Samsung(.*);S\/N(.*)/", $sysdescr)) {
         $message[] = array('oid' => '.1.3.6.1.4.1.236.11.5.1.1.1.1.0');
      }  else if (preg_match("/^\S+ Service Release/", $sysdescr)) {
         $message[] = array('oid' => '.1.3.6.1.2.1.47.1.1.1.1.13.1');
      }
      return $message;
   }

}

?>
