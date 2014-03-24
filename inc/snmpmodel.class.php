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
   @since     2010

   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access this file directly");
}

class PluginFusioninventorySnmpmodel extends CommonDBTM {

   /**
   * Get name of this type
   *
   * @return text name of this type by language of the user connected
   *
   **/
   static function getTypeName($nb=0) {

      return __('SNMP models', 'fusioninventory');

   }



   static function canCreate() {
      return PluginFusioninventoryProfile::haveRight("model", "w");
   }


   static function canView() {
      return PluginFusioninventoryProfile::haveRight("model", "r");
   }


   function getSearchOptions() {

      $tab = array();

      $tab['common'] = __('SNMP models', 'fusioninventory');


      $tab[1]['table'] = $this->getTable();
      $tab[1]['field'] = 'name';
      $tab[1]['linkfield'] = 'name';
      $tab[1]['name'] = __('Name');

      $tab[1]['datatype'] = 'itemlink';

      $tab[2]['table'] = $this->getTable();
      $tab[2]['field'] = 'itemtype';
      $tab[2]['linkfield'] = 'itemtype';
      $tab[2]['name'] = __('Item type');


      $tab[5]['table'] = $this->getTable();
      $tab[5]['field'] = 'discovery_key';
      $tab[5]['linkfield'] = 'discovery_key';
      $tab[5]['name'] = __('Key of model discovery', 'fusioninventory');


      $tab[6]['table'] = $this->getTable();
      $tab[6]['field'] = 'comment';
      $tab[6]['linkfield'] = 'comment';
      $tab[6]['name'] = __('Comments');


      $tab[7]['table'] = "glpi_plugin_fusioninventory_snmpmodeldevices";
      $tab[7]['field'] = 'sysdescr';
      $tab[7]['name'] = __('Sysdescr', 'fusioninventory');
      $tab[7]['forcegroupby']  = TRUE;
      $tab[7]['splititems']    = TRUE;


      return $tab;
   }



   function showForm($id, $options=array()) {
      global $CFG_GLPI;

      PluginFusioninventoryProfile::checkRight("model", "r");

      if ($id!='') {
         $this->getFromDB($id);
      } else {
         $this->getEmpty();
      }

      $target = $CFG_GLPI['root_doc'].'/plugins/fusioninventory/front/snmpmodel.form.php';
            $this->showTabs($id);
      echo "<div align='center'><form method='post' name='' id=''  action=\"" . $target . "\">";

      echo "<table class='tab_cadre' cellpadding='5' width='950'><tr><th colspan='2'>";
      echo ($id =='' ? __('Create SNMP model', 'fusioninventory') :
            __('Edit SNMP model', 'fusioninventory'));

      echo " :</th></tr>";


      echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>" . __('Name') . "</td>";
      echo "<td align='center'>";
      echo "<input type='text' name='name' value='" . $this->fields["name"] . "' size='35'/>";
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>".__('Type')."</td>";
      echo "<td align='center'>";

      $selected_value = $this->fields["itemtype"];
      $selected = '';
      echo "<select name='itemtype'>\n";
      if ($selected_value == "0"){$selected = 'selected';}else{$selected = '';}
      echo "<option value='0' ".$selected.">-----</option>\n";
      if ($selected_value == COMPUTER_TYPE){$selected = 'selected';}else{$selected = '';}
      echo "<option value='".COMPUTER_TYPE."' ".$selected.">"._n('Computer', 'Computers', 2).
              "</option>\n";
      if ($selected_value == NETWORKING_TYPE){$selected = 'selected';}else{$selected = '';}
      echo "<option value='".NETWORKING_TYPE."' ".$selected.">".
              _n('Network device', 'Network devices', 2)."</option>\n";
      if ($selected_value == PRINTER_TYPE){$selected = 'selected';}else{$selected = '';}
      echo "<option value='".PRINTER_TYPE."' ".$selected.">".
              _n('Printer', 'Printers', 2)."</option>\n";
      if ($selected_value == PERIPHERAL_TYPE){$selected = 'selected';}else{$selected = '';}
      echo "<option value='".PERIPHERAL_TYPE."' ".$selected.">"._n('Device', 'Devices', 2).
              "</option>\n";
      if ($selected_value == PHONE_TYPE){$selected = 'selected';}else{$selected = '';}
      echo "<option value='".PHONE_TYPE."' ".$selected.">"._n('Phone', 'Phones', 2)."</option>\n";
      echo "</select>";

      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>" . __('Discovery key', 'fusioninventory') . "</td>";
      echo "<td align='center'>";
      echo $this->fields["discovery_key"];
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>" . __('Comments') . "</td>";
      echo "<td align='center'>";
      echo nl2br($this->fields["comment"]);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_2'><td colspan='2'>";
      if(PluginFusioninventoryProfile::haveRight("model", "w")) {
         if ($id=='') {
            echo "<div align='center'><input type='submit' name='add' value=\"" . __('Add') .

                 "\" class='submit' >";
         } else {
            echo "<input type='hidden' name='id' value='" . $id . "'/>";
            echo "<div align='center'><input type='submit' name='update' value=\"".__('Update').

                 "\" class='submit' >";
            echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' name='delete' value=\"" .
                    __('Delete', 'fusioninventory') . "\" class='submit'>";
         }
      }
      echo "</td>";
      echo "</tr>";
      echo "</table>";
      Html::closeForm();
      echo "</div>";

      echo "<br/>";
      $pfSnmpmodeldevice = new PluginFusioninventorySnmpmodeldevice();
      $pfSnmpmodeldevice->showDevices($id);
   }



   /**
    * Get all OIDs from model
    *
    * @param $ID_Device id of the device
    * @param $type type of device (NETWORKING_TYPE, PRINTER_TYPE ...)
    *
    * @return OID list in array
    *
   **/
   function oidlist($ID_Device, $type) {
      global $DB;

      $oids = array();
      $query = "";

      switch ($type) {

         case NETWORKING_TYPE :
            $query = "SELECT *
                      FROM `glpi_plugin_fusioninventory_networkequipments`
                           LEFT JOIN `glpi_plugin_fusioninventory_snmpmodelmibs`
                           ON `glpi_plugin_fusioninventory_networkequipments`.".
                                 "`plugin_fusioninventory_snmpmodels_id`=
                              `glpi_plugin_fusioninventory_snmpmodelmibs`.".
                                 "`plugin_fusioninventory_snmpmodels_id`
                      WHERE `networkequipments_id`='".$ID_Device."'
                            AND `glpi_plugin_fusioninventory_snmpmodelmibs`.`is_active`='1' ";
            break;

         case PRINTER_TYPE :
            $query = "SELECT `glpi_plugin_fusioninventory_printers`.*,
                        `glpi_plugin_fusioninventory_snmpmodelmibs`.*,
                        `glpi_plugin_fusioninventory_mappings`.`name` AS `mapping_name`
                      FROM `glpi_plugin_fusioninventory_printers`
                           LEFT JOIN `glpi_plugin_fusioninventory_snmpmodelmibs`
                              ON `glpi_plugin_fusioninventory_printers`.".
                                    "`plugin_fusioninventory_snmpmodels_id`=
                                 `glpi_plugin_fusioninventory_snmpmodelmibs`.".
                                    "`plugin_fusioninventory_snmpmodels_id`
                           LEFT JOIN `glpi_plugin_fusioninventory_mappings`
                              ON `glpi_plugin_fusioninventory_snmpmodelmibs`.".
                                    "`plugin_fusioninventory_mappings_id`=
                                 `glpi_plugin_fusioninventory_mappings`.`id`
                      WHERE `printers_id`='".$ID_Device."'
                            AND `glpi_plugin_fusioninventory_snmpmodelmibs`.`is_active`='1' ";
            break;

      }
      if (!empty($query)) {
         $result=$DB->query($query);
         while ($data=$DB->fetch_array($result)) {
            $oids[$data['oid_port_counter']][$data['oid_port_dyn']][$data['mapping_name']] =
               Dropdown::getDropdownName('glpi_plugin_fusioninventory_snmpmodelmiboids',
                                         $data['plugin_fusioninventory_snmpmodelmiboids_id']);
         }
         return $oids;
      }
   }



   function getrightmodel($device_id, $type, $comment="") {
      global $DB;

      // Get description (sysdescr) of device
      // And search in device_serials base
      $sysdescr = '';
      if ($comment != "") {
         $sysdescr = $comment;
      } else {
         switch($type) {

            case 'NetworkEquipment':
               $pfNetworkEquipment = new PluginFusioninventoryNetworkEquipment();
               $NetworkEquipment = new NetworkEquipment();
               if($NetworkEquipment->getFromDB($device_id)) {
                  if ($NetworkEquipment->can($device_id, 'r')) {
                     $a_data = $pfNetworkEquipment->find("`networkequipments_id`='".$device_id."'",
                                                      "", "1");
                     $data = current($a_data);
                     $sysdescr = $data["sysdescr"];
                  }
               } else {
                  // Delete, device deleted
                  $a_data = $pfNetworkEquipment->find("`networkequipments_id`='".$device_id."'",
                                                      "", "1");
                  $data = current($a_data);
                  $pfNetworkEquipment->delete($data);
                  $sysdescr = '';
               }
               break;

            case 'Printer':
               $pfPrinter = new PluginFusioninventoryPrinter();
               $Printer = new Printer();
               if($Printer->getFromDB($device_id)) {
                  if ($Printer->can($device_id, 'r')) {
                     $a_data = $pfPrinter->find("`printers_id`='".$device_id."'", "", "1");
                     $data = current($a_data);
                     $sysdescr = $data["sysdescr"];
                  }
               } else {
                  // Delete, device deleted
                  $a_data = $pfPrinter->find("`printers_id`='".$device_id."'", "", "1");
                  $data = current($a_data);
                  $pfPrinter->delete($data);
                  $sysdescr = '';
               }
               break;

         }
      }
      $sysdescr = str_replace("\r", "", $sysdescr);
      $sysdescr = str_replace("\n", "", $sysdescr);
      $sysdescr = trim($sysdescr);
      $modelgetted = '';
      if (!empty($sysdescr)) {
         $pfSnmpmodeldevice = new PluginFusioninventorySnmpmodeldevice();
         $a_devices = $pfSnmpmodeldevice->find("`sysdescr`='".$sysdescr."'", '', 1);
         if (count($a_devices) == 1) {
            $a_device = current($a_devices);
            $plugin_fusinvsnmp_models_id = $a_device['plugin_fusioninventory_snmpmodels_id'];
            if ($comment != "") {
               return $plugin_fusinvsnmp_models_id;
            } else {
               // Udpate Device with this model
               switch($type) {

                  case 'NetworkEquipment':
                     $query = "UPDATE `glpi_plugin_fusioninventory_networkequipments`
                               SET `plugin_fusioninventory_snmpmodels_id`='".
                                  $plugin_fusinvsnmp_models_id."'
                               WHERE `networkequipments_id`='".$device_id."'";
                     $DB->query($query);
                     break;

                  case 'Printer':
                     $query = "UPDATE `glpi_plugin_fusioninventory_printers`
                               SET `plugin_fusioninventory_snmpmodels_id`='".
                                  $plugin_fusinvsnmp_models_id."'
                               WHERE `printers_id`='".$device_id."'";
                     $DB->query($query);
                     break;
               }
            }
         }
      }
      return '';
   }



   function getModelByKey($key) {
      $a_models = $this->find("`discovery_key`='".$key."'");
      if (count($a_models) > 0) {
         $a_model = current($a_models);
         return $a_model['id'];
      } else {
         return 0;
      }
   }



   function getModelBySysdescr($sysdescr) {
      $key = $this->getrightmodel('0', '', $sysdescr);
      if ($key != '') {
         return $key;
      }
      return 0;
   }



   static function importAllModels($folder='',$mode_cli=FALSE) {
      global $DB;

      if ( $mode_cli && defined('NO_MODELS_UPDATE') ) {
         return TRUE;
      }
      /*
       * Manage models migration
       */
      $NewModelList = array();
      foreach (glob(GLPI_ROOT.'/plugins/fusioninventory/snmpmodels/*.xml') as $file) {
         $file = str_replace("../plugins/fusioninventory/snmpmodels/", "", $file);
         $NewModelList[$file] = 1;
      }


      // Delete old models
      $pfModel = new PluginFusioninventorySnmpmodel();
      $a_models = $pfModel->find("");
      foreach ($a_models as $a_model) {
         if (!isset($NewModelList[$a_model['name'].".xml"])) {
            $pfModel->delete($a_model, 1);
         }
      }

      // Import models
      $importexport = new PluginFusioninventorySnmpmodelImportExport();

      $nb = 0;
      foreach (glob(GLPI_ROOT.'/plugins/fusioninventory/snmpmodels/*.xml') as $file) {
         $nb++;
      }
      $i = 0;
      if ($mode_cli) {
         print("Importing SNMP models, please wait...\n");
      } else {
         echo "<table class='tab_cadre_fixe'>";
         echo "<tr class='tab_bg_1'>";
         echo "<th align='center'>";
         echo __('Importing SNMP models, please wait...', 'fusioninventory');
         echo "</th>";
         echo "</tr>";
         echo "<tr class='tab_bg_1'>";
         echo "<td align='center'>";
         Html::createProgressBar(__('Importing SNMP models, please wait...', 'fusioninventory'));
      }
      foreach (glob(GLPI_ROOT.'/plugins/fusioninventory/snmpmodels/*.xml') as $file) {
         $importexport->import($file, 0, 1);
         $i++;
         if (substr($i, -1) == '0') {
            if ($mode_cli) {
               print("$i/$nb\n");
            } else {
               Html::changeProgressBarPosition($i, $nb, "$i / $nb");
            }
         }
      }
      if ($mode_cli) {
         print("$i/$nb\n");
      } else {
         Html::changeProgressBarPosition($nb, $nb, "$nb / $nb");
         echo "</td>";
         echo "</table>";
      }

      PluginFusioninventorySnmpmodelImportExport::exportDictionnaryFile();

//      // Reload model for networkequipment have sysdescr
//      $pfNetworkEquipment = new PluginFusioninventoryNetworkEquipment();
//      $a_networkequipments = $pfNetworkEquipment->find("`sysdescr`!=''");
//      foreach ($a_networkequipments as $a_networkequipment) {
//         $pfModel->getrightmodel($a_networkequipment['networkequipments_id'], "NetworkEquipment");
//      }
//      // Reload model for printers have sysdescr
//      $pfPrinter = new PluginFusioninventoryPrinter();;
//      $a_printers = $pfPrinter->find("`sysdescr`!=''");
//      foreach ($a_printers as $a_printer) {
//         $pfModel->getrightmodel($a_printer['printers_id'], "Printer");
//      }
   }



   /**
    * Actions done after the DELETE of the item in the database
    *
    *@return nothing
   **/
   function post_deleteFromDB() {
      global $DB;

      $query = "SELECT `glpi_plugin_fusioninventory_printers`.`id`
                FROM `glpi_plugin_fusioninventory_printers`
                WHERE `plugin_fusioninventory_snmpmodels_id`='".$this->fields['id']."' ";
      $result = $DB->query($query);
      $pfPrinter = new PluginFusioninventoryPrinter();
      while ($data=$DB->fetch_array($result)) {
         $pfPrinter->update(array(
             'id' => $data['id'],
             'plugin_fusioninventory_snmpmodels_id' => 0
         ));
      }
      $query = "SELECT `glpi_plugin_fusioninventory_networkequipments`.`id`
                FROM `glpi_plugin_fusioninventory_networkequipments`
                WHERE `plugin_fusioninventory_snmpmodels_id`='".$this->fields['id']."' ";
      $result = $DB->query($query);
      $pfNetworkEquipment = new PluginFusioninventoryNetworkEquipment();
      while ($data=$DB->fetch_array($result)) {
         $pfNetworkEquipment->update(array(
             'id' => $data['id'],
             'plugin_fusioninventory_snmpmodels_id' => 0
         ));
      }
   }

}

?>
