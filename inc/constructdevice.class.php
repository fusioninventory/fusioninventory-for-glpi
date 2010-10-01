<?php

/*
 * @version $Id$
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copynetwork (C) 2003-2006 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org/
 ----------------------------------------------------------------------

 LICENSE

 This file is part of GLPI.

 GLPI is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 GLPI is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 ------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: DURIEUX David
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

class PluginFusionInventoryConstructDevice extends CommonDBTM {
   private $sysdescr='',$a_cartridge=array(), $a_pagecounter=array();

   function __construct() {
		$this->table = "glpi_plugin_fusioninventory_construct_device";
		$this->type = PLUGIN_FUSIONINVENTORY_CONSTRUCT_DEVICE;
	}


   private function mibDetection() {

      $mapping_pre = array();
      
      // List of OID with relations by default with mapping
      $mapping_pre[2]['.1.3.6.1.4.1.9.9.23.1.2.1.1.4']      = 'cdpCacheAddress';
      $mapping_pre[2]['.1.0.8802.1.1.2.1.4.1.1.5']          = 'lldpRemChassisId';

      // LLDP, try to see in SNMPv2-SMI::enterprises.9.9.23.1.2.1.1.6
      $mapping_pre[2]['.1.3.6.1.4.1.9.9.23.1.2.1.1.7']      = 'cdpCacheDevicePort';
      $mapping_pre[2]['.1.0.8802.1.1.2.1.4.1.1.7']          = 'lldpRemPortId';
      $mapping_pre[2]['.1.0.8802.1.1.2.1.3.2.0']             = 'lldpLocChassisId';
      $mapping_pre[2]['.1.3.6.1.2.1.1.1.0']                 = 'comments';
      $mapping_pre[2]['.1.3.6.1.4.1.9.9.109.1.1.1.1.3.1']   = 'cpu';
      $mapping_pre[2]['.1.3.6.1.2.1.17.1.4.1.2']            = 'dot1dBasePortIfIndex';
      $mapping_pre[2]['.1.3.6.1.2.1.17.4.3.1.1']            = 'dot1dTpFdbAddress';
      $mapping_pre[2]['.1.3.6.1.2.1.17.4.3.1.2']            = 'dot1dTpFdbPort';
      $mapping_pre[2]['.1.3.6.1.2.1.47.1.1.1.1.13.1001']    = 'entPhysicalModelName';
      $mapping_pre[2]['.1.3.6.1.2.1.47.1.1.1.1.13.2']       = 'entPhysicalModelName';
      $mapping_pre[2]['.1.3.6.1.2.1.47.1.1.1.1.9.1001']     = 'firmware';
      $mapping_pre[2]['.1.3.6.1.2.1.47.1.1.1.1.9.1000']     = 'firmware';
      $mapping_pre[2]['.1.3.6.1.2.1.2.2.1.2']               = 'ifdescr';
      $mapping_pre[2]['.1.3.6.1.2.1.2.2.1.1']               = 'ifIndex';
      $mapping_pre[2]['.1.3.6.1.2.1.2.2.1.14']              = 'ifinerrors';
      $mapping_pre[2]['.1.3.6.1.2.1.2.2.1.10']              = 'ifinoctets';
      $mapping_pre[2]['.1.3.6.1.2.1.2.2.1.7']               = 'ifinternalstatus';
      $mapping_pre[2]['.1.3.6.1.2.1.2.2.1.9']               = 'iflastchange';
      $mapping_pre[2]['.1.3.6.1.2.1.2.2.1.4']               = 'ifmtu';
      $mapping_pre[2]['.1.3.6.1.2.1.31.1.1.1.1']            = 'ifName';
      $mapping_pre[2]['.1.3.6.1.2.1.2.2.1.20']              = 'ifouterrors';
      $mapping_pre[2]['.1.3.6.1.2.1.2.2.1.16']              = 'ifoutoctets';
      $mapping_pre[2]['.1.3.6.1.2.1.2.2.1.6']               = 'ifPhysAddress';
      $mapping_pre[2]['.1.3.6.1.2.1.2.2.1.5']               = 'ifspeed';
      $mapping_pre[2]['.1.3.6.1.2.1.2.2.1.8']               = 'ifstatus';
      $mapping_pre[2]['.1.3.6.1.2.1.2.2.1.3']               = 'ifType';
      $mapping_pre[2]['.1.3.6.1.2.1.4.20.1.1']              = 'ipAdEntAddr';
      $mapping_pre[2]['.1.3.6.1.2.1.4.22.1.2']              = 'ipNetToMediaPhysAddress';
      $mapping_pre[2]['.1.3.6.1.2.1.1.6.0']                 = 'location';
      $mapping_pre[2]['.1.3.6.1.2.1.17.1.1.0']              = 'macaddr';
      $mapping_pre[2]['.1.3.6.1.4.1.9.2.1.8.0']             = 'memory';
      $mapping_pre[2]['.1.3.6.1.2.1.1.5.0']                 = 'name';
      $mapping_pre[2]['.1.3.6.1.4.1.9.3.6.6.0']             = 'ram';
      $mapping_pre[2]['.1.3.6.1.2.1.47.1.1.1.1.11.1001']    = 'serial';
      $mapping_pre[2]['.1.3.6.1.2.1.47.1.1.1.1.11.1']       = 'serial';
      $mapping_pre[2]['.1.3.6.1.2.1.47.1.1.1.1.11.2']       = 'serial';
      $mapping_pre[2]['.1.3.6.1.2.1.1.3.0']                 = 'uptime';
      $mapping_pre[2]['.1.3.6.1.4.1.9.9.46.1.6.1.1.14']     = 'vlanTrunkPortDynamicStatus';
      $mapping_pre[2]['.1.3.6.1.4.1.9.9.68.1.2.2.1.2']      = 'vmvlan';
      $mapping_pre[2]['.1.3.6.1.4.1.9.9.46.1.3.1.1.4.1']    = 'vtpVlanName';
      $mapping_pre[2]['.1.3.6.1.2.1.17.7.1.4.3.1.1']        = 'vtpVlanName';
      $mapping_pre[2]['.1.3.6.1.2.1.47.1.1.1.1.9.1']        = 'firmware1';
      $mapping_pre[2]['.1.3.6.1.2.1.47.1.1.1.1.10.1']       = 'firmware2';
      $mapping_pre[2]['.1.3.6.1.2.1.47.1.1.1.1.13.1']       = 'entPhysicalModelName';
      $mapping_pre[2]['.1.3.6.1.4.1.171.10.37.20.1.8.0']    = 'entPhysicalModelName';
      $mapping_pre[2]['.1.3.6.1.4.1.171.10.37.20.1.9.0']    = 'firmware';
      $mapping_pre[2]['.1.3.6.1.2.1.2.1.0']                 = '';
      // Omnistack LS6200 :
         $mapping_pre[2]['.1.3.6.1.2.1.47.1.1.1.1.11.67108992'] = 'serial';
      // 3Com
         $mapping_pre[2]['.1.3.6.1.4.1.43.10.27.1.1.1.13.1'] = 'serial';
         $mapping_pre[2]['.1.3.6.1.4.1.43.10.27.1.1.1.19.1'] = 'entPhysicalModelName';
         /*
          * firmware: iso.3.6.1.2.1.47.1.1.1.1.10.67108992
          * modele: iso.3.6.1.2.1.47.1.1.1.1.2.68420352
          * MAC: .1.3.6.1.2.1.17.1.1.0
          *
          */


      $mapping_pre[3]['.1.3.6.1.4.1.641.2.1.2.1.2.1']                = 'model';
      $mapping_pre[3]['.1.3.6.1.4.1.641.2.1.2.1.6.1']                = 'serial';
      $mapping_pre[3]['.1.3.6.1.2.1.25.2.3.1.5.1']                   = 'memory';
      $mapping_pre[3]['.1.3.6.1.2.1.43.8.2.1.14.1.1']                = 'enterprise';
      $mapping_pre[3]['.1.3.6.1.2.1.4.20.1.2']                       = 'ifaddr';
      $mapping_pre[3]['.1.3.6.1.2.1.2.2.1.2']                        = 'ifName';
      $mapping_pre[3]['.1.3.6.1.2.1.2.2.1.6']                        = 'ifPhysAddress';
      $mapping_pre[3]['.1.3.6.1.2.1.2.2.1.3']                        = 'ifType';
      $mapping_pre[3]['.1.3.6.1.2.1.1.5.0']                          = 'name';
      $mapping_pre[3]['.1.3.6.1.4.1.367.3.2.1.7.3.5.1.1.3.1.1']      = 'name';
      $mapping_pre[3]['.1.3.6.1.2.1.1.6.0']                          = 'location';
      $mapping_pre[3]['.1.3.6.1.2.1.1.1.0']                          = 'comments';
      $mapping_pre[3]['.1.3.6.1.2.1.2.2.1.1']                        = 'ifIndex';
      $mapping_pre[3]['.1.3.6.1.4.1.367.3.2.1.2.1.4.0']              = 'serial';
      $mapping_pre[3]['.1.3.6.1.4.1.367.3.2.1.2.24.1.1.5.2']         = 'cartridgescyan';
      $mapping_pre[3]['.1.3.6.1.4.1.367.3.2.1.2.24.1.1.5.1']         = 'cartridgesblack';
      $mapping_pre[3]['.1.3.6.1.4.1.367.3.2.1.2.24.1.1.5.3']         = 'cartridgesmagenta';
      $mapping_pre[3]['.1.3.6.1.4.1.367.3.2.1.2.24.1.1.5.4']         = 'cartridgesyellow';
      $mapping_pre[3]['.1.3.6.1.2.1.43.10.2.1.4.1.1']                = 'pagecountertotalpages';
      $mapping_pre[3]['.1.3.6.1.2.1.43.5.1.1.17.1']                  = 'serial';
      $mapping_pre[3]['.1.3.6.1.4.1.2001.1.1.1.1.11.1.10.45.0']      = 'serial';
      $mapping_pre[3]['.1.3.6.1.4.1.11.2.4.3.1.25.0']                = 'serial';
      $mapping_pre[3]['.1.3.6.1.4.1.253.8.53.13.2.1.8.1.20.33']      = 'pagecountercolorpages';
      $mapping_pre[3]['.1.3.6.1.4.1.253.8.53.13.2.1.8.1.20.34']      = 'pagecounterblackpages';
      $mapping_pre[3]['.1.3.6.1.4.1.253.8.53.13.2.1.8.103.20.3']     = 'pagecounterblackpages_copy';
      $mapping_pre[3]['.1.3.6.1.4.1.253.8.53.13.2.1.8.103.20.25']    = 'pagecountercolorpages_copy';
      $mapping_pre[3]['.1.3.6.1.4.1.253.8.53.13.2.1.8.104.20.15']    = 'pagecountertotalpages_fax';
      $mapping_pre[3]['.1.3.6.1.4.1.253.8.53.13.2.1.8.1.20.7']       = 'pagecounterblackpages_print';
      $mapping_pre[3]['.1.3.6.1.4.1.253.8.53.13.2.1.8.1.20.29']      = 'pagecountercolorpages_print';
      $mapping_pre[3]['.1.3.6.1.4.1.253.8.53.13.2.1.8.102.20.21']    = 'pagecounterscannedpages';
      $mapping_pre[3]['.1.3.6.1.4.1.11.2.3.9.4.2.1.1.3.12.0']        = 'otherserial';
      $mapping_pre[3]['.1.3.6.1.4.1.11.2.3.9.4.2.1.1.16.4.1.1.1.0']  = 'pagecounterblackpages';
      $mapping_pre[3]['.1.3.6.1.4.1.11.2.3.9.4.2.1.1.16.4.1.3.1.0']  = 'pagecountercolorpages';
      $mapping_pre[3]['.1.3.6.1.4.1.1129.2.3.50.1.3.21.6.1.3.1.3']   = 'pagecountertotalpages_print';
      $mapping_pre[3]['.1.3.6.1.4.1.1129.2.3.50.1.3.21.6.1.3.1.3']   = 'pagecountertotalpages_print';
      $mapping_pre[3]['.1.3.6.1.4.1.1129.2.3.50.1.3.21.6.1.4.1.3']   = 'pagecountertotalpages_copy';
      $mapping_pre[3]['.1.3.6.1.4.1.11.2.3.9.1.1.7.0']               = 'informations';
      $mapping_pre[3]['.1.3.6.1.4.1.674.10898.100.2.1.2.1.6.1']      = 'serial';
      $mapping_pre[3]['.1.3.6.1.4.1.1602.1.2.1.4.0']                 = 'serial';
      $mapping_pre[3]['.1.3.6.1.4.1.11.2.3.9.4.2.1.1.3.3.0']         = 'serial';
      $mapping_pre[3]['.1.3.6.1.4.1.2435.2.3.9.4.2.1.5.5.1.0']       = 'serial';
      $mapping_pre[3]['.1.3.6.1.2.1.2.1.0']                          = '';
// To delete
      $mapping_pre[3]['.1.3.6.1.2.1.1.3.0']       = 'serial';

      $mapping_pre[1]['.1.3.6.1.4.1.714.1.2.5.3.5.0'] = 'serial';
      $mapping_pre[1]['.1.3.6.1.2.1.2.2.1.6']         = 'ifPhysAddress';
      $mapping_pre[1]['.1.3.6.1.2.1.4.20.1.2']        = 'ifaddr';

      return $mapping_pre;
   }

   
   private function mibIgnore() {

      $a_ignore = array();
      
      // ##### Not see in contruct tool
      $a_ignore[3]['.1.3.6.1.2.1.1.3.0'] = 1;
      $a_ignore[3]['.1.3.6.1.2.1.2.2.1.4'] = 1;
      $a_ignore[3]['.1.3.6.1.2.1.2.2.1.5'] = 1;
      $a_ignore[3]['.1.3.6.1.2.1.2.2.1.7'] = 1;
      $a_ignore[3]['.1.3.6.1.2.1.2.2.1.8'] = 1;
      $a_ignore[3]['.1.3.6.1.2.1.2.2.1.9'] = 1;
      $a_ignore[3]['.1.3.6.1.2.1.2.2.1.10'] = 1;
      $a_ignore[3]['.1.3.6.1.2.1.2.2.1.14'] = 1;
      $a_ignore[3]['.1.3.6.1.2.1.2.2.1.16'] = 1;
      $a_ignore[3]['.1.3.6.1.2.1.2.2.1.20'] = 1;
      $a_ignore[3]['.1.3.6.1.2.1.4.20.1.1'] = 1;
      $a_ignore[3]['.1.3.6.1.2.1.4.22.1.2'] = 1;

      return $a_ignore;
   }


   private function mibVlan() {

      $mapping_pre_vlan = array();
      $mapping_pre_vlan['.1.3.6.1.4.1.9.9.46.1.6.1.1.14'] = '1';
      $mapping_pre_vlan['.1.3.6.1.2.1.17.4.3.1.1'] = '1';
      $mapping_pre_vlan['.1.3.6.1.2.1.4.22.1.2'] = '1';
      $mapping_pre_vlan['.1.3.6.1.2.1.17.4.3.1.2'] = '1';
      $mapping_pre_vlan['.1.3.6.1.2.1.17.1.4.1.2'] = '1';

      return $mapping_pre_vlan;
   }



   function showForm($target, $ID = '') {
		global $DB,$CFG_GLPI,$LANG;

		if ($ID!='') {
			$this->getFromDB($ID);
      } else {
			$this->getEmpty();
      }

		$this->showTabs($ID, "",$_SESSION['glpi_tab']);
		echo "<div align='center'><form method='post' name='' id=''  action=\"" . $target . "\">";

		echo "<table class='tab_cadre' cellpadding='5' width='950'>";
		echo "<tr>";
		echo "<th colspan='2'>";
		echo $LANG['plugin_fusioninventory']["constructdevice"][0];
		echo " :</th>";
		echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['common'][5].": 	</td><td>";
      dropdownValue("glpi_dropdown_manufacturer","FK_glpi_enterprise",$this->fields["FK_glpi_enterprise"]);
      echo "</td>";

      echo "<tr>";
      echo "<td>".$LANG['setup'][71].": 	</td><td>\n";
      dropdownValue("glpi_dropdown_firmware", "firmware", $this->fields["firmware"]);
      echo "</td>";
      echo "</tr>\n";

		echo "<tr class='tab_bg_1'>";
		echo "<td>" . $LANG['common'][25] . "</td>";
		echo "<td>";
		echo "<textarea name='sysdescr'  cols='110' rows='4' />".$this->fields["sysdescr"]."</textarea>";
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>" . $LANG['common'][17] . " :</td>";
		echo "<td>";
         $type_list = array();
			$type_list[] = COMPUTER_TYPE;
			$type_list[] = NETWORKING_TYPE;
			$type_list[] = PRINTER_TYPE;
			$type_list[] = PERIPHERAL_TYPE;
			$type_list[] = PHONE_TYPE;

         // GENERIC OBJECT : Search types in generic object
         $plugin = new Plugin;
         if ($plugin->isActivated('genericobject')) {
            if (TableExists("glpi_plugin_genericobject_types")) {
               $query = "SELECT * FROM `glpi_plugin_genericobject_types`
                  WHERE `status`='1' ";
               if ($result=$DB->query($query)) {
                  while ($data=$DB->fetch_array($result)) {
                     $type_list[] = $data['device_type'];
                  }
               }
            }
         }
         // END GENERIC OBJECT
			dropdownDeviceTypes('type',$this->fields["type"],$type_list);
		echo "</td>";
      echo "</tr>";

		echo "<tr class='tab_bg_1'><td align='center' colspan='3'>";
		if ($ID=='') {
			echo "<div align='center'><input type='submit' name='add' value=\"" . $LANG["buttons"][8] . "\" class='submit' >";
		} else {
			echo "<input type='hidden' name='ID' value='" . $ID . "'/>";
			echo "<div align='center'><input type='submit' name='update' value=\"" . $LANG["buttons"][7] . "\" class='submit' >";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' name='delete' value=\"" . $LANG["buttons"][6] . "\" class='submit'>";
		}
		echo "</td></tr>";
		echo "</table></form></div>";

	}


   function manageWalks($target, $ID) {
      include (GLPI_ROOT . "/plugins/fusioninventory/inc_constants/plugin_fusioninventory.snmp.mapping.constant.php");

		global $DB,$CFG_GLPI,$LANG,$FUSIONINVENTORY_MAPPING,$IMPORT_TYPES;
      
      $cartridgename = array();
      // Definition of relation since you have in walk file and cartrige
      $a_cartridgerelation = array();
      $a_cartridgerelation["black"] = "black";
      $a_cartridgerelation["Black"] = "black";
      $a_cartridgerelation["cyan"] = "cyan";
      $a_cartridgerelation["Cyan"] = "cyan";
      $a_cartridgerelation["magenta"] = "magenta";
      $a_cartridgerelation["Magenta"] = "magenta";
      $a_cartridgerelation["yellow"] = "yellow";
      $a_cartridgerelation["Yellow"] = "yellow";


      $query = "SELECT * FROM glpi_plugin_fusioninventory_construct_device
         WHERE ID='".$ID."'";
      $result = $DB->query($query);
      $a_device = $DB->fetch_assoc($result);
      $type_model = $a_device['type'];
      $sysdescr   = $a_device['sysdescr'];

      $a_ignore = array();

      

     // Load oids from DB
      $oids_DB = array();
      $query = "SELECT * FROM `glpi_dropdown_plugin_fusioninventory_mib_oid`";
      if ($result = $DB->query($query)) {
			while ($data = $DB->fetch_array($result)) {
            $oids_DB["search"][] = $data['name'];
            $oids_DB["id"][] = $data['ID'];
            $oids_DB["search"][] = $data['comments'];
            $oids_DB["id"][] = $data['ID'];
            $data['name'] = preg_replace("/^.1./", "iso.", $data['name']);
            $oids_DB["search"][] = $data['name'];
            $oids_DB["id"][] = $data['ID'];
         }
      }

      

      // Used mapping name :
      $a_mapping_used = array();
      $query = "SELECT * FROM glpi_plugin_fusioninventory_construct_mibs
         WHERE construct_device_id='".$ID."'
            AND mapping_name != ''";
      if ($result = $DB->query($query)) {
			while ($data = $DB->fetch_array($result)) {
            $a_mapping_used[$data['mapping_type']."||".$data['mapping_name']] = $data['mapping_type']."||".$data['mapping_name'];
         }
      }

      $query = "SELECT * FROM glpi_plugin_fusioninventory_construct_walks
         WHERE construct_device_id='".$ID."'";
      echo "<div align='center'>
         <form method='post' name='' id=''  action='".$target."' >";

      $a_oids = array();
      $a_oids1 = array();
      $a_oids2 = array();
      $a_mibs = array();
      if ($result = $DB->query($query)) {
			if ($data = $DB->fetch_array($result)) {
            $missing = "";
            if (!file_exists(GLPI_PLUGIN_DOC_DIR."/fusioninventory/walks/".$data['log'])) {
               $missing = " (missing)";
            }
            $file_content = file(GLPI_PLUGIN_DOC_DIR."/fusioninventory/walks/".$data['log']);
            echo "<table class='tab_cadre' width='950'>";
            echo "<tr>";
            echo "<th>Filename : ";
            echo $data['log'].$missing;
            echo "&nbsp;<input type=\"button\" name=\"deletewalk\" class=\"submit\" value=\"" . $LANG['buttons'][6] . "\" onclick=\"self.location.href='".$_SERVER["PHP_SELF"]."?deletewalk=".$ID."'\" >";
            echo "</th>";
            echo "</tr>";
            echo "</table>";
            echo "<br/>";

            $query_oid = "SELECT * FROM glpi_dropdown_plugin_fusioninventory_mib_oid";
            $result_oid = $DB->query($query_oid);
            while ($fields_oid = $DB->fetch_array($result_oid)) {
               if ($fields_oid['comments'] != "") {
                  $a_oids[] = $fields_oid['comments'];
                  $a_oids1[] = $fields_oid['name'];
                  $a_oids2[] = $fields_oid['ID'];
               }
            }
            $before = '';
            $oid_id_before = 0;
            if ($missing != "") {
               $a_oid_all = array();
               $query_oid = "SELECT * FROM `glpi_plugin_fusioninventory_construct_mibs`
                  INNER JOIN glpi_dropdown_plugin_fusioninventory_mib_oid
                     on mib_oid_id = glpi_dropdown_plugin_fusioninventory_mib_oid.ID
                  WHERE construct_device_id='".$ID."'
                     ORDER BY name";
               $result_oid = $DB->query($query_oid);
               while ($fields_oid = $DB->fetch_array($result_oid)) {
                  $a_oid_all[] = $fields_oid['comments'].' = ""';
               }
               $file_content = $a_oid_all;
            }
            $previous_oid = 0;
            foreach($file_content as $line){
               $previous_oid = $this->manageWalksLine($line, $oids_DB, $previous_oid, $type_model);
            }
         }
      }
      if (!empty($ID)) {
         echo "<table class='tab_cadre' cellpadding='5' width='950'>";
         echo "<tr class='tab_bg_1 center'>";
         echo "<td>";
         echo "<input type='hidden' name='ID' value='" . $ID . "'/>";
         echo "&nbsp;<input type='submit' name='mib' value=\"" . $LANG["buttons"][7] . "\" class='submit' >";
         echo "</td>";
         echo "</tr>";
         echo "</table>";
      }
      echo "</form><br/>";

      if (!empty($ID)) {
         echo "<form method='post' name='' id=''  action='".$target."' enctype=\"multipart/form-data\">";
         echo "<table class='tab_cadre' cellpadding='5' width='950'>";
         echo "<tr class='tab_bg_1 center'>";
         echo "<td>";
         echo "<input type='hidden' name='ID' value='" . $ID . "'/>";
         echo "<input type='file' name='walk'/>";
         echo "&nbsp;<input type='submit' name='addWalk' value=\"" . $LANG["buttons"][8] . "\" class='submit' >";
         echo "</td>";
         echo "</tr>";
         echo "</table>";
         echo "</form>";
      }
      echo "</div>";

   }



   function manageWalksLine($line, $oids_DB, $previous_oid, $type_model) {
      global $DB;

      // Search if OID of line is in DB
      $a_line = explode(" = ", $line);
      $find_array = array();
      if (!strstr($line, " = OID: ")) {
         foreach($oids_DB["search"] as $tmp_id=>$oid) {
            if (strstr($a_line[0], $oid)) {
               if (preg_match("/^".$oid."/", $a_line[0])) {
                  if (($a_line[0] == $oid) OR (strstr($a_line[0], $oid."."))) {
                     $find_array[$tmp_id] = $oid;
                  }
               }
            }
         }
      }
      
      if (count($find_array) > 0) {
         // get id of best oid
         $long = 0;
         foreach($find_array as $tmp_id=>$oid) {
            if (count($oid) > $long) {
               $oid_id = $tmp_id;
               $long = count($oid);
            }
         }
         // Now we have oid_id
         $a_mibIgnore = $this->mibIgnore();
         $oid = getDropdownName("glpi_dropdown_plugin_fusioninventory_mib_oid", $oids_DB["id"][$oid_id]);
         $oid_walk = str_replace($oids_DB["search"][$oid_id], $oid, $a_line[0]);
         $this->manageWalksLineCartridgeDetection($oid, $a_line[1]);
         $this->manageWalksLinePagecounterDetection($oid, $a_line[1]);
         if (!isset($a_mibIgnore[$type_model][$oid])) {
            // If oid_complete !=  $previous_oid , we start array display
            if ($previous_oid == $oid_id) {


            } else {

               if ($previous_oid > 0) {
                  // TODO : display options
                  $this->manageWalksDisplaySecondOptions($previous_oid, $oids_DB, $type_model);
                  $previous_oid1 = getDropdownName("glpi_dropdown_plugin_fusioninventory_mib_oid", $oids_DB["id"][$previous_oid]);
                  $this->manageWalksDisplayThirdOptions($previous_oid, $oids_DB, $type_model, $previous_oid1);
                  // End of oid array
                  echo "</th>";
                  echo "</tr>";
                  echo "</table><br/>";
               }
               // ****************************************************** //
               // Start oid array
               $display_cartridge = $this->manageWalksDisplayFirstOptions($oid_id, $oids_DB, $type_model, $a_line[1], $oid_walk);
            }
            if (isset($display_cartridge) AND ($display_cartridge == '1')) {
               echo "<tr class='tab_bg_1'>";
               echo "<th colspan='3' align='center'>";
               if (isset($this->a_cartridge[$oid_walk]["name"])) {
                  echo $this->a_cartridge[$oid_walk]["name"];
                  echo " => ";
                  if (preg_match("/8.1.([0-9]*)$/", $oid)) {
                     echo "MAX";
                  } else if (preg_match("/9.1.([0-9]*)$/", $oid)) {
                     echo "REMAIN";
                  } else {
                     echo "Description";
                  }
               } else if (isset($this->a_pagecounter[$oid_walk]["name"])) {
                  echo $this->a_pagecounter[$oid_walk]["name"];
               }
               echo "</th>";
               echo "</tr>";
            }
            echo "<tr class='tab_bg_1'>";
            echo "<td colspan='3' align='center'>";
            echo $line;
            echo "</td>";
            echo "</tr>";
         } else {
            $oid_id = $previous_oid;
         }

      } else {
         if ($previous_oid != '0') {
            $a_mibIgnore = $this->mibIgnore();
            $oid = getDropdownName("glpi_dropdown_plugin_fusioninventory_mib_oid", $oids_DB["id"][$previous_oid]);
            if (!isset($a_mibIgnore[$type_model][$oid])) {
               $this->manageWalksDisplaySecondOptions($previous_oid, $oids_DB, $type_model);
               $this->manageWalksDisplayThirdOptions($previous_oid, $oids_DB, $type_model, $previous_oid);
               echo "</th>";
               echo "</tr>";
               echo "</table><br/>";
            }
         }
         $oid_id = 0;
      }
    
      return $oid_id;
   }
   

   
   function manageWalksDisplayFirstOptions($oid_id, $oids_DB, $type_model, $oid_value, $oid_walk) {
      global $DB, $CFG_GLPI;

      $mapping_pre = $this->mibDetection();
      $mapping_pre_ignore = $this->mibIgnore();

      $array = getDropdownName("glpi_dropdown_plugin_fusioninventory_mib_oid", $oids_DB["id"][$oid_id],1);
      $oid = $array['name'];

      $query = "SELECT * FROM `glpi_plugin_fusioninventory_construct_mibs`
         WHERE `construct_device_id`='".$_GET['ID']."'
            AND `mib_oid_id`='".$oids_DB["id"][$oid_id]."'";
      $result = $DB->query($query);
      $is_inDB = $DB->numrows($result);

      $display_cartridge = 0;
      if ($is_inDB > 0) {
         $style = " style='border-color: #00d50f; border-width: 2px' ";
      } else if ((isset($mapping_pre[$type_model][$oid]) AND (!isset($mapping_pre_ignore[$type_model][$oid])))) {
         $style = " style='border-color: #0000ff; border-width: 3px' "; // 0000ff
      } else if (isset($this->a_cartridge[$oid_walk])) {
         $style = " style='border-color: #0000ff; border-width: 3px' "; // 0000ff
         $display_cartridge = 1;
      } else if (isset($this->a_pagecounter[$oid_walk])) {
         $style = " style='border-color: #0000ff; border-width: 3px' "; // 0000ff
         $display_cartridge = 1;
      } else {
         $style = " style='border-color: #ff0000; border-width: 2px' ";
      }

      echo "<table class='tab_cadre' cellpadding='5' width='950' ".$style.">";
      echo "<tr>";
      echo "<th colspan='3'>";

      if ($is_inDB == 0) {
         echo "<input type='checkbox' name='oidsselected[]' value='".$oids_DB["id"][$oid_id]."' />&nbsp;";
         echo "&nbsp;<font color='#ff0000'>";
      } else {
         echo "<img src='".$CFG_GLPI["root_doc"]."/pics/bookmark.png'/>";
         echo "&nbsp;<font>";
         
      }
      if ($oid == '.1.3.6.1.2.1.1.1.0') {
         $this->sysdescr = $oid_value;
      }
      echo $oid." (".$array['comments'].")";
      echo "</th>";
      echo "</tr>";
      return $display_cartridge;
   }



     /*
    * Manage options :
    * VLAN
    * Port counter
    * Dynamic port
    * Link field
    *
    * Argumentents :
    * state : string if display only or can select field
    */
   function manageWalksDisplaySecondOptions($oid_id, $oids_DB, $type_model) {
      global $DB, $LANG, $CFG_GLPI;

      $oid = getDropdownName("glpi_dropdown_plugin_fusioninventory_mib_oid", $oids_DB["id"][$oid_id]);

      $query = "SELECT * FROM `glpi_plugin_fusioninventory_construct_mibs`
         WHERE `construct_device_id`='".$_GET['ID']."'
            AND `mib_oid_id`='".$oids_DB["id"][$oid_id]."'";
      $result = $DB->query($query);
      $is_inDB = $DB->numrows($result);
      if ($is_inDB == '1') {
         $mib_DB = $DB->fetch_assoc($result);
      }

      echo "<tr>";
      echo "<th>";
      echo $LANG['plugin_fusioninventory']["mib"][9]." : ";
      if ($is_inDB > 0) {
         if ($mib_DB["vlan"] == "1") {
            echo "<a href='?ID=".$_GET['ID']."&vlan_update=".$oids_DB["id"][$oid_id]."'>";
            echo "<img src='".$CFG_GLPI["root_doc"]."/pics/bookmark.png'/>";
            echo "</a>";
         } else {
            echo "<img src='".$CFG_GLPI["root_doc"]."/plugins/fusioninventory/pics/bookmark_off.png'/>";
         }
      } else {
         $mapping_pre_vlan = $this->mibVlan();
         if (isset($mapping_pre_vlan[$oid])) {
            if (strstr($this->sysdescr, "Cisco")) {
               dropdownYesNo("vlan_".$oids_DB["id"][$oid_id], 1);
            } else {
               dropdownYesNo("vlan_".$oids_DB["id"][$oid_id]);
            }
         } else {
            dropdownYesNo("vlan_".$oids_DB["id"][$oid_id]);
         }
      }
      echo "</th>";
      echo "<th width='350'>";
      if ($is_inDB > 0) {
         echo $LANG['plugin_fusioninventory']["mib"][6]." : ";
         if ($mib_DB["oid_port_counter"] == "1") {
            echo "<img src='".$CFG_GLPI["root_doc"]."/pics/bookmark.png'/>";
         } else {
            echo "<img src='".$CFG_GLPI["root_doc"]."/plugins/fusioninventory/pics/bookmark_off.png'/>";
         }
      } else {
         if ($oid == ".1.3.6.1.2.1.2.1.0") {
            echo $LANG['plugin_fusioninventory']["mib"][6]." : ";
            dropdownYesNo("oid_port_counter_".$oids_DB["id"][$oid_id], 1);
         }
      }
      echo "</th>";
      echo "<th>";
      echo $LANG['plugin_fusioninventory']["mib"][7]." : ";
      if ($is_inDB > 0) {
         if ($mib_DB["oid_port_dyn"] == "1") {
            echo "<img src='".$CFG_GLPI["root_doc"]."/pics/bookmark.png'/>";
         } else {
            echo "<img src='".$CFG_GLPI["root_doc"]."/plugins/fusioninventory/pics/bookmark_off.png'/>";
         }
      } else {
         $mapping_pre = $this->mibDetection();

         if (isset($mapping_pre[$type_model][$oid])
            AND ((preg_match('/^if/', $mapping_pre[$type_model][$oid]))
            OR (preg_match('/ipAdEntAddr/', $mapping_pre[$type_model][$oid]))
            OR (preg_match('/ipNetToMediaPhysAddress/', $mapping_pre[$type_model][$oid])))){

            dropdownYesNo("oid_port_dyn_".$oids_DB["id"][$oid_id],1);
         } else {
            dropdownYesNo("oid_port_dyn_".$oids_DB["id"][$oid_id]);
         }
      }
      echo "</th>";
      echo "</tr>";

   }



   function manageWalksDisplayThirdOptions($oid_id, $oids_DB, $type_model, $oid_walk) {
      GLOBAL $DB, $LANG, $FUSIONINVENTORY_MAPPING;

      $oid = getDropdownName("glpi_dropdown_plugin_fusioninventory_mib_oid", $oids_DB["id"][$oid_id]);

      $query = "SELECT * FROM `glpi_plugin_fusioninventory_construct_mibs`
         WHERE `construct_device_id`='".$_GET['ID']."'
            AND `mib_oid_id`='".$oids_DB["id"][$oid_id]."'";
      $result = $DB->query($query);
      $is_inDB = $DB->numrows($result);
      if ($is_inDB == '1') {
         $mib_DB = $DB->fetch_assoc($result);
      }

      echo "<tr>";
      echo "<th colspan='3'>";
      echo $LANG['plugin_fusioninventory']["mib"][8]." : ";
      if ($is_inDB > 0) {
         if ($mib_DB["oid_port_counter"] == "0") {
            echo $FUSIONINVENTORY_MAPPING[$mib_DB['mapping_type']][$mib_DB["mapping_name"]]['name'];
            echo " <a href='".$_SERVER["PHP_SELF"]."?ID=".$_GET['ID']."&amp;deleteoid=".$oids_DB["id"][$oid_id]."'><img src='".GLPI_ROOT."/plugins/fusioninventory/pics/delete.png' /></a>";
         }
      } else {
         $mapping_pre = $this->mibDetection();

         $types = array();
         $types[] = "-----";
         foreach ($FUSIONINVENTORY_MAPPING as $type=>$mapping43) {
            if (($type_model == $type) OR ($type_model == "0")) {
               if (isset($FUSIONINVENTORY_MAPPING[$type])) {
                  foreach ($FUSIONINVENTORY_MAPPING[$type] as $name=>$mapping) {
                     $types[$type."||".$name]=$FUSIONINVENTORY_MAPPING[$type][$name]["name"]." (".$name.")";
                  }
               }
            }
         }
         $mapping_pre_ignore = $this->mibIgnore();
         if ((isset($mapping_pre[$type_model][$oid])) AND (!isset($mapping_pre_ignore[$type_model][$oid])) ) {
            dropdownArrayValues("links_oid_fields_".$oids_DB["id"][$oid_id],$types, $type_model."||".$mapping_pre[$type_model][$oid]); //,$a_mapping_used
         } else if (isset($this->a_cartridge[$oid_walk]["mapping"])) {
            dropdownArrayValues("links_oid_fields_".$oids_DB["id"][$oid_id],$types, $type_model."||".$this->a_cartridge[$oid_walk]["mapping"]); //,$a_mapping_used
         } else if (isset($this->a_pagecounter[$oid_walk]["mapping"])) {
            dropdownArrayValues("links_oid_fields_".$oids_DB["id"][$oid_id],$types, $type_model."||".$this->a_pagecounter[$oid_walk]["mapping"]); //,$a_mapping_used
         } else {
            dropdownArrayValues("links_oid_fields_".$oids_DB["id"][$oid_id],$types);
         }
      }
      echo "</th>";
      echo "</tr>";

   }
   


   function manageWalksLineCartridgeDetection($oid, $oid_value) {

      // Definition of cartridges
      $a_cartridgerelation = array();
      $i = 0;
      $a_cartridgerelation[$i]["value"][] = "Black";
      $a_cartridgerelation[$i]["value"][] = "Toner";
      $a_cartridgerelation[$i]["mapping"] = "tonerblack";
      $i++;
      $a_cartridgerelation[$i]["value"][] = "noir";
      $a_cartridgerelation[$i]["value"][] = "Toner";
      $a_cartridgerelation[$i]["mapping"] = "tonerblack";
      $i++;
      $a_cartridgerelation[$i]["value"][] = "Cyan";
      $a_cartridgerelation[$i]["value"][] = "Toner";
      $a_cartridgerelation[$i]["mapping"] = "tonercyan";
      $i++;
      $a_cartridgerelation[$i]["value"][] = "cyan";
      $a_cartridgerelation[$i]["value"][] = "Toner";
      $a_cartridgerelation[$i]["mapping"] = "tonercyan";
      $i++;
      $a_cartridgerelation[$i]["value"][] = "Magenta";
      $a_cartridgerelation[$i]["value"][] = "Toner";
      $a_cartridgerelation[$i]["mapping"] = "tonermagenta";
      $i++;
      $a_cartridgerelation[$i]["value"][] = "magenta";
      $a_cartridgerelation[$i]["value"][] = "Toner";
      $a_cartridgerelation[$i]["mapping"] = "tonermagenta";
      $i++;
      $a_cartridgerelation[$i]["value"][] = "Yellow";
      $a_cartridgerelation[$i]["value"][] = "Toner";
      $a_cartridgerelation[$i]["mapping"] = "toneryellow";
      $i++;
      $a_cartridgerelation[$i]["value"][] = "jaune";
      $a_cartridgerelation[$i]["value"][] = "Toner";
      $a_cartridgerelation[$i]["mapping"] = "toneryellow";
      $i++;
      $a_cartridgerelation[$i]["value"][] = "Waste";
      $a_cartridgerelation[$i]["value"][] = "Toner";
      $a_cartridgerelation[$i]["mapping"] = "wastetoner";
      $i++;
      $a_cartridgerelation[$i]["value"][] = "usagé";
      $a_cartridgerelation[$i]["value"][] = "Toner";
      $a_cartridgerelation[$i]["mapping"] = "wastetoner";
      $i++;
      $a_cartridgerelation[$i]["value"][] = "Black";
      $a_cartridgerelation[$i]["value"][] = "Drum";
      $a_cartridgerelation[$i]["mapping"] = "drumblack";
      $i++;
      $a_cartridgerelation[$i]["value"][] = "Cyan";
      $a_cartridgerelation[$i]["value"][] = "Drum";
      $a_cartridgerelation[$i]["mapping"] = "drumcyan";
      $i++;
      $a_cartridgerelation[$i]["value"][] = "Magenta";
      $a_cartridgerelation[$i]["value"][] = "Drum";
      $a_cartridgerelation[$i]["mapping"] = "drummagenta";
      $i++;
      $a_cartridgerelation[$i]["value"][] = "Yellow";
      $a_cartridgerelation[$i]["value"][] = "Drum";
      $a_cartridgerelation[$i]["mapping"] = "drumyellow";



      
      
      $a_cartridgerelation[10001]["value"][] = "Toner";
      $a_cartridgerelation[10001]["mapping"] = "tonerblack";

      $a_cartridgerelation[10002]["value"][] = "Drum";
      $a_cartridgerelation[10002]["mapping"] = "drumblack";

      foreach($a_cartridgerelation as $id=>$datas) {
         $search = 1;
         foreach($datas["value"] as $id_value => $value) {
            if ($search != "0") {
               if (!strstr($oid_value, $value)) {
                  $search = 0;
               }
            }
         }
         if ($search == "1") {
            $last_oidId = str_replace(".1.3.6.1.2.1.43.11.1.1.6.1.", "", $oid);
            $this->a_cartridge[".1.3.6.1.2.1.43.11.1.1.6.1.".$last_oidId]["name"] = $oid_value;
            $this->a_cartridge[".1.3.6.1.2.1.43.11.1.1.6.1.".$last_oidId]["mapping"] = $datas["mapping"];
            $this->a_cartridge[".1.3.6.1.2.1.43.11.1.1.8.1.".$last_oidId]["name"] = $oid_value;
            $this->a_cartridge[".1.3.6.1.2.1.43.11.1.1.8.1.".$last_oidId]["mapping"] = $datas["mapping"]."max";
            $this->a_cartridge[".1.3.6.1.2.1.43.11.1.1.9.1.".$last_oidId]["name"] = $oid_value;
            $this->a_cartridge[".1.3.6.1.2.1.43.11.1.1.9.1.".$last_oidId]["mapping"] = $datas["mapping"]."remaining";
            return;
         }
      }
   }



   function manageWalksLinePagecounterDetection($oid, $oid_value) {

      // Definition of cartridges
      $a_pagecounterrelation = array();
      $i = 0;
      $a_pagecounterrelation[$i]["value"][] = "Counter: Machine Total";
      $a_pagecounterrelation[$i]["mapping"] = "pagecountertotalpages";
      $i++;
      $a_pagecounterrelation[$i]["value"][] = "Counter:Print:Total";
      $a_pagecounterrelation[$i]["mapping"] = "pagecountertotalpages_print";
      $i++;
      $a_pagecounterrelation[$i]["value"][] = "Counter:Print:Black & White";
      $a_pagecounterrelation[$i]["mapping"] = "pagecounterblackpages_print";
      $i++;
      $a_pagecounterrelation[$i]["value"][] = "Counter:Print:Full Color";
      $a_pagecounterrelation[$i]["mapping"] = "pagecountercolorpages_print";
      $i++;
      $a_pagecounterrelation[$i]["value"][] = "Counter:Copy:Total";
      $a_pagecounterrelation[$i]["mapping"] = "pagecountertotalpages_copy";
      $i++;
      $a_pagecounterrelation[$i]["value"][] = "Counter:Copy:Black & White";
      $a_pagecounterrelation[$i]["mapping"] = "pagecounterblackpages_copy";
      $i++;
      $a_pagecounterrelation[$i]["value"][] = "Counter:Copy:Full Color";
      $a_pagecounterrelation[$i]["mapping"] = "pagecountercolorpages_copy";
      $i++;
      $a_pagecounterrelation[$i]["value"][] = "Counter:FAX:Total";
      $a_pagecounterrelation[$i]["mapping"] = "pagecountertotalpages_fax";

 
      foreach($a_pagecounterrelation as $id=>$datas) {
         $search = 1;
         foreach($datas["value"] as $id_value => $value) {
            if ($search != "0") {
               if (!strstr($oid_value, $value)) {
                  $search = 0;
               }
            }
         }
         if ($search == "1") {
            $last_oidId = str_replace(".1.3.6.1.4.1.367.3.2.1.2.19.5.1.5.", "", $oid);
            $this->a_pagecounter[".1.3.6.1.4.1.367.3.2.1.2.19.5.1.9.".$last_oidId]["name"] = $oid_value;
            $this->a_pagecounter[".1.3.6.1.4.1.367.3.2.1.2.19.5.1.9.".$last_oidId]["mapping"] = $datas["mapping"];
            return;
         }
      }
   }



    function generatemodels() {
      global $DB;

      $ptmi = new PluginFusionInventoryModelInfos;
      $ptmn = new PluginFusionInventoryMibNetworking;

      $query = "SELECT glpi_plugin_fusioninventory_construct_device.ID, type  FROM glpi_plugin_fusioninventory_construct_device
         LEFT JOIN glpi_plugin_fusioninventory_construct_walks on glpi_plugin_fusioninventory_construct_device.ID = construct_device_id
         WHERE type IN (1,2,3)
            AND log!=''";
      if ($result = $DB->query($query)) {
			while ($data = $DB->fetch_array($result)) {
            // Load mibs
            $a_mib = array();
            $count_mib = 0;
            $query_mibs = "SELECT * FROM glpi_plugin_fusioninventory_construct_mibs
               WHERE construct_device_id='".$data["ID"]."' ";
            if ($result_mibs = $DB->query($query_mibs)) {
               while ($data_mibs = $DB->fetch_array($result_mibs)) {
                  $a_mib[$data_mibs['mib_oid_id']]['mapping_type'] = $data_mibs['mapping_type'];
                  $a_mib[$data_mibs['mib_oid_id']]['mapping_name'] = $data_mibs['mapping_name'];
                  $a_mib[$data_mibs['mib_oid_id']]['oid_port_counter'] = $data_mibs['oid_port_counter'];
                  $a_mib[$data_mibs['mib_oid_id']]['oid_port_dyn'] = $data_mibs['oid_port_dyn'];
                  $a_mib[$data_mibs['mib_oid_id']]['vlan'] = $data_mibs['vlan'];
                  $count_mib++;
               }
            }

            // See if model exactly exists
            $query_models = "SELECT * FROM glpi_plugin_fusioninventory_model_infos";
            $existent = 0;
            if ($result_models = $DB->query($query_models)) {
               while ($data_models = $DB->fetch_array($result_models)) {
                  if ($existent != '1') {
                     $count_mib_model = 0;
                     $query_mibs_model = "SELECT * FROM glpi_plugin_fusioninventory_mib_networking
                        WHERE FK_model_infos='".$data_models['ID']."' ";
                     if ($result_mib_model = $DB->query($query_mibs_model)) {
                        while ($data_mib_model = $DB->fetch_array($result_mib_model)) {
                           $count_mib_model++;
                           if ($existent != '-1') {
                              if (isset($a_mib[$data_mib_model['FK_mib_oid']]['mapping_type'])) {
                                 // Oid Existe, on vÃ©rifie si tous les paramÃ¨tres sont pareils
                                 if ($a_mib[$data_mib_model['FK_mib_oid']]['mapping_type'] == $data_mib_model['mapping_type'] AND
                                    $a_mib[$data_mib_model['FK_mib_oid']]['mapping_name'] == $data_mib_model['mapping_name'] AND
                                    $a_mib[$data_mib_model['FK_mib_oid']]['oid_port_counter'] == $data_mib_model['oid_port_counter'] AND
                                    $a_mib[$data_mib_model['FK_mib_oid']]['oid_port_dyn'] == $data_mib_model['oid_port_dyn'] AND
                                    $a_mib[$data_mib_model['FK_mib_oid']]['vlan'] == $data_mib_model['vlan']) {

                                 } else {
                                    $existent = '-1';
                                 }
                              } else {
                                 $existent = '-1';
                              }
                           }
                        }
                     }
                     if (($existent == '0') AND ($count_mib == $count_mib_model)) {
                        // Add number in database
                        $query_update = "UPDATE glpi_plugin_fusioninventory_construct_device
                           SET snmpmodel_id='".$data_models['ID']."'
                           WHERE ID='".$data["ID"]."'";
                        $DB->query($query_update);
                        $existent = 1;
                     } else {
                        $existent = 0;
                     }
                  }
               }
            }
            if ($existent != '1') {
               // Create model
               $a_input = array();
               $a_input['name'] = rand(10000, 10000000);
               $a_input['device_type'] = $data["type"];
               $a_input['activation'] = 1;
               $id = $ptmi->add($a_input);
               
               $query_mibs = "SELECT * FROM glpi_plugin_fusioninventory_construct_mibs
                  WHERE construct_device_id='".$data["ID"]."' ";
               if ($result_mibs = $DB->query($query_mibs)) {
                  while ($data_mibs = $DB->fetch_array($result_mibs)) {
                     $a_input = array();
                     $a_input['FK_model_infos'] = $id;
                     $a_input['FK_mib_oid'] = $data_mibs['mib_oid_id'];
                     $a_input['oid_port_counter'] = $data_mibs['oid_port_counter'];
                     $a_input['oid_port_dyn'] = $data_mibs['oid_port_dyn'];
                     $a_input['vlan'] = $data_mibs['vlan'];
                     $a_input['links_oid_fields'] = $data_mibs['mapping_type']."||".$data_mibs['mapping_name'];
                     $a_input['activation'] = 1;
                     $ptmn->add($a_input);
                  }
               }
               $query_update = "UPDATE glpi_plugin_fusioninventory_construct_device
                  SET snmpmodel_id='".$id."'
                  WHERE ID='".$data["ID"]."'";
               $DB->query($query_update);

            }


         }
      }

       // Add Number
       //key : Networking0006
      $query = "SELECT * FROM glpi_plugin_fusioninventory_model_infos
         WHERE discovery_key LIKE 'Networking%'
         ORDER BY discovery_key DESC
         LIMIT 1";
      $result = $DB->query($query);
      $data = $DB->fetch_assoc($result);
      $num = 1;
      if (!empty($data['discovery_key'])) {
         $num = str_replace('Networking', '', $data['discovery_key']);
         $num++;
      }

      $query = "SELECT * FROM glpi_plugin_fusioninventory_model_infos
         WHERE (discovery_key IS NULL OR discovery_key='')
            AND device_type='".NETWORKING_TYPE."' ";
      if ($result = $DB->query($query)) {
			while ($data = $DB->fetch_array($result)) {
            while(strlen($num) < 4)
               $num = "0" . $num;
            $query_update = "UPDATE glpi_plugin_fusioninventory_model_infos
               SET discovery_key='Networking".$num."'
                  WHERE ID='".$data['ID']."'";
            $DB->query($query_update);
            $num++;
         }
      }
      // Printers
      $query = "SELECT * FROM glpi_plugin_fusioninventory_model_infos
         WHERE discovery_key LIKE 'Printer%'
         ORDER BY discovery_key DESC
         LIMIT 1";
      $result = $DB->query($query);
      $data = $DB->fetch_assoc($result);
      if (empty($data['discovery_key'])) {
         $num = '1';
      } else {
         $num = str_replace('Printer', '', $data['discovery_key']);
         $num++;
      }

      $query = "SELECT * FROM glpi_plugin_fusioninventory_model_infos
         WHERE (discovery_key IS NULL OR discovery_key='')
            AND device_type='".PRINTER_TYPE."' ";
      if ($result = $DB->query($query)) {
			while ($data = $DB->fetch_array($result)) {
            while(strlen($num) < 4)
               $num = "0" . $num;
            $query_update = "UPDATE glpi_plugin_fusioninventory_model_infos
               SET discovery_key='Printer".$num."'
                  WHERE ID='".$data['ID']."'";
            $DB->query($query_update);
            $num++;
         }
      }
   }



   function generateDiscovery() {
      global $DB;
      
      $xmlstr = "<?xml version='1.0' encoding='UTF-8'?>
<SNMPDISCOVERY>
</SNMPDISCOVERY>";
      $sxml = new SimpleXMLElement($xmlstr);
      //$sxml = simplexml_load_file($xmlstr);

      $query = "SELECT * FROM `".$this->table."`
         WHERE type NOT IN('', 0) ";
      if ($result = $DB->query($query)) {
			while ($data = $DB->fetch_array($result)) {
            $sxml_device = $sxml->addChild('DEVICE');
            $data['sysdescr'] = str_replace("\n", "", $data['sysdescr']);
            $data['sysdescr'] = str_replace("\r", "", $data['sysdescr']);
            $sxml_device->addChild('SYSDESCR', "<![CDATA[".$data['sysdescr']."]]>");
            $sxml_device->addChild('MANUFACTURER', $data['FK_glpi_enterprise']); //dropdown
            $sxml_device->addChild('TYPE', $data['type']);

            if (($data['snmpmodel_id'] !='0') AND ($data['snmpmodel_id'] != '')) {
               //$sxml_device->addAttribute('MODELSNMP', $data['snmpmodel_id']); //dropdown

               $query_modelkey = "SELECT * FROM `glpi_plugin_fusioninventory_model_infos`
                  WHERE ID='".$data['snmpmodel_id']."'
                     LIMIT 1";
               $result_modelkey=$DB->query($query_modelkey);
               if ($DB->numrows($result_modelkey)) {
                  $line = mysql_fetch_assoc($result_modelkey);
                  $sxml_device->addChild('MODELSNMP', $line['discovery_key']);
               }               

               $query_serial = "SELECT * FROM `glpi_plugin_fusioninventory_construct_mibs`
                  WHERE `construct_device_id`='".$data['ID']."'
                     AND `mapping_name`='serial'
                  LIMIT 1";
               $result_serial=$DB->query($query_serial);
               if ($DB->numrows($result_serial)) {
                  $line = mysql_fetch_assoc($result_serial);
                  $sxml_device->addChild('SERIAL', getDropdownName('glpi_dropdown_plugin_fusioninventory_mib_oid',
                                               $line['mib_oid_id']));
               }

               $query_serial = "SELECT * FROM `glpi_plugin_fusioninventory_construct_mibs`
                  WHERE `construct_device_id`='".$data['ID']."'
                     AND ((`mapping_name`='macaddr' AND mapping_type='2')
                           OR ( `mapping_name`='ifPhysAddress' AND mapping_type='3')
                           OR ( `mapping_name`='ifPhysAddress' AND mapping_type='1'))
                  LIMIT 1";
               $result_serial=$DB->query($query_serial);
               if ($DB->numrows($result_serial)) {
                  $line = mysql_fetch_assoc($result_serial);
                  if ($line['mapping_name'] == "macaddr") {
                     $sxml_device->addChild('MAC', getDropdownName('glpi_dropdown_plugin_fusioninventory_mib_oid',
                                                   $line['mib_oid_id']));
                  } else {
                     $sxml_device->addChild('MACDYN', getDropdownName('glpi_dropdown_plugin_fusioninventory_mib_oid',
                                                   $line['mib_oid_id']));
                  }
               }
            }
            if (strstr($data['sysdescr'], "^M")) {
               $data['sysdescr'] = str_replace("^M", "", $data['sysdescr']);

               $sxml_device = $sxml->addChild('DEVICE');
               $sxml_device->addChild('SYSDESCR', "<![CDATA[".$data['sysdescr']."]]>");
               $sxml_device->addChild('MANUFACTURER', $data['FK_glpi_enterprise']); //dropdown
               $sxml_device->addChild('TYPE', $data['type']);

               if (($data['snmpmodel_id'] !='0') AND ($data['snmpmodel_id'] != '')) {
                  //$sxml_device->addAttribute('MODELSNMP', $data['snmpmodel_id']); //dropdown

                  $query_modelkey = "SELECT * FROM `glpi_plugin_fusioninventory_model_infos`
                     WHERE ID='".$data['snmpmodel_id']."'
                        LIMIT 1";
                  $result_modelkey=$DB->query($query_modelkey);
                  if ($DB->numrows($result_modelkey)) {
                     $line = mysql_fetch_assoc($result_modelkey);
                     $sxml_device->addChild('MODELSNMP', $line['discovery_key']);
                  }

                  $query_serial = "SELECT * FROM `glpi_plugin_fusioninventory_construct_mibs`
                     WHERE `construct_device_id`='".$data['ID']."'
                        AND `mapping_name`='serial'
                     LIMIT 1";
                  $result_serial=$DB->query($query_serial);
                  if ($DB->numrows($result_serial)) {
                     $line = mysql_fetch_assoc($result_serial);
                     $sxml_device->addChild('SERIAL', getDropdownName('glpi_dropdown_plugin_fusioninventory_mib_oid',
                                                  $line['mib_oid_id']));
                  }

                  $query_serial = "SELECT * FROM `glpi_plugin_fusioninventory_construct_mibs`
                     WHERE `construct_device_id`='".$data['ID']."'
                        AND ((`mapping_name`='macaddr' AND mapping_type='2')
                              OR ( `mapping_name`='ifPhysAddress' AND mapping_type='3')
                              OR ( `mapping_name`='ifPhysAddress' AND mapping_type='1'))
                     LIMIT 1";
                  $result_serial=$DB->query($query_serial);
                  if ($DB->numrows($result_serial)) {
                     $line = mysql_fetch_assoc($result_serial);
                     if ($line['mapping_name'] == "macaddr") {
                        $sxml_device->addChild('MAC', getDropdownName('glpi_dropdown_plugin_fusioninventory_mib_oid',
                                                      $line['mib_oid_id']));
                     } else {
                        $sxml_device->addChild('MACDYN', getDropdownName('glpi_dropdown_plugin_fusioninventory_mib_oid',
                                                      $line['mib_oid_id']));
                     }
                  }
               }

            }
            if (preg_match('/ $/', $data['sysdescr'])) {
               $data['sysdescr'] = preg_replace("/ $/", "", $data['sysdescr']);

               $sxml_device = $sxml->addChild('DEVICE');
               $sxml_device->addChild('SYSDESCR', "<![CDATA[".$data['sysdescr']."]]>");
               $sxml_device->addChild('MANUFACTURER', $data['FK_glpi_enterprise']); //dropdown
               $sxml_device->addChild('TYPE', $data['type']);

               if (($data['snmpmodel_id'] !='0') AND ($data['snmpmodel_id'] != '')) {
                  //$sxml_device->addAttribute('MODELSNMP', $data['snmpmodel_id']); //dropdown

                  $query_modelkey = "SELECT * FROM `glpi_plugin_fusioninventory_model_infos`
                     WHERE ID='".$data['snmpmodel_id']."'
                        LIMIT 1";
                  $result_modelkey=$DB->query($query_modelkey);
                  if ($DB->numrows($result_modelkey)) {
                     $line = mysql_fetch_assoc($result_modelkey);
                     $sxml_device->addChild('MODELSNMP', $line['discovery_key']);
                  }

                  $query_serial = "SELECT * FROM `glpi_plugin_fusioninventory_construct_mibs`
                     WHERE `construct_device_id`='".$data['ID']."'
                        AND `mapping_name`='serial'
                     LIMIT 1";
                  $result_serial=$DB->query($query_serial);
                  if ($DB->numrows($result_serial)) {
                     $line = mysql_fetch_assoc($result_serial);
                     $sxml_device->addChild('SERIAL', getDropdownName('glpi_dropdown_plugin_fusioninventory_mib_oid',
                                                  $line['mib_oid_id']));
                  }

                  $query_serial = "SELECT * FROM `glpi_plugin_fusioninventory_construct_mibs`
                     WHERE `construct_device_id`='".$data['ID']."'
                        AND ((`mapping_name`='macaddr' AND mapping_type='2')
                              OR ( `mapping_name`='ifPhysAddress' AND mapping_type='3')
                              OR ( `mapping_name`='ifPhysAddress' AND mapping_type='1'))
                     LIMIT 1";
                  $result_serial=$DB->query($query_serial);
                  if ($DB->numrows($result_serial)) {
                     $line = mysql_fetch_assoc($result_serial);
                     if ($line['mapping_name'] == "macaddr") {
                        $sxml_device->addChild('MAC', getDropdownName('glpi_dropdown_plugin_fusioninventory_mib_oid',
                                                      $line['mib_oid_id']));
                     } else {
                        $sxml_device->addChild('MACDYN', getDropdownName('glpi_dropdown_plugin_fusioninventory_mib_oid',
                                                      $line['mib_oid_id']));
                     }
                  }
               }

            }
         }
      }
      $sxml = $this->formatXmlString($sxml);
      echo $sxml->asXML();
      file_put_contents(GLPI_PLUGIN_DOC_DIR."/fusioninventory/discovery.xml", $sxml->asXML());

      $xmlstr = "package FusionInventory::Agent::Task::NetDiscovery::Dico;

use strict;
use XML::Simple;


sub loadDico {

   my \$dico = '".$sxml->asXML()."';

   my \$xmlDico = new XML::Simple;
   return XMLin(\$dico);

   1;
}

1;";
      file_put_contents(GLPI_PLUGIN_DOC_DIR."/fusioninventory/Dico.pm", utf8_encode($xmlstr));

   }


   function formatXmlString($sxml) {
      $xml = str_replace("><", ">\n<", $sxml->asXML());
      //$xml = str_replace("^M", "", $xml);
      $token      = strtok($xml, "\n");
      $result     = '';
      $pad        = 0;
      $matches    = array();
      $indent     = 0;

      while ($token !== false) {
         // 1. open and closing tags on same line - no change
         if (preg_match('/.+<\/\w[^>]*>$/', $token, $matches)) :
            $indent=0;
         // 2. closing tag - outdent now
         elseif (preg_match('/^<\/\w/', $token, $matches)) :
            $pad = $pad-3;
         // 3. opening tag - don't pad this one, only subsequent tags
         elseif (preg_match('/^<\w[^>]*[^\/]>.*$/', $token, $matches)) :
            $indent=3;
         else :
            $indent = 0;
         endif;

         $line    = str_pad($token, strlen($token)+$pad, '  ', STR_PAD_LEFT);
         $result .= $line . "\n";
         $token   = strtok("\n");
         $pad    += $indent;
      }
      $result = str_replace("&lt;![CDATA[", "<![CDATA[", $result);
      $result = str_replace("]]&gt;", "]]>", $result);
      $sxml = simplexml_load_string($result);
      return $sxml;
   }

   function cleanmodels() {
      global $DB;

      $query_models = "SELECT * FROM glpi_plugin_fusioninventory_model_infos";
      if ($result_models = $DB->query($query_models)) {
         while ($data_models = $DB->fetch_array($result_models)) {
            $query = "SELECT * FROM glpi_plugin_fusioninventory_construct_device
               WHERE snmpmodel_id='".$data_models['ID']."' ";
            if ($result = $DB->query($query)) {
               if ($DB->numrows($result) == 0) {
                  // Delete model
                  $query_delete = "DELETE FROM glpi_plugin_fusioninventory_model_infos
                     WHERE ID='".$data_models['ID']."'";
                  $DB->query($query_delete);
               }
            }
         }
       }
   }


   function exportmodels() {
      global $DB;

      $pfiie = new PluginFusionInventoryImportExport;

      $query_models = "SELECT * FROM glpi_plugin_fusioninventory_model_infos";
      if ($result_models = $DB->query($query_models)) {
         while ($data = $DB->fetch_array($result_models)) {
            $xml = $pfiie->plugin_fusioninventory_export($data['ID']);
            file_put_contents(GLPI_PLUGIN_DOC_DIR."/fusioninventory/models/".$data['name'].".xml", $xml);
         }
      }
   }

   

   function generatecomments() {
      global $DB;

      $query_clean = "UPDATE `glpi_plugin_fusioninventory_model_infos`
         SET comments='' ";
      $DB->query($query_clean);

      $a_devices = $this->find("snmpmodel_id > 0", "sysdescr");
      $a_comments = array();
      if (count($a_devices)){
         foreach ($a_devices as $device){
            if (!isset($a_comments[$device['snmpmodel_id']])) {
               $a_comments[$device['snmpmodel_id']] = "";
            }
            $a_comments[$device['snmpmodel_id']] .= $device['sysdescr']."\n\n";
         }
      }
      foreach ($a_comments as $model_id=>$comments) {
         $query_update = "UPDATE `glpi_plugin_fusioninventory_model_infos`
            SET comments='".$comments."'
            WHERE ID='".$model_id."' ";
         $DB->query($query_update);
      }      
   }


   function exportall() {
      global $DB;


		plugin_fusioninventory_checkRight("snmp_models","r");
		$query = "SELECT *
                FROM `glpi_dropdown_plugin_fusioninventory_mib_oid`";

      $xml = "<xml>\n";
      $xml .= "  <glpi_dropdown_plugin_fusioninventory_mib_oid>\n";
		if ($result=$DB->query($query)) {
			while ($data=$DB->fetch_array($result)) {
            $xml .= "    <line>\n";
            $xml .= "      <ID>".$data["ID"]."</ID>\n";
            $xml .= "      <name><![CDATA[".$data["name"]."]]></name>\n";
            $xml .= "      <comments><![CDATA[".$data["comments"]."]]></comments>\n";
            $xml .= "    </line>\n";
			}
		}
		$xml .= "  </glpi_dropdown_plugin_fusioninventory_mib_oid>\n";
      $xml .= "  <glpi_plugin_fusioninventory_construct_device>\n";
      $query = "SELECT *
                FROM `glpi_plugin_fusioninventory_construct_device`";
      if ($result=$DB->query($query)) {
			while ($data=$DB->fetch_array($result)) {
            $xml .= "    <line>\n";
            $xml .= "      <ID>".$data["ID"]."</ID>\n";
            $xml .= "      <FK_glpi_enterprise><![CDATA[".$data["FK_glpi_enterprise"]."]]></FK_glpi_enterprise>\n";
            $xml .= "      <device><![CDATA[".$data["device"]."]]></device>\n";
            $xml .= "      <firmware><![CDATA[".$data["firmware"]."]]></firmware>\n";
            $xml .= "      <sysdescr><![CDATA[".$data["sysdescr"]."]]></sysdescr>\n";
            $xml .= "      <type><![CDATA[".$data["type"]."]]></type>\n";
            $xml .= "      <snmpmodel_id><![CDATA[".$data["snmpmodel_id"]."]]></snmpmodel_id>\n";
            $xml .= "    </line>\n";
			}
		}
      $xml .= "  </glpi_plugin_fusioninventory_construct_device>\n";
      $xml .= "  <glpi_plugin_fusioninventory_construct_mibs>\n";
      $query = "SELECT *
                FROM `glpi_plugin_fusioninventory_construct_mibs`";
      if ($result=$DB->query($query)) {
			while ($data=$DB->fetch_array($result)) {
            $xml .= "    <line>\n";
            $xml .= "      <ID>".$data["ID"]."</ID>\n";
            $xml .= "      <mib_oid_id><![CDATA[".$data["mib_oid_id"]."]]></mib_oid_id>\n";
            $xml .= "      <construct_device_id><![CDATA[".$data["construct_device_id"]."]]></construct_device_id>\n";
            $xml .= "      <mapping_name><![CDATA[".$data["mapping_name"]."]]></mapping_name>\n";
            $xml .= "      <oid_port_counter><![CDATA[".$data["oid_port_counter"]."]]></oid_port_counter>\n";
            $xml .= "      <oid_port_dyn><![CDATA[".$data["oid_port_dyn"]."]]></oid_port_dyn>\n";
            $xml .= "      <mapping_type><![CDATA[".$data["mapping_type"]."]]></mapping_type>\n";
            $xml .= "      <vlan><![CDATA[".$data["vlan"]."]]></vlan>\n";
            $xml .= "    </line>\n";
			}
		}
      $xml .= "  </glpi_plugin_fusioninventory_construct_mibs>\n";
      $xml .= "  <glpi_plugin_fusioninventory_construct_walks>\n";
      $query = "SELECT *
                FROM `glpi_plugin_fusioninventory_construct_walks`";
      if ($result=$DB->query($query)) {
			while ($data=$DB->fetch_array($result)) {
            $xml .= "    <line>\n";
            $xml .= "      <ID>".$data["ID"]."</ID>\n";
            $xml .= "      <construct_device_id><![CDATA[".$data["construct_device_id"]."]]></construct_device_id>\n";
            $xml .= "      <log><![CDATA[".$data["log"]."]]></log>\n";
            $xml .= "    </line>\n";
			}
		}
      $xml .= "  </glpi_plugin_fusioninventory_construct_walks>\n";

		$xml .= "</xml>\n";
      file_put_contents(GLPI_PLUGIN_DOC_DIR."/fusioninventory/exportall.xml", $xml);
   }


   function formImportall() {
      global $LANG;

      echo "<form method='post' name='' id=''  action='".$target."' enctype=\"multipart/form-data\">";
      echo "<table class='tab_cadre' cellpadding='5' width='950'>";
      echo "<tr class='tab_bg_1 center'>";
      echo "<th>";
      echo "Import file&nbsp;:";
      echo "</th>";
      echo "</tr>";
      echo "<tr class='tab_bg_1 center'>";
      echo "<td>";
      echo "<input type='file' name='importallfile'/>";
      echo "&nbsp;<input type='submit' name='addimportall' value=\"" . $LANG["buttons"][8] . "\" class='submit' >";
      echo "</td>";
      echo "</tr>";
      echo "</table>";
      echo "</form>";

      
   }

   function importall() {
      global $DB;

      $query = "SELECT * FROM `glpi_dropdown_plugin_fusioninventory_mib_oid`";
      if ($result=$DB->query($query)) {
			while ($data=$DB->fetch_array($result)) {
            $dataname = $data['name'];
            $a_db_devices["'$dataname'"] = $data;
         }
      }

//print_r($a_db_devices);
      $xml = @simplexml_load_file(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/importall.xml','SimpleXMLElement', LIBXML_NOCDATA);
      echo "<form method='post' name='' id=''  action='".$target."' >";
      echo "<table class='tab_cadre' width='950'>";
      echo "<tr class='tab_bg_1'>";
      echo "<th>name in DB</th>"; //name
      echo "<th>Comment in DB</th>"; //comment
      echo "<th></th>";
      echo "<th></th>";
      echo "<th>Name in XML</th>"; //name
      echo "<th>Comment in XML</th>"; //comment
      echo "</tr>";
      $i = 0;
      foreach ($xml->glpi_dropdown_plugin_fusioninventory_mib_oid->line as $line) {
         if (isset($a_db_devices["'".$line->name."'"])) {
            //echo "exist<br/>";
            if ($a_db_devices["'".$line->name."'"]['comments'] != $line->comments) {
               $checked = '';
               if ($a_db_devices["'".$line->name."'"]['comments'] != $line->comments) {
                  $checked = 'checked';
               }
               echo "<tr class='tab_bg_1'>";
               echo "<td>".$a_db_devices["'".$line->name."'"]['name']."</td>"; //name
               echo "<td>".$a_db_devices["'".$line->name."'"]['comments']."</td>"; //comment
               echo "<td><input type='radio' name='oid".$i."' value='db'/></td>";
               echo "<td><input type='radio' name='oid".$i."' value='xml' ".$checked."/></td>";
               echo "<td>".$line->name."</td>"; //name
               echo "<td><strong>".$line->comments."</strong></td>"; //comment
               echo "</tr>";
               $i++;
            }
         } else {
//            echo "Not exist!<br/>";
            echo "<tr class='tab_bg_1'>";
            echo "<td></td>"; //name
            echo "<td></td>"; //comment
            echo "<td><input type='radio' name='oid".$i."' value='insdb'/></td>";
            echo "<td><input type='radio' name='oid".$i."' value='insxml' checked/></td>";
            echo "<td><strong>".$line->name."</strong></td>"; //name
            echo "<td><strong>".$line->comments."</strong></td>"; //comment
            echo "</tr>";
            $i++;
         }
      }
      echo "<tr class='tab_bg_1'>";
      echo "<td colspan='6' align='center'>";
      echo "<input type='submit' class='submit' name='miboid' value='Update'/>";
      echo "</td>";
      echo "</table>";
      echo "</form>";

   }

   function importmiboid($input) {
      global $DB;
      
      $query = "SELECT * FROM `glpi_dropdown_plugin_fusioninventory_mib_oid`";
      if ($result=$DB->query($query)) {
			while ($data=$DB->fetch_array($result)) {
            $dataname = $data['name'];
            $a_db_devices["'$dataname'"] = $data;
         }
      }

      $xml = @simplexml_load_file(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/importall.xml','SimpleXMLElement', LIBXML_NOCDATA);
      $i = 0;
      foreach ($xml->glpi_dropdown_plugin_fusioninventory_mib_oid->line as $line) {
         if ((isset($input['oid'.$i])) AND ($input['oid'.$i] == "xml")) {
            // Update DB
            $query = "UPDATE `glpi_dropdown_plugin_fusioninventory_mib_oid`
               SET `name`='".$line->name."', `comments`='".$line->comments."'
               WHERE `ID`='".$a_db_devices["'".$line->name."'"][ID]."' ";
            $DB->query($query);           
         } else if ((isset($input['oid'.$i])) AND ($input['oid'.$i] == "insxml")) {
            $query = "INSERT INTO `glpi_dropdown_plugin_fusioninventory_mib_oid`
              (`name`, `comments`) VALUES ('".$line->name."', '".$line->comments."')";
            $DB->query($query);
         }
         $i++;
      }
   }


   function showFormImportConstructDevice() {
      global $DB;

      $query = "SELECT * FROM `glpi_plugin_fusioninventory_construct_device`";
      if ($result=$DB->query($query)) {
			while ($data=$DB->fetch_array($result)) {
            $datasysdescr = $data['sysdescr'];
            $a_db_devices["'".$datasysdescr."'"] = $data;
         }
      }


      // Detect constructdevice
      $xml = @simplexml_load_file(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/importall.xml','SimpleXMLElement', LIBXML_NOCDATA);
      $i = 0;
      foreach ($xml->glpi_plugin_fusioninventory_construct_device->line as $line) {
         $line->sysdescr = str_replace("\n", "\r\n", $line->sysdescr);
         echo "<table class='tab_cadre' width='950'>";
         if (isset($a_db_devices["'".$line->sysdescr."'"])) {
            // Exist
            echo "<tr class='tab_bg_1'>";
            echo "<td>";
            echo $line->sysdescr;
            echo "</td>";
            echo "</tr>";

            $a_mibs = array();
            $query = "SELECT * FROM `glpi_plugin_fusioninventory_construct_mibs`
               WHERE `construct_device_id`='".$a_db_devices["'".$line->sysdescr."'"]['ID']."' ";
            if ($result=$DB->query($query)) {
               while ($data=$DB->fetch_array($result)) {
                  for($j = 0; $j<8; $j++) {
                     unset($data[$j]);
                  }
                  $a_mibs[$data['mapping_name']] = $data;
               }
            }
            foreach ($xml->glpi_plugin_fusioninventory_construct_mibs->line as $line_mibs) {
               if ("$line_mibs->construct_device_id" == "$line->ID") {
                  if (isset($a_mibs["$line_mibs->mapping_name"])) {
                     // Same mapping, now verify same values
                     echo "<tr class='tab_bg_1'>";
                     echo "<td align='center'>";
                     echo $line_mibs->mapping_name;
                     echo "<br/><table>";
                     foreach ($a_mibs["$line_mibs->mapping_name"] as $key => $value) {
                        if ($value != $line_mibs[$key]) {
                           echo "<tr>";
                           echo "<td>";
                           echo $value." - ".$line_mibs->{"$key"};
                           echo "</td>";
                           echo "</tr>";
                        }
                     }


                     echo "</table>";

                     echo "</td>";
                     echo "</tr>";
                     unset($a_mibs[$line_mibs->mapping_name]);
                  } else {
                     // New register
                     echo "<tr class='tab_bg_1'>";
                     echo "<td align='center'>";
                     echo "New : ".$line_mibs->mapping_name;
                     echo "</td>";
                     echo "</tr>";

                  }
               }
            }
            foreach ($a_mibs as $mappingname=>$datasmib) {
               // Delete or not ?
            }
         } else {
            // not exist
            echo "<tr class='tab_bg_1'>";
            echo "<td>";
            echo $line->sysdescr;
            echo "</td>";
            echo "</tr>";
            
            echo "<tr class='tab_bg_1'>";
            echo "<td align='center'>";
            echo "<input type='checkbox' name='device".$i."' value='import' /> Import";
            echo "</td>";
            echo "</tr>";

            $i++;
         }
         echo "</table>";
         echo "<br/>";

         
      }


echo "Number of devices non exist : ".$i;
      // detect all oids

      exit;
   }

}

?>