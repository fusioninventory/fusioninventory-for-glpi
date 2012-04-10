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

class PluginFusinvsnmpConfigSecurity extends CommonDBTM {
   
   
   function canCreate() {
      return PluginFusioninventoryProfile::haveRight("fusinvsnmp", "configsecurity", "w");
   }


   function canView() {
      return PluginFusioninventoryProfile::haveRight("fusinvsnmp", "configsecurity", "r");
   }
   

   
   function showForm($id, $options=array()) {
      global $LANG;

      PluginFusioninventoryProfile::checkRight("fusinvsnmp", "configsecurity","r");

      if ($id!='') {
         $this->getFromDB($id);
      } else {
         $this->getEmpty();   
      }
      $this->showTabs($options);
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td align='center' colspan='2'>" . $LANG["common"][16] . "</td>";
      echo "<td align='center' colspan='2'>";
      echo "<input type='text' name='name' value='" . $this->fields["name"] . "'/>";
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td align='center' colspan='2'>" . $LANG['plugin_fusinvsnmp']['model_info'][2] . "</td>";
      echo "<td align='center' colspan='2'>";
         $this->showDropdownSNMPVersion($this->fields["snmpversion"]);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<th colspan='2'>v 1 & v 2c</th>";
      echo "<th colspan='2'>v 3</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>" . $LANG['plugin_fusinvsnmp']['snmpauth'][1] . "</td>";
      echo "<td align='center'>";
      echo "<input type='text' name='community' value='" . $this->fields["community"] . "'/>";
      echo "</td>";

      echo "<td align='center'>" . $LANG['plugin_fusinvsnmp']['snmpauth'][2] . "</td>";
      echo "<td align='center'>";
      echo "<input type='text' name='username' value='" . $this->fields["username"] . "'/>";
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td colspan='2'></td>";
      echo "<td align='center'>" . $LANG['plugin_fusinvsnmp']['snmpauth'][4] . "</td>";
      echo "<td align='center'>";
         $this->showDropdownSNMPAuth($this->fields["authentication"]);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td colspan='2'></td>";
      echo "<td align='center'>" . $LANG['plugin_fusinvsnmp']['snmpauth'][5] . "</td>";
      echo "<td align='center'>";
      echo "<input type='text' name='auth_passphrase'
                   value='".$this->fields["auth_passphrase"]."'/>";
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td colspan='2'></td>";
      echo "<td align='center'>" . $LANG['plugin_fusinvsnmp']['snmpauth'][6] . "</td>";
      echo "<td align='center'>";
         $this->showDropdownSNMPEncryption($this->fields["encryption"]);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td colspan='2'></td>";
      echo "<td align='center'>" . $LANG['plugin_fusinvsnmp']['snmpauth'][5] . "</td>";
      echo "<td align='center'>";
      echo "<input type='text' name='priv_passphrase'
                   value='" . $this->fields["priv_passphrase"] . "'/>";
      echo "</td>";
      echo "</tr>";

      $this->showFormButtons($options);

      echo "<div id='tabcontent'></div>";
      echo "<script type='text/javascript'>loadDefaultTab();</script>";

      return true;
   }
   
   
   
   function plugin_fusioninventory_snmp_connections($array=0) {
      global $LANG;

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

      $numero = array();
      $name = array();
      $snmp_version = array();
      $community = array();
      $username = array();
      $authentication = array();
      $auth_passphrase = array();
      $encryption = array();
      $priv_passphrase = array();

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
      
      foreach ($numero AS $key=>$numerosimple) {
         if ($array == '0') {
            echo "<tr class='tab_bg_1'>";
            echo "<td align='center'>".$numerosimple."</td>";
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
            $array_auth["$numero"]['IDC'] = $numerosimple;
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
      $xml_write .= "   <incrementID>".$id."</incrementID>\n";
      $xml_write .= "   <auth>\n";
      $i = -1;
      foreach($xml->auth[0] as $num) {
         $i++;
         $xml_write .= "      <conf>\n";
         $j = 0;
         foreach($xml->auth->conf[$i] as $item) {
            $j++;
            switch ($j) {
               case 1:
                  $xml_write .= "         <Num>".$item."</Num>\n";
                  break;

               case 2:
                  $xml_write .= "         <Name><![CDATA[".$item."]]></Name>\n";
                  break;

               case 3:
                  $xml_write .= "         <snmp_version>".$item."</snmp_version>\n";
                  break;

               case 4:
                  $xml_write .= "         <community><![CDATA[".$item."]]></community>\n";
                  break;

               case 5:
                  $xml_write .= "         <sec_name><![CDATA[".$item."]]></sec_name>\n";
                  break;

               case 7:
                  $xml_write .= "         <auth_protocol>".$item."</auth_protocol>\n";
                  break;

               case 8:
                  $xml_write .= "         <auth_passphrase><![CDATA[".$item.
                                "]]></auth_passphrase>\n";
                  break;

               case 9:
                  $xml_write .= "         <priv_protocol>".$item."</encryption>\n";
                  break;

               case 10:
                  $xml_write .= "         <priv_passphrase><![CDATA[".$item.
                                "]]></priv_passphrase>\n";
                  break;
            }
         }
         $xml_write .= "      </conf>\n";
      }
      // Write new Line
      $xml_write .= "      <conf>\n";
      $xml_write .= "         <Num>".$id."</Num>\n";
      $xml_write .= "         <Name><![CDATA[".$_POST["name"]."]]></Name>\n";
      $xml_write .= "         <snmp_version>".$_POST["plugin_fusioninventory_snmpversions_id"]."</snmp_version>\n";
      $xml_write .= "         <community><![CDATA[".$_POST["community"]."]]></community>\n";
      $xml_write .= "         <sec_name><![CDATA[".$_POST["username"]."]]></sec_name>\n";
      $xml_write .= "         <auth_protocol>".$_POST["authentication"]."</auth_protocol>\n";
      $xml_write .= "         <auth_passphrase><![CDATA[".$_POST["auth_passphrase"].
                    "]]></auth_passphrase>\n";
      $xml_write .= "         <priv_protocol>".$_POST["encryption"]."</priv_protocol>\n";
      $xml_write .= "         <priv_passphrase><![CDATA[".$_POST["priv_passphrase"].
                    "]]></priv_passphrase>\n";
      $xml_write .= "      </conf>\n";
      
      $xml_write .= "   </auth>\n";
      $xml_write .= "</snmp>\n";
      
      $myFile = GLPI_ROOT."/plugins/fusioninventory/scripts/auth.xml";
      $fh = fopen($myFile, 'w') or die("can't open file");
      fwrite($fh, $xml_write);
      fclose($fh);
      
      return $id;
   }



   /**
    * Display SNMP version (dropdown)
    * 
    * @param $p_value
    */
   function showDropdownSNMPVersion($p_value=NULL) {
      $snmpVersions = array(0=>'-----', '1', '2c', '3');
      $options = array();
      if (!is_null($p_value)) {
         $options = array('value'=>$p_value);
      }
      Dropdown::showFromArray("snmpversion", $snmpVersions, $options);
   }


   
   /**
    * Get real version of SNMP
    * 
    * @param $id version number
    * 
    * @return real version 
    */
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


   
   /**
    * Display SNMP authentication encryption (dropdown)
    * 
    * @param $p_value 
    */
   function showDropdownSNMPAuth($p_value=NULL) {
      $authentications = array(0=>'-----', 'MD5', 'SHA');
      $options = array();
      if (!is_null($p_value)) {
         $options = array('value'=>$p_value);
      }
      Dropdown::showFromArray("authentication", $authentications, $options);
   }


   
   /**
    * Get SNMP authentication encryption
    *
    * @param $id
    * 
    * @return encryption 
    */
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
      $options = array();
      if (!is_null($p_value)) {
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
}

?>