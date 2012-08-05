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
   @since     2010
 
   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusinvsnmpModel extends CommonDBTM {

   function canCreate() {
      return PluginFusioninventoryProfile::haveRight("fusinvsnmp", "model", "w");
   }


   function canView() {
      return PluginFusioninventoryProfile::haveRight("fusinvsnmp", "model", "r");
   }


   function getSearchOptions() {
      global $LANG;

      $tab = array();

      $tab['common'] = $LANG['plugin_fusinvsnmp']['model_info'][4];

      $tab[1]['table'] = $this->getTable();
      $tab[1]['field'] = 'name';
      $tab[1]['linkfield'] = 'name';
      $tab[1]['name'] = $LANG['common'][16];
      $tab[1]['datatype'] = 'itemlink';

      $tab[2]['table'] = $this->getTable();
      $tab[2]['field'] = 'itemtype';
      $tab[2]['linkfield'] = 'itemtype';
      $tab[2]['name'] = $LANG['state'][6];

      $tab[5]['table'] = $this->getTable();
      $tab[5]['field'] = 'discovery_key';
      $tab[5]['linkfield'] = 'discovery_key';
      $tab[5]['name'] = $LANG['plugin_fusinvsnmp']['model_info'][12];

      $tab[6]['table'] = $this->getTable();
      $tab[6]['field'] = 'comment';
      $tab[6]['linkfield'] = 'comment';
      $tab[6]['name'] = $LANG['common'][25];

      return $tab;
   }



   function showForm($id, $options=array()) {
      global $CFG_GLPI,$LANG;

      PluginFusioninventoryProfile::checkRight("fusinvsnmp", "model","r");

      if ($id!='') {
         $this->getFromDB($id);
      } else {
         $this->getEmpty();   
      }

      $target = $CFG_GLPI['root_doc'].'/plugins/fusinvsnmp/front/model.form.php';
            $this->showTabs($id);
      echo "<div align='center'><form method='post' name='' id=''  action=\"" . $target . "\">";

      echo "<table class='tab_cadre' cellpadding='5' width='950'><tr><th colspan='2'>";
      echo ($id =='' ? $LANG['plugin_fusinvsnmp']['model_info'][7] :
            $LANG['plugin_fusinvsnmp']['model_info'][6]);
      echo " :</th></tr>";


      echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>" . $LANG["common"][16] . "</td>";
      echo "<td align='center'>";
      echo "<input type='text' name='name' value='" . $this->fields["name"] . "' size='35'/>";
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>".$LANG["common"][17]."</td>";
      echo "<td align='center'>";

      $selected_value = $this->fields["itemtype"];
      $selected = '';
      echo "<select name='itemtype'>\n";
      if ($selected_value == "0"){$selected = 'selected';}else{$selected = '';}
      echo "<option value='0' ".$selected.">-----</option>\n";
      if ($selected_value == COMPUTER_TYPE){$selected = 'selected';}else{$selected = '';}
      echo "<option value='".COMPUTER_TYPE."' ".$selected.">".$LANG["Menu"][0]."</option>\n";
      if ($selected_value == NETWORKING_TYPE){$selected = 'selected';}else{$selected = '';}
      echo "<option value='".NETWORKING_TYPE."' ".$selected.">".$LANG["Menu"][1]."</option>\n";
      if ($selected_value == PRINTER_TYPE){$selected = 'selected';}else{$selected = '';}
      echo "<option value='".PRINTER_TYPE."' ".$selected.">".$LANG["Menu"][2]."</option>\n";
      if ($selected_value == PERIPHERAL_TYPE){$selected = 'selected';}else{$selected = '';}
      echo "<option value='".PERIPHERAL_TYPE."' ".$selected.">".$LANG["Menu"][16]."</option>\n";
      if ($selected_value == PHONE_TYPE){$selected = 'selected';}else{$selected = '';}
      echo "<option value='".PHONE_TYPE."' ".$selected.">".$LANG["Menu"][34]."</option>\n";
      echo "</select>";
      
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>" . $LANG['common'][25] . "</td>";
      echo "<td align='center'>";
      echo nl2br($this->fields["comment"]);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_2'><td colspan='2'>";
      if(PluginFusioninventoryProfile::haveRight("fusinvsnmp", "model","w")) {
         if ($id=='') {
            echo "<div align='center'><input type='submit' name='add' value=\"" . $LANG["buttons"][8] .
                 "\" class='submit' >";
         } else {
            echo "<input type='hidden' name='id' value='" . $id . "'/>";
            echo "<div align='center'><input type='submit' name='update' value=\"".$LANG["buttons"][7].
                 "\" class='submit' >";
            echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' name='delete' value=\"" .
                    $LANG["buttons"][6] . "\" class='submit'>";
         }
      }
      echo "</td>";
      echo "</tr>";
      echo "</table>";
      Html::closeForm();
      echo "</div>";
      
      echo "<br/>";
      $pfModeldevice = new PluginFusinvsnmpModeldevice();
      $pfModeldevice->showDevices($id);
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
   function oidlist($ID_Device,$type) {
      global $DB;

      $oids = array();
      $query = "";
      
      switch ($type) {

         case NETWORKING_TYPE :
            $query = "SELECT * 
                      FROM `glpi_plugin_fusinvsnmp_networkequipments`
                           LEFT JOIN `glpi_plugin_fusinvsnmp_modelmibs`
                           ON `glpi_plugin_fusinvsnmp_networkequipments`.`plugin_fusinvsnmp_models_id`=
                              `glpi_plugin_fusinvsnmp_modelmibs`.`plugin_fusinvsnmp_models_id`
                      WHERE `networkequipments_id`='".$ID_Device."'
                            AND `glpi_plugin_fusinvsnmp_modelmibs`.`is_active`='1' ";
            break;

         case PRINTER_TYPE :
            $query = "SELECT `glpi_plugin_fusinvsnmp_printers`.*,
                        `glpi_plugin_fusinvsnmp_modelmibs`.*,
                        `glpi_plugin_fusioninventory_mappings`.`name` AS `mapping_name`
                      FROM `glpi_plugin_fusinvsnmp_printers`
                           LEFT JOIN `glpi_plugin_fusinvsnmp_modelmibs`
                              ON `glpi_plugin_fusinvsnmp_printers`.`plugin_fusinvsnmp_models_id`=
                                 `glpi_plugin_fusinvsnmp_modelmibs`.`plugin_fusinvsnmp_models_id`
                           LEFT JOIN `glpi_plugin_fusioninventory_mappings`
                              ON `glpi_plugin_fusinvsnmp_modelmibs`.`plugin_fusioninventory_mappings_id`=
                                 `glpi_plugin_fusioninventory_mappings`.`id`
                      WHERE `printers_id`='".$ID_Device."'
                            AND `glpi_plugin_fusinvsnmp_modelmibs`.`is_active`='1' ";
            break;

      }
      if (!empty($query)) {
         $result=$DB->query($query);
         while ($data=$DB->fetch_array($result)) {
            $oids[$data['oid_port_counter']][$data['oid_port_dyn']][$data['mapping_name']] =
               Dropdown::getDropdownName('glpi_plugin_fusinvsnmp_miboids',$data['plugin_fusinvsnmp_miboids_id']);
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
               $pfNetworkEquipment = new PluginFusinvsnmpCommonDBTM("glpi_plugin_fusinvsnmp_networkequipments");
               $NetworkEquipment = new NetworkEquipment();
               if($NetworkEquipment->getFromDB($device_id)) {
                  $NetworkEquipment->check($device_id,'r');
                  $a_data = $pfNetworkEquipment->find("`networkequipments_id`='".$device_id."'", "", "1");
                  $data = current($a_data);
                  $sysdescr = $data["sysdescr"];
               } else {
                  // Delete, device deleted
                  $a_data = $pfNetworkEquipment->find("`networkequipments_id`='".$device_id."'", "", "1");
                  $data = current($a_data);
                  $pfNetworkEquipment->delete($data);
                  $sysdescr = '';
               }
               break;

            case 'Printer':
               $pfPrinter = new PluginFusinvsnmpCommonDBTM("glpi_plugin_fusinvsnmp_printers");
               $Printer = new Printer();
               if($Printer->getFromDB($device_id)) {
                  $Printer->check($device_id,'r');
                  $a_data = $pfPrinter->find("`printers_id`='".$device_id."'", "", "1");
                  $data = current($a_data);
                  $sysdescr = $data["sysdescr"];
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
         $xml = @simplexml_load_file(GLPI_PLUGIN_DOC_DIR.'/fusinvsnmp/discovery.xml','SimpleXMLElement', LIBXML_NOCDATA);
         foreach ($xml->DEVICE as $device) {
            $device->SYSDESCR = str_replace("\r", "", $device->SYSDESCR);
            $device->SYSDESCR = str_replace("\n", "", $device->SYSDESCR);
            $device->SYSDESCR = trim($device->SYSDESCR);
            if ($sysdescr == $device->SYSDESCR) {
               if (isset($device->MODELSNMP)) {
                  $modelgetted = $device->MODELSNMP;
               }
               break;
            }
         }

         if (!empty($modelgetted)) {
            $query = "SELECT * 
                      FROM `glpi_plugin_fusinvsnmp_models`
                      WHERE `discovery_key`='".$modelgetted."'
                      LIMIT 0,1";
            $result = $DB->query($query);
            $data = $DB->fetch_assoc($result);
            $plugin_fusinvsnmp_models_id = $data['id'];
            if ($comment != "") {
               return $data['discovery_key'];
            } else {
               // Udpate Device with this model
               switch($type) {

                  case 'NetworkEquipment':
                     $query = "UPDATE `glpi_plugin_fusinvsnmp_networkequipments`
                               SET `plugin_fusinvsnmp_models_id`='".$plugin_fusinvsnmp_models_id."'
                               WHERE `networkequipments_id`='".$device_id."'";
                     $DB->query($query);
                     break;

                  case 'Printer':
                     $query = "UPDATE `glpi_plugin_fusinvsnmp_printers`
                               SET `plugin_fusinvsnmp_models_id`='".$plugin_fusinvsnmp_models_id."'
                               WHERE `printers_id`='".$device_id."'";
                     $DB->query($query);
                     break;
               }
            }
         }
      }
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
      if (isset($key) AND !empty($key)) {
         $model_id = $this->getModelByKey($key);
         if (isset($model_id) AND !empty($model_id)) {
            return $model_id;
         }
      }
      return 0;
   }
   


   static function importAllModels($folder='') {
      /*
       * Manage models migration
       */
      
      if ($folder == '') {
         $folder = GLPI_ROOT.'/plugins/fusinvsnmp/models';
      }
      
      $NewModelList = array();
      foreach (glob($folder.'/*.xml') as $file) {
         $split = explode("/", $file);
         $file = end($split);
         $NewModelList[$file] = 1;
      }


      // Delete old models
      $pfModel = new PluginFusinvsnmpModel();
      $a_models = $pfModel->find("");
      foreach ($a_models as $a_model) {
         if (!isset($NewModelList[$a_model['name'].".xml"])) {
            $pfModel->delete($a_model, 1);
         }
      }

      // Import models
      $importexport = new PluginFusinvsnmpImportExport();

      $nb = 0;
      foreach (glob($folder.'/*.xml') as $file) {
         $nb++;
      }
      $i = 0;
      echo "<table class='tab_cadre_fixe'>";
      echo "<tr class='tab_bg_1'>";
      echo "<th align='center'>";
      echo "Importing SNMP models, please wait...";
      echo "</th>";
      echo "</tr>";
      echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>";
      Html::createProgressBar("Importing SNMP models, please wait...");
      foreach (glob($folder.'/*.xml') as $file) {
         $importexport->import($file,0,1);
         $i++;
         if (substr($i, -1) == '0') {
            Html::changeProgressBarPosition($i,$nb,"$i / $nb");
         }
      }
      Html::changeProgressBarPosition($nb,$nb,"$nb / $nb");
      echo "</td>";
      echo "</table>";

      // Reload model for networkequipment have sysdescr
      $networkequipmentext = new PluginFusinvsnmpCommonDBTM("glpi_plugin_fusinvsnmp_networkequipments");
      $a_networkequipments = $networkequipmentext->find("`sysdescr`!=''");
      foreach ($a_networkequipments as $a_networkequipment) {
         $pfModel->getrightmodel($a_networkequipment['networkequipments_id'], "NetworkEquipment");
      }
      // Reload model for printers have sysdescr
      $printerext = new PluginFusinvsnmpCommonDBTM("glpi_plugin_fusinvsnmp_printers");
      $a_printers = $printerext->find("`sysdescr`!=''");
      foreach ($a_printers as $a_printer) {
         $pfModel->getrightmodel($a_printer['printers_id'], "Printer");
      }
      PluginFusinvsnmpImportExport::exportDictionnaryFile();
   }
}

?>