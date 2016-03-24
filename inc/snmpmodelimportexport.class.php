<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2016 by the FusionInventory Development Team.

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
   @copyright Copyright (c) 2010-2016 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010

   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access this file directly");
}

class PluginFusioninventorySnmpmodelImportExport extends CommonGLPI {


   /**
    * Export a SNMP model in a XML
    *
    * @global object $DB
    * @param integer $ID_model idof the SNMP model
    *
    * @return string XML
    */
   function export($ID_model) {
      global $DB;

      Session::checkRight('plugin_fusioninventory_model', READ);
      $query = "SELECT *
         FROM `glpi_plugin_fusioninventory_snmpmodels`
         WHERE `id`='".$ID_model."'";

      $model_name = "";
      $type = "";
      $discovery_key = "";
      $comment = "";

      $result=$DB->query($query);
      if ($result) {

         if ($DB->numrows($result) != 0) {
            $model_name = $DB->result($result, 0, "name");
            $type = $DB->result($result, 0, "itemtype");
            $discovery_key = $DB->result($result, 0, "discovery_key");
            $comment = $DB->result($result, 0, "comment");
         } else {
            exit();
         }
      }

      // Construction of XML file
      $xml = "<model>\n";
      $xml .= "   <name>".$model_name."</name>\n";
      $xml .= "   <type>".$type."</type>\n";
      $xml .= "   <key>".$discovery_key."</key>\n";
      $xml .= "   <comments><![CDATA[".$comment."]]></comments>\n";
      $xml .= "   <oidlist>\n";

      $query = "SELECT `glpi_plugin_fusioninventory_snmpmodelmibs`.*,
    FROM `glpi_plugin_fusioninventory_snmpmodelmibs`
    WHERE `plugin_fusioninventory_snmpmodels_id`='".$ID_model."';";

      $result=$DB->query($query);
      if ($result) {
         while ($data=$DB->fetch_array($result)) {
            $xml .= "      <oidobject>\n";
            $xml .= "         <object>".
               Dropdown::getDropdownName("glpi_plugin_fusioninventory_snmpmodelmibobjects",
                                         $data["plugin_fusioninventory_snmpmodelmibobjects_id"]).
               "</object>\n";
            $xml .= "         <oid>".
               Dropdown::getDropdownName("glpi_plugin_fusioninventory_snmpmodelmiboids",
                                   $data["plugin_fusioninventory_snmpmodelmiboids_id"])."</oid>\n";
            $xml .= "         <portcounter>".$data["oid_port_counter"]."</portcounter>\n";
            $xml .= "         <dynamicport>".$data["oid_port_dyn"]."</dynamicport>\n";
            $xml .= "         <mappings_id>".$data["plugin_fusioninventory_mappings_id"].
               "</mappings_id>\n";
            $xml .= "         <vlan>".$data["vlan"]."</vlan>\n";
            $xml .= "         <activation>".$data["is_active"]."</activation>\n";
            $xml .= "      </oidobject>\n";
         }
      }
      $xml .= "   </oidlist>\n";
      $xml .= "</model>\n";

      return $xml;
   }



   function showForm($id, $options=array()) {
      global $CFG_GLPI;

      Session::checkRight('plugin_fusioninventory_model', READ);

      $target = $CFG_GLPI['root_doc'].'/plugins/fusioninventory/front/snmpmodel.form.php';
      echo "<form action='".$target."?add=1' method='post' enctype='multipart/form-data'>";

      echo "<br>";
      echo "<table class='tab_cadre' cellpadding='1' width='600'><tr><th colspan='2'>";
      echo __('SNMP model import', 'fusioninventory')." :</th></tr>";

      echo "   <tr class='tab_bg_1'>";
      echo "      <td align='center'>";
      echo "</td>";
      echo "<td align='center'>";
      echo "<input type='file' name='importfile' value=''/>";

      if(Session::haveRight('plugin_fusioninventory_model', UPDATE)) {
    echo "&nbsp;<input type='submit' value='".__('Import')."' class='submit'/>";
      }
      echo "</td>";
      echo "</tr>";
      echo "</table>";

      Html::closeForm();
   }



   function showFormMassImport($target) {

      Session::checkRight('plugin_fusioninventory_model', READ);

      echo "<form action='".$target."?add=1' method='post' enctype='multipart/form-data'>";

      echo "<table class='tab_cadre' cellpadding='1' width='600'><tr><th>";
      echo __('Mass import of models', 'fusioninventory')." :</th></tr>";

      echo "   <tr class='tab_bg_1'>";
      echo "<td align='center'>";
      echo __('Mass import of models in folder plugins/fusioninventory/snmpmodels/', 'fusioninventory')."<br/>";
      echo "<input type='hidden' name='massimport' value='1'/>";
      if(Session::haveRight('plugin_fusioninventory_model', UPDATE)) {
    echo "&nbsp;<input type='submit' value='".__('Import')."' class='submit'/>";
      }
      echo "</td>";
      echo "</tr>";
      echo "</table>";

      Html::closeForm();
   }



   function import($file, $message=1, $installation=0) {
      global $DB;

      if ($installation != 1) {
         Session::checkRight('plugin_fusioninventory_model', UPDATE);
      }

      $xml = simplexml_load_file($file, 'SimpleXMLElement', LIBXML_NOCDATA);

      // Clean
      $query = "DELETE FROM `glpi_plugin_fusioninventory_snmpmodelmibs`
         WHERE  `plugin_fusioninventory_mappings_id`='0'
            AND `oid_port_counter`='0'";
      $DB->query($query);

      // check if the model already exists
      $query = "SELECT `id` FROM `glpi_plugin_fusioninventory_snmpmodels`
         WHERE `name`='".(string)$xml->name."'
         LIMIT 1";
      $result = $DB->query($query);

      if ($DB->numrows($result) > 0) {
         $this->updateModel($xml, $message, $result);
      } else {
         $this->createModel($xml, $message);
      }
      $pfSnmpmodeldevice = new PluginFusioninventorySnmpmodeldevice();
      $pfSnmpmodeldevice->cleanDevices();
   }



   function updateModel($xml, $message, $result) {
      global $DB;

      $pfMapping = new PluginFusioninventoryMapping();

      if ($message == '1') {
         $_SESSION["MESSAGE_AFTER_REDIRECT"] = __('Model already exists', 'fusioninventory');

      }

      // Update model oids
      // Get list of oids in DB
      $a_oidsDB = array();
      $models_data = $DB->fetch_assoc($result);
      $pfModelMib = new PluginFusioninventorySnmpmodelMib();
      $pfModel = new PluginFusioninventorySnmpmodel();
      $pfModel->getFromDB($models_data['id']);
      $input = array();
      $input['id'] = $pfModel->fields['id'];
      $input['comment'] = Toolbox::clean_cross_side_scripting_deep(
                              Toolbox::addslashes_deep((string)$xml->comments));
      $pfModel->update($input);

      $a_devices = array();
      if (isset($xml->devices)) {
         foreach ($xml->devices->sysdescr as $child) {
            $a_devices[] = (string)$child;
         }
      }
      $pfSnmpmodeldevice = new PluginFusioninventorySnmpmodeldevice();
      $pfSnmpmodeldevice->updateDevicesForModel($pfModel->fields['id'], $a_devices);

      $a_oids = $pfModelMib->find(
              "`plugin_fusioninventory_snmpmodels_id`='".$models_data['id']."'");
      foreach ($a_oids as $data) {
         $oid = Dropdown::getDropdownName("glpi_plugin_fusioninventory_snmpmodelmiboids",
                                          $data['plugin_fusioninventory_snmpmodelmiboids_id']);
         $oid_name = '';
         if ($data['plugin_fusioninventory_mappings_id'] != 0) {
            $pfMapping->getFromDB($data['plugin_fusioninventory_mappings_id']);
            $oid_name = $pfMapping->fields["name"];
         }
         $a_oidsDB[$oid."-".$oid_name] = $data['id'];
      }
      $mapping_type = '';
      foreach($xml->oidlist->oidobject as $child) {
         $input = array();
         if (isset($a_oidsDB[$child->oid."-".$child->mapping_name])) {
            // Update oid
            $pfModelMib->getFromDB($a_oidsDB[$child->oid."-".$child->mapping_name]);
            $input = $pfModelMib->fields;
         }
         $input["plugin_fusioninventory_snmpmodels_id"] = $models_data['id'];
         $input['plugin_fusioninventory_snmpmodelmibobjects_id'] = 0;
         if (isset($child->object)) {
            $input['plugin_fusioninventory_snmpmodelmibobjects_id'] = Dropdown::importExternal(
               "PluginFusioninventorySnmpmodelMibObject", $child->object);
         }
         $input['plugin_fusioninventory_snmpmodelmiboids_id'] = Dropdown::importExternal(
            "PluginFusioninventorySnmpmodelMibOid", $child->oid);
         $input['oid_port_counter'] = 0;
         if (isset($child->portcounter)) {
            $input['oid_port_counter'] = $child->portcounter;
         }
         $input['oid_port_dyn'] = 0;
         if (isset($child->dynamicport)) {
            $input['oid_port_dyn'] = $child->dynamicport;
         }
         $input["vlan"] = 0;
         if (isset($child->vlan)) {
            $input["vlan"] = $child->vlan;
         }
         $input["is_active"] = 0;
         if (isset($child->activation)) {
            $input["is_active"] = $child->activation;
         }
         if (isset($mapping_type)) {
            unset($mapping_type);
         }
         if (isset($child->mapping_type)) {
            $mapping_type = '';
            if (is_numeric($child->mapping_type)) {
               switch ($child->mapping_type) {

                  case '1':
                     $mapping_type = "Computer";
                     break;

                  case '2':
                     $mapping_type = "NetworkEquipment";
                     break;

                  case '3':
                     $mapping_type = "Printer";
                     break;

               }
            } else {
               $mapping_type = $child->mapping_type;
            }
         }
         $input["plugin_fusioninventory_mappings_id"] = 0;
         if (isset($child->mapping_name)) {
            if ($child->mapping_name != '') {
               $a_mappings = $pfMapping->get($mapping_type, $child->mapping_name);
               $input["plugin_fusioninventory_mappings_id"] = $a_mappings['id'];
            }
         }
         $input["plugin_fusioninventory_mappings_id"] = 0;
         if (isset($child->mapping_name)) {
            if ($child->mapping_name != '') {
               $a_mappings = $pfMapping->get($mapping_type, $child->mapping_name);
               $input["plugin_fusioninventory_mappings_id"] = $a_mappings['id'];
            }
         }
         $input["plugin_fusioninventory_snmpmodelmiblabels"] = 0;
         if (isset($a_oidsDB[$child->oid."-".$child->mapping_name])) {
            // Update oid
            $pfModelMib->update($input);
            unset($a_oidsDB[$child->oid."-".$child->mapping_name]);
         } else {
            // Add
            $pfModelMib->add($input);
         }
      }
      // Delete OID not in the XML
      foreach ($a_oidsDB as $mibs_id) {
         $pfModelMib->delete(array('id'=>$mibs_id), 1);
      }
   }



   function createModel($xml, $message) {
      global $DB;

      $pfMapping = new PluginFusioninventoryMapping();
      $pfModel = new PluginFusioninventorySnmpmodel();

      $type = (string)$xml->type;
      switch ($type) {

         case '1':
            $type = "Computer";
            break;

         case '2':
            $type = "NetworkEquipment";
            break;

         case '3':
            $type = "Printer";
            break;

      }

      $input = array();
      $input['name']          = (string)$xml->name;
      $input['itemtype']      = $type;
      $input['discovery_key'] = (string)$xml->key;
      //$input['comment']       = Toolbox::clean_cross_side_scripting_deep(
      //                            Toolbox::addslashes_deep((string)$xml->comments));
      $plugin_fusioninventory_snmpmodels_id = $pfModel->add($input);

      $a_devices = array();
      if (isset($xml->devices)
              && isset($xml->devices->sysdescr)) {
         foreach ($xml->devices->sysdescr as $child) {
            $a_devices[] = (string)$child;
         }
      }
      $pfSnmpmodeldevice = new PluginFusioninventorySnmpmodeldevice();
      $pfSnmpmodeldevice->updateDevicesForModel($plugin_fusioninventory_snmpmodels_id, $a_devices);


      foreach($xml->oidlist->oidobject as $child) {
         $plugin_fusioninventory_snmpmodelmibobjects_id = 0;
         $plugin_fusioninventory_snmpmodelmiboids_id = 0;
         $oid_port_counter = 0;
         $oid_port_dyn = 0;
         $mapping_type = '';
         $mapping_name = '';
         $vlan = 0;
         $is_active = 1;
         $mappings_id = 0;

         if (isset($child->object)) {
            $plugin_fusioninventory_snmpmodelmibobjects_id = Dropdown::importExternal(
               "PluginFusioninventorySnmpmodelMibObject", $child->object);
         }
         if (isset($child->oid)) {
            $plugin_fusioninventory_snmpmodelmiboids_id = Dropdown::importExternal(
               "PluginFusioninventorySnmpmodelMibOid", $child->oid);
         }
         if (isset($child->portcounter)) {
            $oid_port_counter = $child->portcounter;
         }
         if (isset($child->dynamicport)) {
            $oid_port_dyn = $child->dynamicport;
         }
         if (isset($child->mapping_type)) {
            $mapping_type = '';
            switch ($child->mapping_type) {

               case '1':
                  $mapping_type = "Computer";
                  break;

               case '2':
                  $mapping_type = "NetworkEquipment";
                  break;

               case '3':
                  $mapping_type = "Printer";
                  break;

            }
            if ($mapping_type == '') {
               $mapping_type = $child->mapping_type;
            }
         }
         if (isset($child->mapping_name)) {
            $mapping_name = $child->mapping_name;
         }
         if (isset($child->vlan)) {
            $vlan = $child->vlan;
         }
         if (isset($child->activation)) {
            $is_active = $child->activation;
         }
         if (isset($mapping_type) AND isset($mapping_name)) {
            $a_mappings = $pfMapping->get($mapping_type, $mapping_name);
            $mappings_id = $a_mappings['id'];
         }
         if (!isset($mappings_id) OR empty($mappings_id)) {
            $mappings_id = '0';
         }

          $query = "INSERT INTO `glpi_plugin_fusioninventory_snmpmodelmibs`
             (
                `plugin_fusioninventory_snmpmodels_id`,
                `plugin_fusioninventory_snmpmodelmiboids_id`,
                `plugin_fusioninventory_snmpmodelmibobjects_id`,
                `oid_port_counter`,
                `oid_port_dyn`,
                `plugin_fusioninventory_mappings_id`,
                `vlan`,
                `is_active`
             )
             VALUES(
                '".$plugin_fusioninventory_snmpmodels_id."',
                '".$plugin_fusioninventory_snmpmodelmiboids_id."',
                '".$plugin_fusioninventory_snmpmodelmibobjects_id."',
                '".$oid_port_counter."',
                '".$oid_port_dyn."',
                '".$mappings_id."',
                '".$vlan."',
                '".$is_active."'
             );";
          $DB->query($query);
      }
   }




   /**
    * This function is used to import in one time all SNMP model in folder
    * fusioninventory/snmpmodels/
    */
   function importMass() {
      ini_set("max_execution_time", "0");
      $nb = 0;
      foreach (glob(GLPI_ROOT.'/plugins/fusioninventory/snmpmodels/*.xml') as $file) {
         $nb++;
      }
      echo "<table class='tab_cadre_fixe'>";
      echo "<tr class='tab_bg_1'>";
      echo "<th align='center'>";
      echo __('Importing SNMP models, please wait...', 'fusioninventory');
      echo "</th>";
      echo "</tr>";
      echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>";
      Html::createProgressBar(__('Importing SNMP models, please wait...', 'fusioninventory'));
      $i = 0;
      foreach (glob(GLPI_ROOT.'/plugins/fusioninventory/snmpmodels/*.xml') as $file) {
         $this->import($file, 0, 1);
         $i++;
         if (substr($i, -1) == '0') {
            Html::changeProgressBarPosition($i, $nb, "$i / $nb");
         }
      }
      Html::changeProgressBarPosition($nb, $nb, "$nb / $nb");
      echo "</td>";
      echo "</table>";
      PluginFusioninventorySnmpmodelImportExport::exportDictionnaryFile();
   }



   function import_netdiscovery($arrayinventory, $agentKey) {

      PluginFusioninventoryCommunication::addLog(
         'Function PluginFusioninventorySnmpmodelImportExport->import_netdiscovery().');

      $ptap = new PluginFusioninventoryStateDiscovery();
      $pta  = new PluginFusioninventoryAgent();

      $agent = $pta->InfosByKey($agentKey);

      if (isset($arrayinventory['AGENT']['START'])) {
         $ptap->updateState($arrayinventory['PROCESSNUMBER'],
                            array('start_time' => date("Y-m-d H:i:s")), $agent['id']);
      } else if (isset($arrayinventory['AGENT']['END'])) {
         $ptap->updateState($arrayinventory['PROCESSNUMBER'],
                            array('end_time' => date("Y-m-d H:i:s")), $agent['id']);
      } else if (isset($arrayinventory['AGENT']['EXIT'])) {
         $ptap->endState($arrayinventory['PROCESSNUMBER'], date("Y-m-d H:i:s"), $agent['id']);
      } else if (isset($arrayinventory['AGENT']['NBIP'])) {
         $ptap->updateState($arrayinventory['PROCESSNUMBER'],
                            array('nb_ip' => $arrayinventory['AGENT']['NBIP']), $agent['id']);
      }
      if (isset($arrayinventory['AGENT']['AGENTVERSION'])) {
         $agent['last_contact'] = date("Y-m-d H:i:s");
         $pta->update($agent);
      }
      $_SESSION['glpi_plugin_fusioninventory_agentid'] = $agent['id'];
      $count_discovery_devices = 0;
      if (isset($arrayinventory['DEVICE'])) {
         if (is_int(key($arrayinventory['DEVICE']))) {
            $count_discovery_devices = count($arrayinventory['DEVICE']);
         } else {
            $count_discovery_devices = 1;
         }
      }
      if ($count_discovery_devices != "0") {
         $ptap->updateState($_SESSION['glpi_plugin_fusioninventory_processnumber'],
                            array('nb_found' => $count_discovery_devices), $agent['id']);
         if (is_int(key($arrayinventory['DEVICE']))) {
            foreach($arrayinventory['DEVICE'] as $discovery) {
               if (count($discovery) > 0) {
                  $pfCommunicationNetworkDiscovery =
                                    new PluginFusioninventoryCommunicationNetworkDiscovery();
                  $pfCommunicationNetworkDiscovery->sendCriteria($discovery);
               }
            }
         } else {
            $pfCommunicationNetworkDiscovery =
                                    new PluginFusioninventoryCommunicationNetworkDiscovery();
            $pfCommunicationNetworkDiscovery->sendCriteria($arrayinventory['DEVICE']);
         }
      }
   }



   static function exportDictionnaryFile($enableright=TRUE) {
      global $DB;

      if (!strstr($_SERVER['PHP_SELF'], "front/plugin.php")
              && !strstr($_SERVER['PHP_SELF'], "front/plugin.form.php")
              &&  basename($_SERVER['PHP_SELF']) != "cli_install.php") {

         if ($enableright) {
            Session::checkRight('plugin_fusioninventory_model', READ);
         }
      }

      $xmlstr =   "<?xml version='1.0' encoding='UTF-8'?>".
                  "<SNMPDISCOVERY>".
                  "</SNMPDISCOVERY>";

      $xml = new SimpleXMLElement($xmlstr);

      $query = "SELECT * FROM `glpi_plugin_fusioninventory_snmpmodeldevices`
                LEFT JOIN `glpi_plugin_fusioninventory_snmpmodels`
                   ON `plugin_fusioninventory_snmpmodels_id`=".
                        "`glpi_plugin_fusioninventory_snmpmodels`.`id`
                ORDER BY `sysdescr`";

      $result=$DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         $device = $xml->addChild('DEVICE');
            $device->addChild('SYSDESCR', $data['sysdescr']);
//            $device->addChild('MANUFACTURER', $data['manufacturers_id']);
            switch ($data['itemtype']) {

               case 'Computer':
                  $device->addChild('TYPE', '1');
                  break;

               case 'NetworkEquipment':
                  $device->addChild('TYPE', '2');
                  break;

               case 'Printer':
                  $device->addChild('TYPE', '3');
                  break;

            }
            $device->addChild('MODELSNMP', $data['discovery_key']);

            $query_serial = "SELECT * FROM `glpi_plugin_fusioninventory_snmpmodelmibs`
                  LEFT JOIN `glpi_plugin_fusioninventory_mappings`
                     ON `glpi_plugin_fusioninventory_snmpmodelmibs`.".
                           "`plugin_fusioninventory_mappings_id`=
                        `glpi_plugin_fusioninventory_mappings`.`id`
               WHERE `plugin_fusioninventory_snmpmodels_id`='".
                    $data['plugin_fusioninventory_snmpmodels_id']."'
                  AND `name`='serial'
               LIMIT 1";
            $result_serial=$DB->query($query_serial);
            if ($DB->numrows($result_serial) > 0) {
               $line = $DB->fetch_assoc($result_serial);
               $device->addChild('SERIAL', Dropdown::getDropdownName(
                                            'glpi_plugin_fusioninventory_snmpmodelmiboids',
                                            $line['plugin_fusioninventory_snmpmodelmiboids_id']));
            }

            $query_serial = "SELECT * FROM `glpi_plugin_fusioninventory_snmpmodelmibs`
                  LEFT JOIN `glpi_plugin_fusioninventory_mappings`
                     ON `glpi_plugin_fusioninventory_snmpmodelmibs`.".
                           "`plugin_fusioninventory_mappings_id`=
                        `glpi_plugin_fusioninventory_mappings`.`id`
               WHERE `plugin_fusioninventory_snmpmodels_id`='".
                    $data['plugin_fusioninventory_snmpmodels_id']."'
                  AND ((`name`='macaddr' AND `itemtype`='NetworkEquipment')
                        OR ( `name`='ifPhysAddress' AND `itemtype`='Printer')
                        OR ( `name`='ifPhysAddress' AND `itemtype`='Computer'))
               LIMIT 1";
            $result_serial=$DB->query($query_serial);
            if ($DB->numrows($result_serial) > 0) {
               $line = $DB->fetch_assoc($result_serial);
               if ($line['name'] == "macaddr") {
                  $device->addChild('MAC', Dropdown::getDropdownName(
                                             'glpi_plugin_fusioninventory_snmpmodelmiboids',
                                             $line['plugin_fusioninventory_snmpmodelmiboids_id']));
               } else {
                  $device->addChild('MACDYN', Dropdown::getDropdownName(
                                             'glpi_plugin_fusioninventory_snmpmodelmiboids',
                                             $line['plugin_fusioninventory_snmpmodelmiboids_id']));
               }
            }
      }
      $xmlprint = PluginFusioninventoryToolbox::formatXML($xml);
      $xmlprint = str_replace("<SYSDESCR>", "<SYSDESCR><![CDATA[", $xmlprint);
      $xmlprint = str_replace("</SYSDESCR>", "]]></SYSDESCR>", $xmlprint);
      file_put_contents(GLPI_PLUGIN_DOC_DIR."/fusioninventory/discovery.xml", $xmlprint);
   }
}

?>
