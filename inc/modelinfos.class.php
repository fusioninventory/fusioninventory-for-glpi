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

class PluginFusionInventoryModelInfos extends CommonDBTM {
   
	function __construct() {
		$this->table = "glpi_plugin_fusioninventory_model_infos";
		$this->type = PLUGIN_FUSIONINVENTORY_MODEL;
	}

	function showForm($target, $ID = '') {
		global $DB,$CFG_GLPI,$LANG;

		plugin_fusioninventory_checkRight("snmp_models","r");

		if ($ID!='') {
			$this->getFromDB($ID);
      } else {
			$this->getEmpty();	
      }

      $this->showTabs($ID, "",$_SESSION['glpi_tab']);
		echo "<div align='center'><form method='post' name='' id=''  action=\"" . $target . "\">";

		echo "<table class='tab_cadre' cellpadding='5' width='950'><tr><th colspan='2'>";
		echo ($ID =='' ? $LANG['plugin_fusioninventory']["model_info"][7] :
            $LANG['plugin_fusioninventory']["model_info"][6]);
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

		$selected_value = $this->fields["device_type"];
		echo "<select name='device_type'>\n";
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

		echo "<tr class='tab_bg_2'><td colspan='2'>";
      if(plugin_fusioninventory_HaveRight("snmp_models","w")) {
         if ($ID=='') {
            echo "<div align='center'><input type='submit' name='add' value=\"" . $LANG["buttons"][8] .
                 "\" class='submit' >";
         } else {
            echo "<input type='hidden' name='ID' value='" . $ID . "'/>";
            echo "<div align='center'><input type='submit' name='update' value=\"".$LANG["buttons"][7].
                 "\" class='submit' >";
            if (!$this->fields["deleted"]) {
               echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' name='delete' value=\"" .
                    $LANG["buttons"][6] . "\" class='submit'>";
            } else {
               echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' name='restore' value=\"" .
                    $LANG["buttons"][21] . "\" class='submit'>";

               echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' name='purge' value=\"" .
                    $LANG["buttons"][22] . "\" class='submit'>";
            }
         }
      }
		echo "</td>";
		echo "</tr>";
		echo "</table></form></div>";
	}
	
	
	
	/**
	 * Get all OIDs from model 
	 *
	 * @param $ID_Device ID of the device
	 * @param $type type of device (NETWORKING_TYPE, PRINTER_TYPE ...)
	 *
	 * @return OID list in array
	 *
	**/
	function oidlist($ID_Device,$type) {
		global $DB;

		switch ($type) {
			case NETWORKING_TYPE :
				$query = "SELECT * 
                      FROM `glpi_plugin_fusioninventory_networking`
                           LEFT JOIN `glpi_plugin_fusioninventory_mib_networking`
                           ON `glpi_plugin_fusioninventory_networking`.`FK_model_infos`=
                              `glpi_plugin_fusioninventory_mib_networking`.`FK_model_infos`
                      WHERE `FK_networking`='".$ID_Device."'
                            AND `glpi_plugin_fusioninventory_mib_networking`.`activation`='1' ";
				break;

			case PRINTER_TYPE :
				$query = "SELECT * 
                      FROM `glpi_plugin_fusioninventory_printers`
                           LEFT JOIN `glpi_plugin_fusioninventory_mib_networking`
                           ON `glpi_plugin_fusioninventory_printers`.`FK_model_infos`=
                              `glpi_plugin_fusioninventory_mib_networking`.`FK_model_infos`
                      WHERE `FK_printers`='".$ID_Device."'
                            AND `glpi_plugin_fusioninventory_mib_networking`.`activation`='1' ";
				break;
		}
		if (!empty($query)) {
			$result=$DB->query($query);
			$exclude = array();
			while ($data=$DB->fetch_array($result)) {
				$oids[$data['oid_port_counter']][$data['oid_port_dyn']][$data['mapping_name']] =
               getDropdownName('glpi_dropdown_plugin_fusioninventory_mib_oid',$data['FK_mib_oid']);
         }
			return $oids;
		}
	}


   function getrightmodel($device_id, $type) {
      global $DB;

      // Get description (sysdescr) of device
      // And search in device_serials base
      $sysdescr = '';
      switch($type) {

         case NETWORKING_TYPE:
            $Netdevice = new Netdevice;
            $Netdevice->check($device_id,'r');
            $sysdescr = $Netdevice->fields["comments"];
            break;

         case PRINTER_TYPE:
            $Printer = new Printer;
            $Printer->check($device_id,'r');
            $sysdescr = $Printer->fields["comments"];
            break;

      }
      $sysdescr = str_replace("\r", "", $sysdescr);
      if (!empty($sysdescr)) {
         $xml = @simplexml_load_file(GLPI_ROOT.'/plugins/fusioninventory/tool/discovery.xml');
         foreach ($xml->DEVICE as $device) {
            $device['SYSDESCR'] = str_replace("\r", "", $device['SYSDESCR']);
            if ($sysdescr == $device['SYSDESCR']) {
               if (isset($device['MODELSNMP'])) {
                  $modelgetted = $device['MODELSNMP'];
               }
               break;
            }
         }
         if (!empty($modelgetted)) {
            $query = "SELECT * 
                      FROM `glpi_plugin_fusioninventory_model_infos`
                      WHERE `discovery_key`='".$modelgetted."'
                      LIMIT 0,1";
				$result = $DB->query($query);
				$data = $DB->fetch_assoc($result);
				$FK_model = $data['ID'];
            // Udpate Device with this model
            switch($type) {

               case NETWORKING_TYPE:
                  $query = "UPDATE `glpi_plugin_fusioninventory_networking`
                            SET `FK_model_infos`='".$FK_model."'
                            WHERE `FK_networking`='".$device_id."'";
                  $DB->query($query);
                  break;

               case PRINTER_TYPE:
                  $query = "UPDATE `glpi_plugin_fusioninventory_printers`
                            SET `FK_model_infos`='".$FK_model."'
                            WHERE `FK_printers`='".$device_id."'";
                  $DB->query($query);
                  break;

            }
         }
      }
   }
}

?>