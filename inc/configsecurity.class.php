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

class PluginFusioninventoryConfigSecurity extends CommonDBTM {

   public $dohistory = TRUE;

   static $rightname = 'plugin_fusioninventory_configsecurity';


   function defineTabs($options=array()){
      $ong = array();
      $this->addStandardTab('Log', $ong, $options);
      return $ong;
   }



   function showForm($id, $options=array()) {

      Session::checkRight('plugin_fusioninventory_configsecurity', READ);

      if ($id!='') {
         $this->getFromDB($id);
      } else {
         $this->getEmpty();
      }
      $this->showTabs($options);
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td align='center' colspan='2'>" . __('Name') . "</td>";
      echo "<td align='center' colspan='2'>";
      Html::autocompletionTextField($this,'name');
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td align='center' colspan='2'>" . __('SNMP version', 'fusioninventory') . "</td>";
      echo "<td align='center' colspan='2'>";
         $this->showDropdownSNMPVersion($this->fields["snmpversion"]);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<th colspan='2'>v 1 & v 2c</th>";
      echo "<th colspan='2'>v 3</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>" . __('Community', 'fusioninventory') . "</td>";
      echo "<td align='center'>";
      Html::autocompletionTextField($this,'community');
      echo "</td>";

      echo "<td align='center'>" . __('User') . "</td>";
      echo "<td align='center'>";
      Html::autocompletionTextField($this,'username');
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td colspan='2'></td>";
      echo "<td align='center'>".__('Encryption protocol for authentication ', 'fusioninventory').
              "</td>";
      echo "<td align='center'>";
         $this->showDropdownSNMPAuth($this->fields["authentication"]);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td colspan='2'></td>";
      echo "<td align='center'>" . __('Password') . "</td>";
      echo "<td align='center'>";
      Html::autocompletionTextField($this,'auth_passphrase');
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td colspan='2'></td>";
      echo "<td align='center'>" . __('Encryption protocol for data', 'fusioninventory') . "</td>";
      echo "<td align='center'>";
         $this->showDropdownSNMPEncryption($this->fields["encryption"]);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td colspan='2'></td>";
      echo "<td align='center'>" . __('Password') . "</td>";
      echo "<td align='center'>";
      Html::autocompletionTextField($this,'priv_passphrase');
      echo "</td>";
      echo "</tr>";

      $this->showFormButtons($options);

      echo "<div id='tabcontent'></div>";
      echo "<script type='text/javascript'>loadDefaultTab();</script>";

      return TRUE;
   }



   // for file stored snmp authentication
   function add_xml() {
      global $CFG_GLPI;

      // Get new id
      $xml = simplexml_load_file(GLPI_ROOT."/plugins/fusioninventory/scripts/auth.xml",
                                 'SimpleXMLElement',
                                 LIBXML_NOCDATA);

      $id = $xml->incrementID[0] + 1;

      // Write XML file
      $xml_write = "<snmp>\n";
      $xml_write .= "   <incrementID>".$id."</incrementID>\n";
      $xml_write .= "   <auth>\n";
      for ($i=-1; $i < (count($xml->auth[0]) - 1); $i++) {
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
      $xml_write .= "         <snmp_version>".$_POST["plugin_fusioninventory_snmpversions_id"].
                                 "</snmp_version>\n";
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

      $myFile = $CFG_GLPI['root_doc']."/plugins/fusioninventory/scripts/auth.xml";
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
      $encryptions = array(0=>'-----', 'DES', 'AES128', 'AES192', 'AES256', 'Triple-DES');
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

         case '2':
            return 'AES';

         case '3':
            return 'AES192';

         case '4':
            return 'AES256';

         case '5':
            return '3DES';

      }
      return '';
   }



   function selectbox($selected=0) {
      $xml = simplexml_load_file(GLPI_ROOT."/plugins/fusioninventory/scripts/auth.xml",
                                 'SimpleXMLElement',
                                 LIBXML_NOCDATA);
      $selectbox = "<select name='plugin_fusioninventory_configsecurities_id' size='1'>\n
                       <option value='0'>-----</option>\n";
      for ($i=-1; $i < (count($xml->auth[0]) - 1); $i++) {

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



   static function auth_dropdown($selected="") {

      Dropdown::show("PluginFusioninventoryConfigSecurity",
                      array('name' => "plugin_fusioninventory_configsecurities_id",
                           'value' => $selected,
                           'comment' => FALSE));
   }


   /**
    * @since version 0.85
    *
    * @see CommonDBTM::showMassiveActionsSubForm()
   **/
   static function showMassiveActionsSubForm(MassiveAction $ma) {
      switch ($ma->getAction()) {
         case "assign_auth":
            PluginFusioninventoryConfigSecurity::auth_dropdown();
            echo Html::submit(_x('button','Post'), array('name' => 'massiveaction'));
            return true;
            break;
      }
   }


   /**
    * @since version 0.85
    *
    * @see CommonDBTM::processMassiveActionsForOneItemtype()
   **/
   static function processMassiveActionsForOneItemtype(MassiveAction $ma, CommonDBTM $item,
                                                       array $ids) {

      $itemtype = $item->getType();

      switch ($ma->getAction()) {
         case "assign_auth" :
            switch($itemtype) {
               case 'NetworkEquipment':
                  $equipement = new PluginFusioninventoryNetworkEquipment();
                  break;
               case 'Printer':
                  $equipement = new PluginFusioninventoryPrinter();
                  break;
               case 'PluginFusioninventoryUnmanaged':
                  $equipement = new PluginFusinvsnmpUnmanaged();
                  break;
            }

            $fk = getForeignKeyFieldForItemType($itemtype);


            foreach($ids as $key) {
               $found = $equipement->find("`$fk`='".$key."'");
               $input = array();
               if (count($found) > 0) {
                  $current = current($found);
                  $equipement->getFromDB($current['id']);
                  $input['id'] = $equipement->fields['id'];
                  $input['plugin_fusioninventory_configsecurities_id'] =
                              $_POST['plugin_fusioninventory_configsecurities_id'];
                  $return = $equipement->update($input);
               } else {
                  $input[$fk] = $key;
                  $input['plugin_fusioninventory_configsecurities_id'] =
                              $_POST['plugin_fusioninventory_configsecurities_id'];
                  $return = $equipement->add($input);
               }

               if ($return) {
                  //set action massive ok for this item
                  $ma->itemDone($item->getType(), $key, MassiveAction::ACTION_OK);
               } else {
                  // KO
                  $ma->itemDone($item->getType(), $key, MassiveAction::ACTION_KO);
               }
            }
         break;
      }
   }
}

?>
