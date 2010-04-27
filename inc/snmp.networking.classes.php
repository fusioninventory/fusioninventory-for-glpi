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
// Original Author of file: David DURIEUX
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

class PluginFusionInventoryNetworking extends CommonDBTM {

	function __construct() {
		$this->table="glpi_plugin_fusioninventory_networking";
	}
	
	// fields of the result of a MySQL request
	var $fields;
	
	// type of the device
	var $type;
	// MySQL table of the device type
	var $table;
	// ID of the device
	var $ID;
	// ID of the device or the switch port into the table "glpi_networking_ports"
	var $networking_ports_ID;
	// IP of the device
	var $ip;
	// community for snmpget()
	var $community;
	
	// SNMP info of the device
	var $snmp;
	
	// Right variables for glpi and FusionInventory
	var $glpi_right;
	var $fusioninventory_right;

	// Init
	function plugin_fusioninventory_snmp() {
		$this->fields = array();
		$this->type = "";
		$this->table = "";
		$this->ID = -1;
		$this->networking_ports_ID = -1;
		$this->ip = "";
		$this->community = "public";
		$this->snmp = array("name" => "", "contact" => "", "location" => "", "netmask" => "");
		$this->glpi_right = "";
		$this->fusioninventory_right = "";
	}
	
	// to check if the device is working
	function isActive() {
		global $DB;
		
		$config = new PluginFusionInventoryConfig;
		
		// state number for an active device
		if (!($active_device_state = $config->getValue("active_device_state"))) {
			return false;
      }
			
		// compare device status and active device status
		$query = "SELECT `state`
                FROM $this->table
                WHERE `ID`='".$this->ID."';";
		if ($result = $DB->query($query)) {
			if ($fields = $DB->fetch_row($result)) {
				if (($fields['0']) == $active_device_state) {
					return true;
            }
			}
		}
		return false;
	}
	
	
	/* to get an object from a snmpget result, without prefix like "STRING: " for instance */
	function snmpgetObject($object_id) {
		$result[0] = @snmpget($this->ip, $this->community, $object_id);
		if ($result[0] != false ) {
			$pos = strpos($result[0], " ");
			$result[1] = substr($result[0], $pos+1);
			// if "" into the string
			$result[2] = str_replace('"', '', $result[1]);
			return $result[2];
		} else {
			return false;
      }
	}
	
	function getName() {
		if (!($this->snmp['name'] = $this->snmpgetObject(MIB_NAME))) {
			$this->snmp['name'] = "";
			return false;
		}
		return $this->snmp['name'];
	}
	
	function getContact() {
		if (!($this->snmp['contact'] = $this->snmpgetObject(MIB_CONTACT))) {
			$this->snmp['contact'] = "";
			return false;
		}
		return $this->snmp['contact'];
	}
	
	function getLocation() {
		if (!($this->snmp['location'] = $this->snmpgetObject(MIB_LOCATION))) {
			$this->snmp['location'] = "";
			return false;
		}
		return $this->snmp['location'];
	}
	
	function getNetmask() {
		$mib_netmask = MIB_NETMASK_PREFIX.".".$this->ip;
		if (!($this->snmp['netmask'] = $this->snmpgetObject($mib_netmask))) {
			$this->snmp['netmask'] = "";
			return false;
		}
		return $this->snmp['netmask'];
	}
	
	
	function showForm($target,$ID) {
		
		global $DB,$CFG_GLPI,$LANG;	
		
		$history = new PluginFusionInventorySNMPHistory;
		
		if (!PluginFusioninventory::haveRight("snmp_networking","r")) {
			return false;
      }
		if (PluginFusioninventory::haveRight("snmp_networking","w")) {
			$canedit = true;
      } else {
			$canedit = false;
      }
		include (GLPI_ROOT . "/plugins/fusioninventory/inc_constants/plugin_fusioninventory.snmp.mapping.constant.php");
		
		$this->ID = $ID;
		
		$nw=new Netwire;
		$CommonItem = new CommonItem;
		$plugin_fusioninventory_snmp = new PluginFusionInventorySNMP;

		echo "<script type='text/javascript' src='".GLPI_ROOT.
               "/lib/extjs/adapter/prototype/prototype.js'></script>";
		echo "<script type='text/javascript' src='".GLPI_ROOT.
               "/lib/extjs/adapter/prototype/effects.js'></script>";
		
      if (!$data = $this->find("`FK_networking`='".$ID."'", '', 1)) {
         // Add in database if not exist
         $input['FK_networking'] = $ID;
         $ID_tn = $this->add($input);
         $this->getFromDB($ID_tn);
      } else {
         foreach ($data as $ID_tn=>$datas) {
            $this->fields = $data[$ID_tn];
         }
      }

      $PID = $this->fields['last_PID_update'];

		// Form networking informations
		echo "<div align='center'>
            <form method='post' name='snmp_form' id='snmp_form' action=\"".$target."\">";

		echo "<table class='tab_cadre' cellpadding='5' width='950'>";
		
		echo "<tr class='tab_bg_1'>";
		echo "<th colspan='3'>";
		echo $LANG['plugin_fusioninventory']["snmp"][11];
		echo "</th>";
		echo "</tr>";
		
		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>".$LANG['plugin_fusioninventory']["profile"][24]."</td>";
		echo "<td align='center'>";
		$query_models = "SELECT * 
                       FROM `glpi_plugin_fusioninventory_model_infos`
                       WHERE `device_type`!='2'
                             AND `device_type`!='0';";
		$result_models=$DB->query($query_models);
		$exclude_models = array();
		while ($data_models=$DB->fetch_array($result_models)) {
			$exclude_models[] = $data_models['ID'];		
		}
      dropdownValue("glpi_plugin_fusioninventory_model_infos","model_infos",$this->fields['FK_model_infos'],0,-1,'',
                    $exclude_models);
      echo "</td>";
		echo "</tr>";
		
		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>".$LANG['plugin_fusioninventory']["functionalities"][43]."</td>";
		echo "<td align='center'>";
		PluginFusioninventorySNMP::auth_dropdown($this->fields['FK_snmp_connection']);
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1 center'>";
      echo "<td>";
      echo " <input type='submit' name='GetRightModel'
              value='".$LANG['plugin_fusioninventory']["model_info"][13]."' class='submit'/></td>";
		echo "<td>";
		echo "<input type='hidden' name='ID' value='".$ID."'>";
		echo "<input type='submit' name='update' value=\"".$LANG["buttons"][7]."\" class='submit' >";
		echo "</td>";
		echo "</tr>";

		echo "</table></form>";

      // Remote action of agent
      $pfit = new PluginFusionInventoryTask;
      $pfit->RemoteStateAgent($target, $ID, NETWORKING_TYPE, array('INVENTORY' => 1 ));


      // SNMP Informations
//		echo "<div align='center'>
      echo "<form method='post' name='snmp_form' id='snmp_form'  action=\"".$target."\">";

		echo "<table class='tab_cadre' cellpadding='5' width='950'>";

		echo "<tr class='tab_bg_1'>";
		echo "<th colspan='3'>";
		echo $LANG['plugin_fusioninventory']["title"][1];
		echo "</th>";
		echo "</tr>";

		echo "<tr class='tab_bg_1 center'>";
		echo "<td colspan='2' height='30'>";
		echo $LANG['plugin_fusioninventory']["snmp"][52].": ".convDateTime($this->fields['last_fusioninventory_update']);
		echo "</td>";
		echo "</tr>";

		// Get link field to detect if cpu, memory and uptime are get onthis network device
		$Array_Object_TypeNameConstant =
                    $plugin_fusioninventory_snmp->GetLinkOidToFields($ID,NETWORKING_TYPE);
		$mapping_name=array();
		foreach ($Array_Object_TypeNameConstant as $object=>$mapping_type_name) {
			$mapping_name[$mapping_type_name] = "1";
		}

		if ((isset($mapping_name['uptime']))  AND ($mapping_name['uptime'] == "1")) {

			echo "<tr class='tab_bg_1 center'>";
			echo "<td>".$LANG['plugin_fusioninventory']["snmp"][12]."</td>";
			echo "<td>";
			$sysUpTime = $this->fields['uptime'];
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
		}
		
		if (((isset($mapping_name['cpu']))  AND ($mapping_name['cpu'] == "1"))
			OR (((isset($mapping_name['cpuuser']))  AND ($mapping_name['cpuuser'] == "1"))
				AND ((isset($mapping_name['cpusystem']))  AND ($mapping_name['cpusystem'] == "1"))
			)) {
            
			echo "<tr class='tab_bg_1 center'>";
			echo "<td>".$LANG['plugin_fusioninventory']["snmp"][13]."</td>";
			echo "<td>";
			PluginFusioninventoryDisplay::bar($this->fields['cpu'],'','inverse');
			echo "</td>";
			echo "</tr>";
		}

		if ((isset($mapping_name['memory']))  AND ($mapping_name['memory'] == "1")) {
			echo "<tr class='tab_bg_1 center'>";
			echo "<td>".$LANG['plugin_fusioninventory']["snmp"][14]."</td>";
			echo "<td>";
			$query2 = "SELECT *
                    FROM `glpi_networking`
                    WHERE `ID`='".$ID."';";
			$result2 = $DB->query($query2);
			$data2 = $DB->fetch_assoc($result2);

			if (empty($data2["ram"])) {
				$ram_pourcentage = 0;
			} else {
				$ram_pourcentage = ceil((100 * ($data2["ram"] - $this->fields['memory'])) / $data2["ram"]);
			}
			PluginFusioninventoryDisplay::bar($ram_pourcentage," (".($data2["ram"] - $this->fields['memory'])." Mo / ".
                            $data2["ram"]." Mo)",'inverse');
			echo "</td>";
			echo "</tr>";
		}

		echo "</table></form>";
		
		
// ********************************************************************************************** //
// *********************************** METTRE TABLEAU DES PORTS ********************************* //
// ********************************************************************************************** //	
		function ByteSize($bytes,$sizeoct=1024) {
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
		
		
		$query = "
		SELECT *,glpi_plugin_fusioninventory_networking_ports.ifmac as ifmacinternal
		
		FROM glpi_plugin_fusioninventory_networking_ports

		LEFT JOIN glpi_networking_ports
		ON glpi_plugin_fusioninventory_networking_ports.FK_networking_ports = glpi_networking_ports.ID 
		WHERE glpi_networking_ports.on_device='".$ID."'
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
                      FROM `glpi_display`
                      WHERE `type`='".PLUGIN_FUSIONINVENTORY_SNMP_NETWORKING_PORTS."'
                            AND `FK_users`='0'
                      ORDER BY `rank`;";
		$result_array=$DB->query($query_array);
		echo "<th colspan='".(mysql_num_rows($result_array) + 2)."'>";
		echo $LANG['plugin_fusioninventory']["snmp"][40];
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
		echo '<th><img alt="Sélectionnez les éléments à afficher par défaut" 
                     title="Sélectionnez les éléments à afficher par défaut"
                     src="'.GLPI_ROOT.'/pics/options_search.png" class="pointer"
                     onclick="var w = window.open(\''.GLPI_ROOT.
                        '/front/popup.php?popup=search_config&type=5157\' ,\'glpipopup\',
                        \'height=400,
                     width=1000, top=100, left=100, scrollbars=yes\' ); w.focus();"></th>';
		echo "<th>".$LANG["common"][16]."</th>";

		$query_array = "SELECT * 
                      FROM `glpi_display`
                      WHERE `type`='5157'
                             AND `FK_users`='0'
                      ORDER BY `rank`;";
		$result_array=$DB->query($query_array);
		while ($data_array=$DB->fetch_array($result_array)) {
			echo "<th>";
			switch ($data_array['num']) {
				case 2 :
					echo $LANG['plugin_fusioninventory']["snmp"][42];
					break;

				case 3 :
					echo $LANG['plugin_fusioninventory']["snmp"][43];
					break;

				case 4 :
					echo $LANG['plugin_fusioninventory']["snmp"][44];
					break;

				case 5 :
					echo $LANG['plugin_fusioninventory']["snmp"][45];
					break;

				case 6 :
					echo $LANG['plugin_fusioninventory']["snmp"][46];
					break;

				case 7 :
					echo $LANG['plugin_fusioninventory']["snmp"][47];
					break;

				case 8 : 
					echo $LANG['plugin_fusioninventory']["snmp"][48];
					break;

				case 9 : 
					echo $LANG['plugin_fusioninventory']["snmp"][49];
					break;

				case 10 : 
					echo $LANG['plugin_fusioninventory']["snmp"][51];
					break;

				case 11 : 
					echo $LANG['plugin_fusioninventory']["mapping"][115];
					break;

				case 12 :
					echo $LANG["networking"][17];
					break;

				case 13 :
					echo $LANG['plugin_fusioninventory']["snmp"][50];
					break;

				case 14 :
					echo $LANG["networking"][56];
					break;
        
            case 15 :
					echo $LANG['plugin_fusioninventory']["snmp"][41];
					break;
					
			}
			echo "</th>";
		}			
		echo "</tr>";
		// Fin de l'entête du tableau
		
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
				echo "<td id='plusmoins".$data["ID"]."'><img src='".GLPI_ROOT.
                     "/pics/expand.gif' onClick='Effect.Appear(\"viewfollowup".$data["ID"].
                     "\");close_array(".$data["ID"].");' /></td>";
				echo "<td><a href='networking.port.php?ID=".$data["ID"]."'>".
                     $data["name"]."</a></td>";
				
				$query_array = "SELECT *
                            FROM `glpi_display`
                            WHERE `type`='5157'
                                  AND `FK_users`='0'
                            ORDER BY `rank`;";
				$result_array=$DB->query($query_array);
				while ($data_array=$DB->fetch_array($result_array)) {
					switch ($data_array['num']) {
						case 2 :
							echo "<td>".$data["ifmtu"]."</td>";
							break;

						case 3 :
							echo "<td>".ByteSize($data["ifspeed"],1000)."bps</td>";
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
								echo ByteSize($data["ifinoctets"],1000)."o";
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
								echo ByteSize($data["ifoutoctets"],1000)."o";
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
							echo "<td>".$data["ifmac"]."</td>";
							break;

						case 12 :
							// ** Mac address and link to device which are connected to this port
							$opposite_port = $nw->getOppositeContact($data["FK_networking_ports"]);
							if ($opposite_port != "") {
								$query_device = "SELECT * 
                                         FROM `glpi_networking_ports`
                                         WHERE `ID`='".$opposite_port."';";
				
								$result_device = $DB->query($query_device);		
								$data_device = $DB->fetch_assoc($result_device);				
								
								$CommonItem->getFromDB($data_device["device_type"],
                                               $data_device["on_device"]);
								$link1 = $CommonItem->getLink(1);
								$link = str_replace($CommonItem->getName(0), $data_device["ifmac"],
                                            $CommonItem->getLink());
                        $link2 = str_replace($CommonItem->getName(0), $data_device["ifaddr"],
                                             $CommonItem->getLink());
								if ($data_device["device_type"] == PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN) {
                           if ($CommonItem->getField("accepted") == "1") {
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

                     $query_vlan = "SELECT * FROM glpi_networking_vlan WHERE FK_port='".$data["ID"]."'";
                     $result_vlan = $DB->query($query_vlan);
                     if ($DB->numrows($result_vlan) > 0) {
                        echo "<table cellpadding='0' cellspacing='0'>";
                        while ($line = $DB->fetch_array($result_vlan)) {
                           $used[]=$line["FK_vlan"];
                           $a_vlan = Dropdown::getDropdownName("glpi_dropdown_vlan", $line["FK_vlan"],1);
                           echo "<tr><td>" . $a_vlan['name']." [".$a_vlan['comments']."]";
                           echo "</td><td>";
                           if ($canedit) {
                              echo "<a href='" . $CFG_GLPI["root_doc"] . "/front/networking.port.php?unassign_vlan=unassigned&amp;ID=" . $line["ID"] . "'>";
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
				<tr style='display: none;' id='viewfollowup".$data["ID"]."'>
					<td colspan='".(mysql_num_rows($result_array) + 2)."'>".
                  PluginFusionInventorySNMPHistory::showHistory($data["ID"])."</td>
				</tr>
				";
			}
		}
		echo "</table>";
	}
	
	

	/* Useful to get the ID of a device into the table "glpi_networking_ports */
	function getNetworkingPortsIDfromID() {
		global $DB;
		$query = "SELECT `ID`
                FROM `glpi_networking_ports`
                WHERE `on_device`='".$this->ID."'
                      AND `device_type`='".$this->type."';";
		if ($result=$DB->query($query)) {
			$this->fields = $DB->fetch_row($result);
			// check if IP is in db
			if (($this->fields['0']) != NULL) {
				$this->networking_ports_ID = $this->fields['0'];
				return $this->networking_ports_ID;
			} else {
				return false;
         }
		}
	}
	
	function getIDfromNetworkingPortsID() {
		global $DB;
		$query = "SELECT `on_device`
                FROM `glpi_networking_ports`
                WHERE `ID`='".$this->networking_ports_ID."'
                AND `device_type`='".$this->type."';";
		if ($result=$DB->query($query)) {
			$this->fields = $DB->fetch_row($result);
			// check if IP is in db
			if (($this->fields['0']) != NULL) {
				$this->ID = $this->fields['0'];
				return $this->ID;
			} else {
				return false;
         }
		}
	}
}

?>