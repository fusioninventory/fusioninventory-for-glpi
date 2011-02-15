<?php

/*
 * @version $Id$
 ----------------------------------------------------------------------
 FusionInventory
 Coded by the FusionInventory Development Team.

 http://www.fusioninventory.org/   http://forge.fusioninventory.org//
 ----------------------------------------------------------------------

 LICENSE

 This file is part of FusionInventory plugins.

 FusionInventory is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 FusionInventory is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with FusionInventory; if not, write to the Free Software
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

class PluginFusinvsnmpConfigSecurity extends CommonDBTM {
   

	function showForm($id, $options=array()) {
		global $DB,$CFG_GLPI,$LANG;

		PluginFusioninventoryProfile::checkRight("fusinvsnmp", "configsecurity","r");

		if ($id!='') {
			$this->getFromDB($id);
      } else {
			$this->getEmpty();	
      }
		$this->showTabs($options);
      $options['colspan']=1;
      $this->showFormHeader($options);

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANG["common"][16] . "</td>";
		echo "<td align='center'>";
		echo "<input type='text' name='name' value='" . $this->fields["name"] . "'/>";
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANG['plugin_fusinvsnmp']['model_info'][2] . "</td>";
		echo "<td align='center'>";
         $this->showDropdownSNMPVersion($this->fields["snmpversion"]);
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANG['plugin_fusinvsnmp']['snmpauth'][1] . "</td>";
		echo "<td align='center'>";
		echo "<input type='text' name='community' value='" . $this->fields["community"] . "'/>";
		echo "</td>";
		echo "</tr>";
		
		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANG['plugin_fusinvsnmp']['snmpauth'][2] . "</td>";
		echo "<td align='center'>";
		echo "<input type='text' name='username' value='" . $this->fields["username"] . "'/>";
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANG['plugin_fusinvsnmp']['snmpauth'][4] . "</td>";
		echo "<td align='center'>";
         $this->showDropdownSNMPAuth($this->fields["authentication"]);
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANG['plugin_fusinvsnmp']['snmpauth'][5] . "</td>";
		echo "<td align='center'>";
		echo "<input type='text' name='auth_passphrase'
                   value='".$this->fields["auth_passphrase"]."'/>";
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANG['plugin_fusinvsnmp']['snmpauth'][6] . "</td>";
		echo "<td align='center'>";
         $this->showDropdownSNMPEncryption($this->fields["encryption"]);
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANG['plugin_fusinvsnmp']['snmpauth'][7] . "</td>";
		echo "<td align='center'>";
		echo "<input type='text' name='priv_passphrase'
                   value='" . $this->fields["priv_passphrase"] . "'/>";
		echo "</td>";
		echo "</tr>";

      $options['colspan']=1;
      $this->showFormButtons($options);

      echo "<div id='tabcontent'></div>";
      echo "<script type='text/javascript'>loadDefaultTab();</script>";

      return true;
	}
	
	
	
	function plugin_fusioninventory_snmp_connections($array=0) {
		global $CFG_GLPI,$LANG;

		$array_auth = array();

		if ($array == '0') {
			echo "<div align='center'><table class='tab_cadre_fixe'>";
			echo "<tr><th colspan='10'>".$LANG['plugin_fusioninventory']['model_info'][3]." :</th></tr>";
			echo "<tr><th>".$LANG["common"][2]."</th>";
			echo "<th>".$LANG["common"][16]."</th>";
			echo "<th>".$LANG['plugin_fusioninventory']['model_info'][2]."</th>";
			echo "<th>".$LANG['plugin_fusioninventory']['snmpauth'][1]."</th>";
			echo "<th>".$LANG['plugin_fusioninventory']['snmpauth'][2]."</th>";
			echo "<th>".$LANG['plugin_fusioninventory']['snmpauth'][3]."</th>";
			echo "<th>".$LANG['plugin_fusioninventory']['snmpauth'][4]."</th>";
			echo "<th>".$LANG['plugin_fusioninventory']['snmpauth'][5]."</th>";
			echo "<th>".$LANG['plugin_fusioninventory']['snmpauth'][6]."</th>";
			echo "<th>".$LANG['plugin_fusioninventory']['snmpauth'][7]."</th>";
			echo "</tr>";
		}

		$xml = simplexml_load_file(GLPI_ROOT."/plugins/fusioninventory/scripts/auth.xml",'SimpleXMLElement', LIBXML_NOCDATA);
		
		$i = -1;
		foreach($xml->auth[0] as $num) {
			$i++;
			$j = 0;
			foreach($xml->auth->conf[$i] as $item) {
				$j++;
				switch ($j) {
					case 1:
						$numero[$i] = $item;
						break;

					case 2:
						$name[$i] = $item;
						break;

					case 3:
						$snmp_version[$i] = Dropdown::getDropdownName(
                                      "glpi_plugin_fusioninventory_snmpversions",$item);
						if ($snmp_version[$i] == "&nbsp;") {
							$snmp_version[$i] = "";
                  }
						break;

					case 4:
						$community[$i] = $item;
						break;

					case 5:
						$username[$i] = $item;
						break;

					case 7:
						$authentication[$i] = Dropdown::getDropdownName(
                                    "glpi_plugin_fusioninventory_snmpprotocolauths",$item);
						if ($authentication[$i] == "&nbsp;") {
							$authentication[$i] = "";
                  }
						break;

					case 8:
						$auth_passphrase[$i] = $item;
						break;

					case 9:
						$encryption[$i] = Dropdown::getDropdownName(
                                    "glpi_plugin_fusioninventory_snmpprotocolprivs",$item);
						if ($encryption[$i] == "&nbsp;") {
							$encryption[$i] = "";
                  }
						break;

					case 10:
						$priv_passphrase[$i] = $item;
						break;
				}
			}
		}
      
		foreach ($numero AS $key=>$numero) {
			if ($array == '0') {
				echo "<tr class='tab_bg_1'>";
				echo "<td align='center'>".$numero."</td>";
				echo "<td align='center'>".$name[$key]."</td>";
				echo "<td align='center'>".$snmp_version[$key]."</td>";
				echo "<td align='center'>".$community[$key]."</td>";
				echo "<td align='center'>".$username[$key]."</td>";
				echo "<td align='center'>".$authentication[$key]."</td>";
				echo "<td align='center'>".$auth_passphrase[$key]."</td>";
				echo "<td align='center'>".$encryption[$key]."</td>";
				echo "<td align='center'>".$priv_passphrase[$key]."</td>";
				echo "</tr>";
			} else if ($array == '1') {
				$array_auth["$numero"]['IDC'] = $numero;
				$array_auth["$numero"]['name']= $name[$key];
				$array_auth["$numero"]['namec']=$snmp_version[$key];
				$array_auth["$numero"]['community']=$community[$key];
				$array_auth["$numero"]['username']=$username[$key];
				$array_auth["$numero"]['authentication']=$authentication[$key];
				$array_auth["$numero"]['auth_passphrase']=$auth_passphrase[$key];
				$array_auth["$numero"]['encryption']=$encryption[$key];
				$array_auth["$numero"]['priv_passphrase']=$priv_passphrase[$key];
			}
		}
		if ($array == '0') {
			echo "</table></div>";
		} else if ($array == '1') {
			return $array_auth;
		}
	}
	

	// for file stored snmp authentication
	function add_xml() {
		// Get new id
		$xml = simplexml_load_file(GLPI_ROOT."/plugins/fusioninventory/scripts/auth.xml",'SimpleXMLElement', LIBXML_NOCDATA);
		
		$id = $xml->incrementID[0];
		$id = $id + 1;

		// Write XML file
		$xml_write = "<snmp>\n";
		$xml_write .= "	<incrementID>".$id."</incrementID>\n";
		$xml_write .= "	<auth>\n";
		$i = -1;
		foreach($xml->auth[0] as $num) {
			$i++;
			$xml_write .= "		<conf>\n";
			$j = 0;
			foreach($xml->auth->conf[$i] as $item) {
				$j++;
				switch ($j) {
					case 1:
						$xml_write .= "			<Num>".$item."</Num>\n";
						break;

					case 2:
						$xml_write .= "			<Name><![CDATA[".$item."]]></Name>\n";
						break;

					case 3:
						$xml_write .= "			<snmp_version>".$item."</snmp_version>\n";
						break;

					case 4:
						$xml_write .= "			<community><![CDATA[".$item."]]></community>\n";
						break;

					case 5:
						$xml_write .= "			<sec_name><![CDATA[".$item."]]></sec_name>\n";
						break;

					case 7:
						$xml_write .= "			<auth_protocol>".$item."</auth_protocol>\n";
						break;

					case 8:
						$xml_write .= "			<auth_passphrase><![CDATA[".$item.
                                "]]></auth_passphrase>\n";
						break;

					case 9:
						$xml_write .= "			<priv_protocol>".$item."</encryption>\n";
						break;

					case 10:
						$xml_write .= "			<priv_passphrase><![CDATA[".$item.
                                "]]></priv_passphrase>\n";
						break;
				}
			}
			$xml_write .= "		</conf>\n";
		}
		// Write new Line
		$xml_write .= "		<conf>\n";
		$xml_write .= "			<Num>".$id."</Num>\n";
		$xml_write .= "			<Name><![CDATA[".$_POST["name"]."]]></Name>\n";
		$xml_write .= "			<snmp_version>".$_POST["plugin_fusioninventory_snmpversions_id"]."</snmp_version>\n";
		$xml_write .= "			<community><![CDATA[".$_POST["community"]."]]></community>\n";
		$xml_write .= "			<sec_name><![CDATA[".$_POST["username"]."]]></sec_name>\n";
		$xml_write .= "			<auth_protocol>".$_POST["authentication"]."</auth_protocol>\n";
		$xml_write .= "			<auth_passphrase><![CDATA[".$_POST["auth_passphrase"].
                    "]]></auth_passphrase>\n";
		$xml_write .= "			<priv_protocol>".$_POST["encryption"]."</priv_protocol>\n";
		$xml_write .= "			<priv_passphrase><![CDATA[".$_POST["priv_passphrase"].
                    "]]></priv_passphrase>\n";
		$xml_write .= "		</conf>\n";
		
		$xml_write .= "	</auth>\n";
		$xml_write .= "</snmp>\n";
		
		$myFile = GLPI_ROOT."/plugins/fusioninventory/scripts/auth.xml";
		$fh = fopen($myFile, 'w') or die("can't open file");
		fwrite($fh, $xml_write);
		fclose($fh);
		
		return $id;
	}
	
	function showDropdownSNMPVersion($p_value=NULL) {
      $snmpVersions = array(0=>'-----', '1', '2c', '3');
      if (is_null($p_value)) {
         $options = array();
      } else {
         $options = array('value'=>$p_value);
      }
      Dropdown::showFromArray("snmpversion", $snmpVersions, $options);
   }

   function getSNMPVersion($id) {
      switch($id) {

         case '1':
            return '1';
            break;

         case '2':
            return '2c';
            break;

         case '3':
            return '3';
            break;

      }
      return '';
   }

	function showDropdownSNMPAuth($p_value=NULL) {
      $authentications = array(0=>'-----', 'MD5', 'SHA');
      if (is_null($p_value)) {
         $options = array();
      } else {
         $options = array('value'=>$p_value);
      }
      Dropdown::showFromArray("authentication", $authentications, $options);
   }

   function getSNMPAuthProtocol($id) {
      switch($id) {

         case '1':
            return 'MD5';
            break;

         case '2':
            return 'SHA';
            break;

      }
      return '';
   }

	function showDropdownSNMPEncryption($p_value=NULL) {
      $encryptions = array(0=>'-----', 'DES', 'AES128', 'AES192', 'AES256');
      if (is_null($p_value)) {
         $options = array();
      } else {
         $options = array('value'=>$p_value);
      }
      Dropdown::showFromArray("encryption", $encryptions, $options);
   }

   function getSNMPEncryption($id) {
      switch($id) {

         case '1':
            return 'DES';
            break;

         case '2':
            return 'AES128';
            break;

         case '3':
            return 'AES192';
            break;

         case '4':
            return 'AES256';
            break;

      }
      return '';
   }

	function selectbox($selected=0) {
		$xml = simplexml_load_file(GLPI_ROOT."/plugins/fusioninventory/scripts/auth.xml",'SimpleXMLElement', LIBXML_NOCDATA);
		$i = -1;
		$selectbox = "<select name='plugin_fusinvsnmp_configsecurities_id' size='1'>\n
                       <option value='0'>-----</option>\n";
		foreach($xml->auth[0] as $num) {
			$i++;

			$j = 0;
			foreach($xml->auth->conf[$i] as $item) {
				$j++;
				switch ($j) {
					case 1:
						if ($item == $selected) {
							$selectbox .= "<option selected='selected' value='".$item."'>";
                  } else {
							$selectbox .= "<option value='".$item."'>";
                  }
						break;

					case 2:
						$selectbox .= $item."</option>\n";
                  break;
				}
			}
		}
		$selectbox .= "</select>\n";
		
		return $selectbox;
	}



	/**
	 * Get SNMP version and authentification 
	 *
	 * @param $ID_Device id of the device ("all" if we want to get all snmp auth)
	 * @param $xml_auth_rep folder where as stocked authxml file (if the management is by FILE)
	 * @param $type type of device (NETWORKING_TYPE, PRINTER_TYPE ...)
	 *
	 * @return $snmp_auth : array with auth informations && version
	 *
	**/
	function GetInfos($ID_Device,$xml_auth_rep,$type) {
		global $DB,$CFG_GLPI,$LANG;

		$config = new PluginFusioninventoryConfig;

		if ($ID_Device != "all") {
			switch ($type) {
				case NETWORKING_TYPE :
					$query = "SELECT *
                         FROM `glpi_plugin_fusinvsnmp_networkequipments`
                         WHERE `networkequipments_id`='".$ID_Device."';";
					break;

				case PRINTER_TYPE :
					$query = "SELECT *
                         FROM `glpi_plugin_fusinvsnmp_printers`
                         WHERE `printers_id`='".$ID_Device."';";
					break;
			}		
			$result=$DB->query($query);
			if ($DB->numrows($result) > 0) {
				$ID_auth = $DB->result($result,0,"plugin_fusinvsnmp_configsecurities_id");
         } else {
				return;
         }
		} else {
			// Put Default community of devices
			$snmp_auth[0]["Name"] = "Public-v2c";
			$snmp_auth[0]["snmp_version"] = "2c";
			$snmp_auth[0]["community"] = "public";
			$snmp_auth[0]["username"] = "";
			$snmp_auth[0]["authentication"] = "";
			$snmp_auth[0]["auth_passphrase"] = "";
			$snmp_auth[0]["encryption"] = "";
			$snmp_auth[0]["priv_passphrase"] = "";			
			$snmp_auth[0]["id"] = 0;
			$snmp_auth[1]["Name"] = "Public-v1";
			$snmp_auth[1]["snmp_version"] = "1";
			$snmp_auth[1]["community"] = "public";
			$snmp_auth[1]["username"] = "";
			$snmp_auth[1]["authentication"] = "";
			$snmp_auth[1]["auth_passphrase"] = "";
			$snmp_auth[1]["encryption"] = "";
			$snmp_auth[1]["priv_passphrase"] = "";		
			$snmp_auth[1]["id"] = 0;
		}

		if ($config->getValue("storagesnmpauth") == "file") {
			$xml = simplexml_load_file($xml_auth_rep."auth.xml",'SimpleXMLElement', LIBXML_NOCDATA);
		
			$i=-1;
			foreach($xml->auth[0] as $num) {
				$i++;
				$j = 0;
				$recup = 0;
				foreach($xml->auth->conf[$i] as $item) {
					$j++;
					switch ($j) {
						case 1:
							if ($ID_Device == "all") {
								$recup = 1;
								$snmp_auth[($i+2)]["id"] = $item;
							} else if ($item == $ID_auth) {
								$recup = 1;
                     }
							break;

						case 2:
							if (($recup == "1") AND ($ID_Device != "all")) {
								$snmp_auth["Name"] = $item;
                     }
							if ($ID_Device == "all") {
								$snmp_auth[($i+2)]["Name"] = $item;
                     }
							break;

						case 3:
							if (($recup == "1") AND ($ID_Device != "all")) {
								$snmp_auth["snmp_version"] = Dropdown::getDropdownName(
                              "glpi_plugin_fusioninventory_snmpversions",$item);
                     }
							if ($ID_Device == "all") {
								$snmp_auth[($i+2)]["snmp_version"] = Dropdown::getDropdownName(
                              "glpi_plugin_fusioninventory_snmpversions",$item);
                     }
							break;

						case 4:
							if (($recup == "1") AND ($ID_Device != "all")) {
								$snmp_auth["community"] = $item;
                     }
							if ($ID_Device == "all") {
								$snmp_auth[($i+2)]["community"] = $item;
                     }
							break;

						case 5:
							if (($recup == "1") AND ($ID_Device != "all")) {
								$snmp_auth["username"] = $item;
                     }
							if ($ID_Device == "all") {
								$snmp_auth[($i+2)]["username"] = $item;
                     }
							break;

						case 7:
							if (($recup == "1") AND ($ID_Device != "all")) {
								$snmp_auth["authentication"] = Dropdown::getDropdownName(
                              "glpi_plugin_fusioninventory_snmpprotocolauths",$item);
                     }
							if ($ID_Device == "all") {
								$snmp_auth[($i+2)]["authentication"] = Dropdown::getDropdownName(
                              "glpi_plugin_fusioninventory_snmpprotocolauths",$item);
                     }
							break;

                  case 8:
							if (($recup == "1") AND ($ID_Device != "all")) {
								$snmp_auth["auth_passphrase"] = $item;
                     }
							if ($ID_Device == "all") {
								$snmp_auth[($i+2)]["auth_passphrase"] = $item;
                     }
							break;
                  
						case 9:
							if (($recup == "1") AND ($ID_Device != "all")) {
								$snmp_auth["encryption"] = Dropdown::getDropdownName(
                              "glpi_plugin_fusioninventory_snmpprotocolprivs",$item);
                     }
							if ($ID_Device == "all") {
									$snmp_auth[($i+2)]["encryption"] = Dropdown::getDropdownName(
                              "glpi_plugin_fusioninventory_snmpprotocolprivs",$item);
                     }
							break;

						case 10:
							if (($recup == "1") AND ($ID_Device != "all")) {
                        $snmp_auth["priv_passphrase"] = $item;
                     }
							if ($ID_Device == "all") {
									$snmp_auth[($i+2)]["priv_passphrase"] = $item;
                     }
							break;
					}
				}
			}	
		} else if ($config->getValue("storagesnmpauth") == "DB") {
			if ($ID_Device == "all") {
				$query = "SELECT *
                      FROM `glpi_plugin_fusinvsnmp_configsecurities`";
         } else {
				$query = "SELECT *
                      FROM `glpi_plugin_fusinvsnmp_configsecurities`
                      WHERE `id`='".$ID_auth."';";
			}
			$result=$DB->query($query);
			if (($DB->numrows($result) == "0") AND ($ID_Device != "all")) {
				$snmp_auth["Name"] = "";
				$snmp_auth["snmp_version"] = "";
				$snmp_auth["community"] = "";
				$snmp_auth["username"] = "";
				$snmp_auth["authentication"] = "";
				$snmp_auth["auth_passphrase"] = "";
				$snmp_auth["encryption"] = "";
				$snmp_auth["priv_passphrase"] = "";
			} else if ($ID_Device != "all") {
				$snmp_auth["Name"] = $DB->result($result,0,"name");
				$snmp_auth["snmp_version"] = Dropdown::getDropdownName(
               "glpi_plugin_fusioninventory_snmpversions",$DB->result($result,0,
               "plugin_fusioninventory_snmpversions_id"));
				$snmp_auth["community"] = $DB->result($result,0,"community");
				$snmp_auth["username"] = $DB->result($result,0,"username");
				$snmp_auth["authentication"] = Dropdown::getDropdownName(
               "glpi_plugin_fusioninventory_snmpprotocolauths",$DB->result($result,0,
               "authentication"));
				$snmp_auth["auth_passphrase"] = $DB->result($result,0,"auth_passphrase");
				$snmp_auth["encryption"] = Dropdown::getDropdownName(
               "glpi_plugin_fusioninventory_snmpprotocolprivs",$DB->result($result,0,
               "encryption"));
				$snmp_auth["priv_passphrase"] = $DB->result($result,0,"priv_passphrase");
			} else if ($ID_Device == "all") {
				$i = 2;
				while ($data=$DB->fetch_array($result)) {
					if (($snmp_auth[0]["snmp_version"] == Dropdown::getDropdownName(
                        "glpi_plugin_fusioninventory_snmpversions",$data["plugin_fusioninventory_snmpversions_id"]))
                  AND ($snmp_auth[0]["community"] == $data["community"])) {
                  
						$snmp_auth[0]["id"] = $data["id"];
                } else if (($snmp_auth[1]["snmp_version"] == Dropdown::getDropdownName(
                         "glpi_plugin_fusioninventory_snmpversions",$data["plugin_fusioninventory_snmpversions_id"]))
                  AND ($snmp_auth[1]["community"] == $data["community"])) {

						$snmp_auth[1]["id"] = $data["id"];
               } else {
						$snmp_auth[$i]["id"] = $data["id"];
						$snmp_auth[$i]["Name"] = $data["name"];
						$snmp_auth[$i]["snmp_version"] = Dropdown::getDropdownName(
                     "glpi_plugin_fusioninventory_snmpversions",$data["plugin_fusioninventory_snmpversions_id"]);
						$snmp_auth[$i]["community"] = $data["community"];
						$snmp_auth[$i]["username"] = $data["username"];
						$snmp_auth[$i]["authentication"] = Dropdown::getDropdownName(
                     "glpi_plugin_fusioninventory_snmpprotocolauths",$data["authentication"]);
						$snmp_auth[$i]["auth_passphrase"] = $data["auth_passphrase"];
						$snmp_auth[$i]["encryption"] = Dropdown::getDropdownName(
                     "glpi_plugin_fusioninventory_snmpprotocolprivs",$data["encryption"]);
						$snmp_auth[$i]["priv_passphrase"] = $data["priv_passphrase"];
						$i++;
					}
				}
			}
		}
		return $snmp_auth;
	}



	function GetSNMPAuthDevice($ID_Device,$type) {
		global $DB;

		switch ($type) {
			case 'NetworkEquipment':
				$query = "SELECT plugin_fusinvsnmp_configsecurities_id
				FROM glpi_plugin_fusinvsnmp_networkequipments 
				WHERE networkequipments_id='".$ID_Device."' ";
				break;

			case 'Printer':
				$query = "SELECT `plugin_fusinvsnmp_configsecurities_id`
                      FROM `glpi_plugin_fusinvsnmp_printers`
                      WHERE `printers_id`='".$ID_Device."';";
				break;
		}
		
		if ((isset($query)) && ($result = $DB->query($query))) {
			if ($DB->numrows($result) != 0) {
				return $DB->result($result, 0, "plugin_fusinvsnmp_configsecurities_id");
         }
		}	
	}


	function GetSNMPAuthName_XML($ID_auth,$xml_auth_rep) {
		$xml = simplexml_load_file($xml_auth_rep."auth.xml",'SimpleXMLElement', LIBXML_NOCDATA);
		
		$i=-1;
		foreach($xml->auth[0] as $num) {
			$i++;
			$j = 0;
			$recup = 0;
			foreach($xml->auth->conf[$i] as $item) {
				$j++;
				switch ($j) {
					case 1:
						if ($item == $ID_auth) {
							$recup = 1;
                  }
						break;

					case 2:
						if ($recup == "1") {
								$snmp_auth_name = $item;
                  }
						break;
				}
			}
		}
		if (isset($snmp_auth_name)) {
			return "<a href='".GLPI_ROOT . "/plugins/fusioninventory/front/configsecurity.php'>".
                $snmp_auth_name."</a>";
      }
	}

   function canCreate() {
//      return plugin_fusioninventory_haveTypeRight('PluginFusinvsnmpConfigSecurity', 'w');
//      return plugin_fusinvsnmp_haveTypeRight('PluginFusinvsnmpConfigSecurity', 'w');
      return true;
   }

   function canView() {
      return true;
   }
}

?>