<?php
/*
 * @version $Id$
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2009 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org
 -------------------------------------------------------------------------

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
 --------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: MAZZONI Vincent
// Purpose of file: modelisation of a networking switch
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

require_once(GLPI_ROOT.'/plugins/fusinvsnmp/inc/commondbtm.class.php');

/**
 * Class to use networking switches
 **/
class PluginFusinvsnmpNetworkEquipment extends PluginFusinvsnmpCommonDBTM {
   private $ports=array(), $ifaddrs=array();
   private $oFusionInventory_networkequipment;
   private $newPorts=array(), $updatesPorts=array();
   private $newIfaddrs=array(), $updatesIfaddrs=array();

	/**
	 * Constructor
	**/
   function __construct() {
      parent::__construct("glpi_networkequipments");
      $this->dohistory=true;
      $this->oFusionInventory_networkequipment = new PluginFusinvsnmpCommonDBTM("glpi_plugin_fusinvsnmp_networkequipments");
   }

   /**
    * Load an existing networking switch
    *
    *@return nothing
    **/
   function load($p_id='') {
      global $DB;

      parent::load($p_id);
      $this->ifaddrs = $this->getIfaddrsDB();
      $this->ports = $this->getPortsDB();

      $query = "SELECT `id`
                FROM `glpi_plugin_fusinvsnmp_networkequipments`
                WHERE `networkequipments_id` = '".$this->getValue('id')."';";
      if ($result = $DB->query($query)) {
         if ($DB->numrows($result) != 0) {
            $fusioninventory = $DB->fetch_assoc($result);
            $this->oFusionInventory_networkequipment->load($fusioninventory['id']);
            $this->ptcdLinkedObjects[]=$this->oFusionInventory_networkequipment;
         } else {
            $input = array();
            $input['networkequipments_id'] = $this->getValue('id');
            $id = $this->oFusionInventory_networkequipment->add($input);
            $this->oFusionInventory_networkequipment->load($id);
            $this->ptcdLinkedObjects[]=$this->oFusionInventory_networkequipment;
            $this->ptcdLinkedObjects[]=$input;
         }
      }
   }

   /**
    * Update an existing preloaded switch with the instance values
    *
    *@return nothing
    **/
   function updateDB() {
      if (array_key_exists('networkequipmentmodels_id', $this->ptcdUpdates)) {
         $this->ptcdUpdates['networkequipmentmodels_id'] = Dropdown::importExternal("NetworkEquipmentModel",
                                                   $this->ptcdUpdates['networkequipmentmodels_id']);
      }
      if (array_key_exists('networkequipmentfirmwares_id', $this->ptcdUpdates)) {
         $this->ptcdUpdates['networkequipmentfirmwares_id'] = Dropdown::importExternal("NetworkEquipmentFirmware",
                                                   $this->ptcdUpdates['networkequipmentfirmwares_id']);
      }
      if (array_key_exists('locations_id', $this->ptcdUpdates)) {
         $entity = $this->getValue('entities_id');
         if (!isset($entity)) {
            $entity = '-1';
         }
         $this->ptcdUpdates['locations_id'] = Dropdown::importExternal('Location',
                                                   $this->ptcdUpdates['locations_id'],
                                                   $entity);
      }

      parent::updateDB();
      //$a_networkequipment = $this->ptcdUpdates;

      // update last_fusioninventory_update even if no other update
      $this->setValue('last_fusioninventory_update', date("Y-m-d H:i:s"));
      $this->oFusionInventory_networkequipment->updateDB();
      // ports
      $this->savePorts();
   }

   /**
    * Get ports
    *
    *@return Array of ports instances
    **/
   private function getPortsDB() {
      global $DB;

      $ptp = new PluginFusinvsnmpNetworkPort();
      $query = "SELECT `id`
                FROM `glpi_networkports`
                WHERE `items_id` = '".$this->getValue('id')."'
                      AND `itemtype` = '".NETWORKING_TYPE."';";
      $portsIds = array();
      if ($result = $DB->query($query)) {
         if ($DB->numrows($result) != 0) {
            while ($port = $DB->fetch_assoc($result)) {
               $ptp->load($port['id']);
               $portsIds[] = clone $ptp;
            }
         }
      }
      return $portsIds;
   }

   /**
    * Get ports
    *
    *@return Array of ports id
    **/
   function getPorts() {
      return $this->ports;
   }

   /**
    * Get index of port object
    *
    *@param $p_mac MAC address
    *@param $p_ip='' IP address
    *@return Index of port object in ports array or '' if not found
    **/
   function getPortIndex($p_ifnumber, $p_ip='') {
      $portIndex = '';
      foreach ($this->ports as $index => $oPort) {
         if (is_object($oPort)) { // should always be true
            if ($oPort->getValue('logical_number')==$p_ifnumber) {
               $portIndex = $index;
               break;
            }
         }
      }
//      if ($portIndex == '' AND $p_ip != '') {
//         foreach ($this->ports as $index => $oPort) {
//            if ($oPort->getValue('ip')==$p_ip) {
//               $portIndex = $index;
//               break;
//            }
//         }
//      }
      return $portIndex;
   }

   /**
    * Get index of ip object
    *
    *@param $p_ip='' IP address
    *@return Index of ip object in ifaddrs array or '' if not found
    **/
   function getIfaddrIndex($p_ip) {
      $ifaddrIndex = '';
      foreach ($this->ifaddrs as $index => $oIfaddr) {
         if (is_object($oIfaddr)) { // should always be true
            if ($oIfaddr->getValue('ip')==$p_ip) {
               $ifaddrIndex = $index;
               break;
            }
         }
      }
      return $ifaddrIndex;
   }

   /**
    * Get port object
    *
    *@param $p_index Index of port object in $ports
    *@return Port object in ports array
    **/
   function getPort($p_index) {
      return $this->ports[$p_index];
   }

   /**
    * Save new ports
    *
    *@return nothing
    **/
   function savePorts() {
      $CFG_GLPI["deleted_tables"][]="glpi_networkports"; // TODO : to clean
      
      foreach ($this->ports as $index=>$ptp) {
         if (!in_array($index, $this->updatesPorts)) { // delete ports which don't exist any more
            $ptp->deleteDB();
         }
      }
      foreach ($this->newPorts as $ptp) {
         if ($ptp->getValue('id')=='') {               // create existing ports
            $ptp->addDB($this->getValue('id'), true);
         } else {                                      // update existing ports
            $ptp->updateDB();
         }
      }
   }

   /**
    * Save ifadddrs
    *
    *@return nothing
    **/
   function saveIfaddrs() {
      $CFG_GLPI["deleted_tables"][]="glpi_plugin_fusinvsnmp_networkequipmentips"; // TODO : to clean

      $pti = new PluginFusinvsnmpNetworkEquipmentIP();
      foreach ($this->ifaddrs as $index=>$pti) {
         if (!in_array($index, $this->updatesIfaddrs)) {
            $pti->deleteDB();
         }
      }
      foreach ($this->newIfaddrs as $pti) {
         if ($pti->getValue('id')=='') {
            $pti->addDB($this->getValue('id'));
         } else {
            $pti->updateDB();
         }
      }
   }

   /**
    * Add new port
    *
    *@param $p_oPort port object
    *@param $p_portIndex='' index of port in $ports if already exists
    *@return nothing
    **/
   function addPort($p_oPort, $p_portIndex='') {
      $this->newPorts[]=$p_oPort;
      if (is_int($p_portIndex)) {
         $this->updatesPorts[]=$p_portIndex;
      }
   }

   /**
    * Get ips
    *
    *@return Array of ips instances
    **/
   private function getIfaddrsDB() {
      global $DB;

      $pti = new PluginFusinvsnmpNetworkEquipmentIp();
      $query = "SELECT `id`
                FROM `glpi_plugin_fusinvsnmp_networkequipmentips`
                WHERE `networkequipments_id` = '".$this->getValue('id')."';";
      $ifaddrsIds = array();
      if ($result = $DB->query($query)) {
         if ($DB->numrows($result) != 0) {
            while ($ip = $DB->fetch_assoc($result)) {
               $pti->load($ip['id']);
               $ifaddrsIds[] = clone $pti;
            }
         }
      }
      return $ifaddrsIds;
   }

   /**
    * Get ip object
    *
    *@param $p_index Index of ip object in $ifaddrs
    *@return Ifaddr object in ifaddrs array
    **/
   function getIfaddr($p_index) {
      return $this->ifaddrs[$p_index];
   }

   /**
    * Add IP
    *
    *@param $p_oIfaddr Ifaddr object
    *@param $p_ifaddrIndex='' index of ip in $ifaddrs if already exists
    *@return nothing
    **/
   function addIfaddr($p_oIfaddr, $p_ifaddrIndex='') {
      if (count($this->newIfaddrs)==0) { // the first IP goes in glpi_networkequipments.ip
         $this->setValue('ip', $p_oIfaddr->getValue('ip'));
      }
      $this->newIfaddrs[]=$p_oIfaddr;
      if (is_int($p_ifaddrIndex)) {
         $this->updatesIfaddrs[]=$p_ifaddrIndex;
      }
   }

	function showForm($id, $options=array()) {
		global $DB,$CFG_GLPI,$LANG;

		$history = new PluginFusinvsnmpNetworkPortLog;

		if (!PluginFusioninventoryProfile::haveRight("fusinvsnmp", "networkequipment","r")) {
			return false;
      }
		if (PluginFusioninventoryProfile::haveRight("fusinvsnmp", "networkequipment","w")) {
			$canedit = true;
      } else {
			$canedit = false;
      }

		$this->oFusionInventory_networkequipment->id = $id;

		$nw=new NetworkPort_NetworkPort();
		$plugin_fusioninventory_snmp = new PluginFusinvsnmpSNMP;

		echo "<script type='text/javascript' src='".GLPI_ROOT.
               "/lib/extjs/adapter/prototype/prototype.js'></script>";
		echo "<script type='text/javascript' src='".GLPI_ROOT.
               "/lib/extjs/adapter/prototype/effects.js'></script>";

      if (!$data = $this->oFusionInventory_networkequipment->find("`networkequipments_id`='".$id."'", '', 1)) {
         // Add in database if not exist
         $input = array();
         $input['networkequipments_id'] = $id;
         $ID_tn = $this->oFusionInventory_networkequipment->add($input);
         $this->oFusionInventory_networkequipment->getFromDB($ID_tn);
      } else {
         foreach ($data as $ID_tn=>$datas) {
            $this->oFusionInventory_networkequipment->fields = $data[$ID_tn];
         }
      }

      $PID = $this->oFusionInventory_networkequipment->fields['last_PID_update'];

		// Form networking informations
      $this->oFusionInventory_networkequipment->showFormHeader($options);

		echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>";
      echo $LANG['plugin_fusinvsnmp']["snmp"][4]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      echo "<textarea name='sysdescr' cols='45' rows='5'>";
      echo $this->oFusionInventory_networkequipment->fields['sysdescr'];
      echo "</textarea>";
      echo "</td>";
      echo "<td align='center'>";
      echo $LANG['plugin_fusinvsnmp']["snmp"][53]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      echo convDateTime($this->oFusionInventory_networkequipment->fields['last_fusioninventory_update']);
      echo "</td>";
      echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center' rowspan='2'>".$LANG['plugin_fusioninventory']["profile"][24]."&nbsp;:</td>";
		echo "<td align='center'>";
		$query_models = "SELECT *
                       FROM `glpi_plugin_fusinvsnmp_models`
                       WHERE `itemtype`!='2'
                             AND `itemtype`!='0';";
		$result_models=$DB->query($query_models);
		$exclude_models = array();
		while ($data_models=$DB->fetch_array($result_models)) {
			$exclude_models[] = $data_models['id'];
		}
      Dropdown::show("PluginFusinvsnmpModel",
                     array('name'=>"model_infos",
                           'value'=>$this->oFusionInventory_networkequipment->fields['plugin_fusinvsnmp_models_id'],
                           'comment'=>0,
                           'used'=>$exclude_models));
      echo "</td>";
      echo "<td align='center'>";
      echo $LANG['plugin_fusinvsnmp']["snmp"][13]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      displayProgressBar(250, $this->oFusionInventory_networkequipment->fields['cpu'],
                  array('simple' => true));
      echo "</td>";
		echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>";
      echo "<input type='submit' name='GetRightModel'
              value='".$LANG['plugin_fusinvsnmp']["model_info"][13]."' class='submit'/>";
      echo "</td>";
      echo "<td align='center'>";
      echo $LANG['plugin_fusinvsnmp']["snmp"][14]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      $query2 = "SELECT *
                 FROM `glpi_networkequipments`
                 WHERE `id`='".$id."';";
      $result2 = $DB->query($query2);
      $data2 = $DB->fetch_assoc($result2);
      if (empty($data2["ram"])) {
         $ram_pourcentage = 0;
      } else {
         $ram_pourcentage = ceil((100 * ($data2["ram"] - $this->oFusionInventory_networkequipment->fields['memory'])) / $data2["ram"]);
      }
      displayProgressBar(250, $ram_pourcentage,
                        array('title' => " (".($data2["ram"] - $this->oFusionInventory_networkequipment->fields['memory'])." Mo / ".
                         $data2["ram"]." Mo)"));
      echo "</td>";
      echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>".$LANG['plugin_fusioninventory']["functionalities"][43]."&nbsp;:</td>";
		echo "<td align='center'>";
		PluginFusinvsnmpSNMP::auth_dropdown($this->oFusionInventory_networkequipment->fields['plugin_fusinvsnmp_configsecurities_id']);
		echo "</td>";
      echo "<td align='center'>";
      echo $LANG['plugin_fusinvsnmp']["snmp"][12]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      $sysUpTime = $this->oFusionInventory_networkequipment->fields['uptime'];
      if (strstr($sysUpTime, "days")) {
         list($day, $hour, $minute, $sec, $ticks) = sscanf($sysUpTime, "%d days, %d:%d:%d.%d");
      } else if (strstr($sysUpTime, "hours")) {
         $day = 0;
         list($hour, $minute, $sec, $ticks) = sscanf($sysUpTime, "%d hours, %d:%d.%d");
      } else if (strstr($sysUpTime, "minutes")) {
         $day = 0;
         $hour = 0;
         list($minute, $sec, $ticks) = sscanf($sysUpTime, "%d minutes, %d.%d");
      } else if($sysUpTime == "0") {
         $day = 0;
         $hour = 0;
         $minute = 0;
         $sec = 0;
      } else {
         list($hour, $minute, $sec, $ticks) = sscanf($sysUpTime, "%d:%d:%d.%d");
         $day = 0;
      }

      echo "<b>$day</b> ".$LANG["stats"][31]." ";
      echo "<b>$hour</b> ".$LANG["job"][21]." ";
      echo "<b>$minute</b> ".$LANG["job"][22]." ";
      echo " ".strtolower($LANG["rulesengine"][42])." <b>$sec</b> ".$LANG["stats"][34]." ";
      echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_2 center'>";
		echo "<td colspan='4'>";
		echo "<input type='hidden' name='id' value='".$id."'>";
		echo "<input type='submit' name='update' value=\"".$LANG["buttons"][7]."\" class='submit' >";
		echo "</td>";
		echo "</tr>";

		$this->oFusionInventory_networkequipment->showFormButtons($options);

      echo "<br/>";

// ********************************************************************************************** //
// *********************************** METTRE TABLEAU DES PORTS ********************************* //
// ********************************************************************************************** //

		$query = "
		SELECT *,glpi_plugin_fusinvsnmp_networkports.mac as ifmacinternal

		FROM glpi_plugin_fusinvsnmp_networkports

		LEFT JOIN glpi_networkports
		ON glpi_plugin_fusinvsnmp_networkports.networkports_id = glpi_networkports.id
		WHERE glpi_networkports.items_id='".$id."'
		ORDER BY logical_number ";

		echo "<script  type='text/javascript'>
function close_array(id){
	document.getElementById('plusmoins'+id).innerHTML = '<img src=\'".GLPI_ROOT."/pics/collapse.gif\''+
      'onClick=\'Effect.Fade(\"viewfollowup'+id+'\");appear_array('+id+');\' />';
}
function appear_array(id){
	document.getElementById('plusmoins'+id).innerHTML = '<img src=\'".GLPI_ROOT."/pics/expand.gif\''+
      'onClick=\'Effect.Appear(\"viewfollowup'+id+'\");close_array('+id+');\' />';
}

		</script>";

		echo "<table class='tab_cadre' cellpadding='5' width='1100'>";

		echo "<tr class='tab_bg_1'>";
		$query_array = "SELECT *
                      FROM `glpi_displaypreferences`
                      WHERE `itemtype`='PluginFusinvsnmpNetworkPort'
                            AND `users_id`='0'
                      ORDER BY `rank`;";
		$result_array=$DB->query($query_array);
		echo "<th colspan='".(mysql_num_rows($result_array) + 2)."'>";
		echo $LANG['plugin_fusinvsnmp']["snmp"][40];
      $result=$DB->query($query);
      echo ' ('.$DB->numrows($result).')';
      if ($_SESSION["glpilanguage"] == "fr_FR") {
         $url_legend = "https://forge.indepnet.net/wiki/fusioninventory/Fr_VI_visualisationsdonnees_2_reseau";
      } else {
         $url_legend = "https://forge.indepnet.net/wiki/fusioninventory/En_VI_visualisationsdonnees_2_reseau";
      }
      echo " <a href='".$url_legend."'>[ ".$LANG['plugin_fusioninventory']["functionalities"][6]." ]</a>";
		echo "</th>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";

		echo '<th><img alt="'.$LANG['setup'][252].'"
                     title="'.$LANG['setup'][252].'"
                     src="'.GLPI_ROOT.'/pics/options_search.png" class="pointer"
                     onclick="var w = window.open(\''.GLPI_ROOT.
                        '/front/popup.php?popup=search_config&type=5157\' ,\'glpipopup\',
                        \'height=400, width=1000, top=100, left=100, scrollbars=yes\' ); w.focus();"></th>';
		echo "<th>".$LANG["common"][16]."</th>";

		$query_array = "SELECT *
                      FROM `glpi_displaypreferences`
                      WHERE `itemtype`='5157'
                             AND `users_id`='0'
                      ORDER BY `rank`;";
		$result_array=$DB->query($query_array);
		while ($data_array=$DB->fetch_array($result_array)) {
			echo "<th>";
			switch ($data_array['num']) {
				case 2 :
					echo $LANG['plugin_fusinvsnmp']["snmp"][42];
					break;

				case 3 :
					echo $LANG['plugin_fusinvsnmp']["snmp"][43];
					break;

				case 4 :
					echo $LANG['plugin_fusinvsnmp']["snmp"][44];
					break;

				case 5 :
					echo $LANG['plugin_fusinvsnmp']["snmp"][45];
					break;

				case 6 :
					echo $LANG['plugin_fusinvsnmp']["snmp"][46];
					break;

				case 7 :
					echo $LANG['plugin_fusinvsnmp']["snmp"][47];
					break;

				case 8 :
					echo $LANG['plugin_fusinvsnmp']["snmp"][48];
					break;

				case 9 :
					echo $LANG['plugin_fusinvsnmp']["snmp"][49];
					break;

				case 10 :
					echo $LANG['plugin_fusinvsnmp']["snmp"][51];
					break;

				case 11 :
					echo $LANG['plugin_fusioninventory']["mapping"][115];
					break;

				case 12 :
					echo $LANG["networking"][17];
					break;

				case 13 :
					echo $LANG['plugin_fusinvsnmp']["snmp"][50];
					break;

				case 14 :
					echo $LANG["networking"][56];
					break;

            case 15 :
					echo $LANG['plugin_fusinvsnmp']["snmp"][41];
					break;

			}
			echo "</th>";
		}
		echo "</tr>";
		// Fin de l'entÃªte du tableau

		if ($result) {
			while ($data=$DB->fetch_array($result)) {
				$background_img = "";
				if (($data["trunk"] == "1") AND (strstr($data["ifstatus"], "up")
                  OR strstr($data["ifstatus"], "1"))) {
					$background_img = " style='background-image: url(\"".GLPI_ROOT.
                                    "/plugins/fusioninventory/pics/port_trunk.png\"); '";
            } else if (($data["trunk"] == "-1") AND (strstr($data["ifstatus"], "up")
                        OR strstr($data["ifstatus"], "1"))) {
					$background_img = " style='background-image: url(\"".GLPI_ROOT.
                                    "/plugins/fusioninventory/pics/multiple_mac_addresses.png\"); '";
            } else if (strstr($data["ifstatus"], "up") OR strstr($data["ifstatus"], "1")) {
					$background_img = " style='background-image: url(\"".GLPI_ROOT.
                                    "/plugins/fusioninventory/pics/connected_trunk.png\"); '";
            }
				echo "<tr class='tab_bg_1 center' height='40'".$background_img.">";
				echo "<td id='plusmoins".$data["id"]."'><img src='".GLPI_ROOT.
                     "/pics/expand.gif' onClick='Effect.Appear(\"viewfollowup".$data["id"].
                     "\");close_array(".$data["id"].");' /></td>";
				echo "<td><a href='networkport.php?id=".$data["id"]."'>".
                     $data["name"]."</a></td>";

				$query_array = "SELECT *
                            FROM `glpi_displaypreferences`
                            WHERE `itemtype`='5157'
                                  AND `users_id`='0'
                            ORDER BY `rank`;";
				$result_array=$DB->query($query_array);
				while ($data_array=$DB->fetch_array($result_array)) {
					switch ($data_array['num']) {
						case 2 :
							echo "<td>".$data["ifmtu"]."</td>";
							break;

						case 3 :
							echo "<td>".$this->byteSize($data["ifspeed"],1000)."bps</td>";
							break;

						case 4 :
							echo "<td>";
							if (strstr($data["ifstatus"], "up") OR strstr($data["ifinternalstatus"],"1")) {
								echo "<img src='".GLPI_ROOT."/pics/greenbutton.png'/>";
                     } else if (strstr($data["ifstatus"],"down")
                                 OR strstr($data["ifinternalstatus"], "2")) {
								echo "<img src='".GLPI_ROOT."/pics/redbutton.png'/>";
                     } else if (strstr($data["ifstatus"], "testing")
                                 OR strstr($data["ifinternalstatus"], "3")) {
								echo "<img src='".GLPI_ROOT."/plugins/fusioninventory/pics/yellowbutton.png'/>";
                     }
							echo "</td>";
							break;

						case 5 :
							echo "<td>".$data["iflastchange"]."</td>";
							break;

						case 6 :
							echo "<td>";
							if ($data["ifinoctets"] == "0") {
								echo "-";
                     } else {
								echo $this->byteSize($data["ifinoctets"],1000)."o";
                     }
							echo "</td>";
							break;

						case 7 :
							if ($data["ifinerrors"] == "0") {
								echo "<td>-";
                     } else {
								echo "<td background='#cf9b9b' class='tab_bg_1_2'>";
								echo $data["ifinerrors"];
							}
							echo "</td>";
							break;

						case 8 :
							echo "<td>";
							if ($data["ifinoctets"] == "0") {
								echo "-";
                     } else {
								echo $this->byteSize($data["ifoutoctets"],1000)."o";
                     }
							echo "</td>";
							break;

						case 9 :
							if ($data["ifouterrors"] == "0") {
								echo "<td>-";
                     } else {
								echo "<td background='#cf9b9b' class='tab_bg_1_2'>";
								echo $data["ifouterrors"];
							}
							echo "</td>";
							break;

						case 10 :
							echo "<td>".$data["portduplex"]."</td>";
							break;

						case 11 :
							// ** internal mac
							echo "<td>".$data["mac"]."</td>";
							break;

						case 12 :
							// ** Mac address and link to device which are connected to this port
							$opposite_port = $nw->getOppositeContact($data["networkports_id"]);
							if ($opposite_port != "") {
								$query_device = "SELECT *
                                         FROM `glpi_networkports`
                                         WHERE `id`='".$opposite_port."';";

								$result_device = $DB->query($query_device);
								$data_device = $DB->fetch_assoc($result_device);

								$item = new $data_device["itemtype"];
                        $item->getFromDB($data_device["items_id"]);
								$link1 = $item->getLink(1);
								$link = str_replace($item->getName(0), $data_device["mac"],
                                            $item->getLink());
                        $link2 = str_replace($item->getName(0), $data_device["ip"],
                                             $item->getLink());
								if ($data_device["itemtype"] == 'PluginFusinvsnmpUnknownDevice') {
                           if ($item->getField("accepted") == "1") {
                              echo "<td style='background:#bfec75'
                                        class='tab_bg_1_2'>".$link1;
                           } else {
                              echo "<td background='#cf9b9b'
                                        class='tab_bg_1_2'>".$link1;
                           }
                           if (!empty($link)) {
                              echo "<br/>".$link;
                           }
                           if (!empty($link2)) {
                              echo "<br/>".$link2;
                           }
                           echo "</td>";
                        } else {
									echo "<td>".$link1;
                           if (!empty($link)) {
                              echo "<br/>".$link;
                           }
                           if (!empty($link2)) {
                              echo "<br/>".$link2;
                           }
                           echo "</td>";
                        }
							} else {
								echo "<td></td>";
							}
							break;

						case 13 :
							// ** Connection status
							echo "<td>";
							if (strstr($data["ifstatus"], "up") OR strstr($data["ifstatus"], "1")) {
								echo "<img src='".GLPI_ROOT."/pics/greenbutton.png'/>";
                     } else if (strstr($data["ifstatus"], "down")
                                OR strstr($data["ifstatus"], "2")) {
								echo "<img src='".GLPI_ROOT."/pics/redbutton.png'/>";
                     } else if (strstr($data["ifstatus"], "testing")
                                OR strstr($data["ifstatus"], "3")) {
								echo "<img src='".GLPI_ROOT."/plugins/fusioninventory/pics/yellowbutton.png'/>";
                     } else if (strstr($data["ifstatus"], "dormant")
                                OR strstr($data["ifstatus"], "5")) {
								echo "<img src='".GLPI_ROOT."/plugins/fusioninventory/pics/orangebutton.png'/>";
                     }
							echo "</td>";
							break;

						case 14 :
							echo "<td>";

                     $canedit = haveRight("networking", "w");

                     $used = array();

                     $query_vlan = "SELECT * FROM glpi_networkports_vlans WHERE ports_id='".$data["id"]."'";
                     $result_vlan = $DB->query($query_vlan);
                     if ($DB->numrows($result_vlan) > 0) {
                        echo "<table cellpadding='0' cellspacing='0'>";
                        while ($line = $DB->fetch_array($result_vlan)) {
                           $used[]=$line["vlans_id"];
                           $a_vlan = Dropdown::getDropdownName("glpi_vlans", $line["vlans_id"],1);
                           echo "<tr><td>" . $a_vlan['name']." [".$a_vlan['comment']."]";
                           echo "</td><td>";
                           if ($canedit) {
                              echo "<a href='" . $CFG_GLPI["root_doc"] . "/front/networkport.form.php?unassign_vlan=unassigned&amp;id=" . $line["id"] . "'>";
                              echo "<img src=\"" . $CFG_GLPI["root_doc"] . "/pics/delete2.png\" alt='" . $LANG['buttons'][6] . "' title='" . $LANG['buttons'][6] . "'></a>";
                           } else
                              echo "&nbsp;";
                           echo "</td></tr>";
                        }
                        echo "</table>";
                     } else {
                        echo "&nbsp;";
                     }


							echo "</td>";
							break;

						case 15 :
							//Port description
							echo "<td>".$data["ifdescr"]."</td>";
							break;
					}
				}

				echo "</tr>";


				// Historique

				echo "
				<tr style='display: none;' id='viewfollowup".$data["id"]."'>
					<td colspan='".(mysql_num_rows($result_array) + 2)."'>".
                  PluginFusinvsnmpNetworkPortLog::showHistory($data["id"])."</td>
				</tr>
				";
			}
		}
		echo "</table>";
	}

   private function byteSize($bytes,$sizeoct=1024) {
      $size = $bytes / $sizeoct;
      if ($size < $sizeoct) {
         $size = number_format($size, 0);
         $size .= ' K';
      } else {
         if ($size / $sizeoct < $sizeoct) {
            $size = number_format($size / $sizeoct, 0);
            $size .= ' M';
         } else if ($size / $sizeoct / $sizeoct < $sizeoct) {
            $size = number_format($size / $sizeoct / $sizeoct, 0);
            $size .= ' G';
         } else if ($size / $sizeoct / $sizeoct / $sizeoct < $sizeoct) {
            $size = number_format($size / $sizeoct / $sizeoct / $sizeoct, 0);
            $size .= ' T';
         }
      }
      return $size;
   }

}

?>