<?php

/**
 * FusionInventory
 *
 * Copyright (C) 2010-2016 by the FusionInventory Development Team.
 *
 * http://www.fusioninventory.org/
 * https://github.com/fusioninventory/fusioninventory-for-glpi
 * http://forge.fusioninventory.org/
 *
 * ------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of FusionInventory project.
 *
 * FusionInventory is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * FusionInventory is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.
 *
 * ------------------------------------------------------------------------
 *
 * This file is used to manage the functions used in many classes.
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
 * @author    Vincent Mazzoni
 * @author    David Durieux
 * @copyright Copyright (c) 2010-2016 FusionInventory team
 * @license   AGPL License 3.0 or (at your option) any later version
 *            http://www.gnu.org/licenses/agpl-3.0-standalone.html
 * @link      http://www.fusioninventory.org/
 * @link      https://github.com/fusioninventory/fusioninventory-for-glpi
 *
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

/**
 * Manage the functions used in many classes.
 **/
class PluginFusioninventoryToolbox {


   /**
    * Log if extra debug enabled
    *
    * @param string $file
    * @param string $message
    */
   static function logIfExtradebug($file, $message) {
      $config = new PluginFusioninventoryConfig();
      if (PluginFusioninventoryConfig::isExtradebugActive()) {
         if (is_array($message)) {
            $message = print_r($message, true);
         }
         Toolbox::logInFile($file, $message . "\n", true);
      }
   }



   /** Function get on http://www.php.net/manual/en/function.gzdecode.php#82930
    *  used to uncompress gzip string
    *
    * @param string $data
    * @param string $filename
    * @param string $error
    * @param null|integer $maxlength
    * @return null|false|string
    */
   static function gzdecode($data, &$filename = '', &$error = '', $maxlength = null) {
       $len = strlen($data);
      if ($len < 18 || strcmp(substr($data, 0, 2), "\x1f\x8b")) {
         $error = "Not in GZIP format.";
         return null;  // Not GZIP format (See RFC 1952)
      }
       $method = ord(substr($data, 2, 1));  // Compression method
       $flags  = ord(substr($data, 3, 1));  // Flags
      if ($flags & 31 != $flags) {
         $error = "Reserved bits not allowed.";
         return null;
      }
       // NOTE: $mtime may be negative (PHP integer limitations)
      //       $a_mtime = unpack("V", substr($data, 4, 4));
      //       $mtime = $a_mtime[1];
       $headerlen = 10;
       $extralen  = 0;
       $extra     = "";
      if ($flags & 4) {
         // 2-byte length prefixed EXTRA data in header
         if ($len - $headerlen - 2 < 8) {
            return false;  // invalid
         }
         $a_extralen = unpack("v", substr($data, 8, 2));
         $extralen = $a_extralen[1];
         if ($len - $headerlen - 2 - $extralen < 8) {
            return false;  // invalid
         }
         $extra = substr($data, 10, $extralen);
         $headerlen += 2 + $extralen;
      }
       $filenamelen = 0;
       $filename = "";
      if ($flags & 8) {
         // C-style string
         if ($len - $headerlen - 1 < 8) {
            return false; // invalid
         }
         $filenamelen = strpos(substr($data, $headerlen), chr(0));
         if ($filenamelen === false || $len - $headerlen - $filenamelen - 1 < 8) {
            return false; // invalid
         }
         $filename = substr($data, $headerlen, $filenamelen);
         $headerlen += $filenamelen + 1;
      }
       $commentlen = 0;
       $comment = "";
      if ($flags & 16) {
         // C-style string COMMENT data in header
         if ($len - $headerlen - 1 < 8) {
            return false;    // invalid
         }
         $commentlen = strpos(substr($data, $headerlen), chr(0));
         if ($commentlen === false || $len - $headerlen - $commentlen - 1 < 8) {
            return false;    // Invalid header format
         }
         $comment = substr($data, $headerlen, $commentlen);
         $headerlen += $commentlen + 1;
      }
       $headercrc = "";
      if ($flags & 2) {
         // 2-bytes (lowest order) of CRC32 on header present
         if ($len - $headerlen - 2 < 8) {
            return false;    // invalid
         }
         $calccrc = crc32(substr($data, 0, $headerlen)) & 0xffff;
         $a_headercrc = unpack("v", substr($data, $headerlen, 2));
         $headercrc = $a_headercrc[1];
         if ($headercrc != $calccrc) {
            $error = "Header checksum failed.";
            return false;    // Bad header CRC
         }
         $headerlen += 2;
      }
       // GZIP FOOTER
       $a_datacrc = unpack("V", substr($data, -8, 4));
       $datacrc = sprintf('%u', $a_datacrc[1] & 0xFFFFFFFF);
       $a_isize = unpack("V", substr($data, -4));
       $isize = $a_isize[1];
       // decompression:
       $bodylen = $len-$headerlen-8;
      if ($bodylen < 1) {
         // IMPLEMENTATION BUG!
         return null;
      }
       $body = substr($data, $headerlen, $bodylen);
       $data = "";
      if ($bodylen > 0) {
         switch ($method) {
            case 8:
               // Currently the only supported compression method:
               $data = gzinflate($body, $maxlength);
               break;
            default:
               $error = "Unknown compression method.";
               return false;
         }
      }  // zero-byte body content is allowed
       // Verifiy CRC32
       $crc   = sprintf("%u", crc32($data));
       $crcOK = $crc == $datacrc;
       $lenOK = $isize == strlen($data);
      if (!$lenOK || !$crcOK) {
         $error = ( $lenOK ? '' : 'Length check FAILED. ') . ( $crcOK ? '' : 'Checksum FAILED.');
         return false;
      }
       return $data;
   }


   /**
    * Merge 2 simpleXML objects
    *
    * @staticvar boolean $firstLoop
    * @param object $simplexml_to simplexml instance source
    * @param object $simplexml_from simplexml instance destination
    */
   static function appendSimplexml(&$simplexml_to, &$simplexml_from) {
      static $firstLoop=true;

      //Here adding attributes to parent
      if ($firstLoop) {
         foreach ($simplexml_from->attributes() as $attr_key => $attr_value) {
            $simplexml_to->addAttribute($attr_key, $attr_value);
         }
      }
      foreach ($simplexml_from->children() as $simplexml_child) {
         $simplexml_temp = $simplexml_to->addChild($simplexml_child->getName(),
                                                  (string)$simplexml_child);
         foreach ($simplexml_child->attributes() as $attr_key => $attr_value) {
            $simplexml_temp->addAttribute($attr_key, $attr_value);
         }
         $firstLoop=false;
         self::appendSimplexml($simplexml_temp, $simplexml_child);
      }
      unset($firstLoop);
   }


   /**
    * Clean XML, ie convert to be insert without problem into MySQL database
    *
    * @param object $xml SimpleXMLElement instance
    * @return object SimpleXMLElement instance
    */
   function cleanXML($xml) {
      $nodes = [];
      foreach ($xml->children() as $key=>$value) {
         if (!isset($nodes[$key])) {
            $nodes[$key] = 0;
         }
         $nodes[$key]++;
      }
      foreach ($nodes as $key=>$nb) {
         if ($nb < 2) {
            unset($nodes[$key]);
         }
      }

      if (count($xml) > 0) {
         $i = 0;
         foreach ($xml->children() as $key=>$value) {
            if (count($value->children()) > 0) {
               $this->cleanXML($value);
            } else if (isset($nodes[$key])) {
               $xml->$key->$i = Toolbox::clean_cross_side_scripting_deep(
                                    Toolbox::addslashes_deep($value));
               $i++;
            } else {
               $xml->$key = Toolbox::clean_cross_side_scripting_deep(
                                 Toolbox::addslashes_deep($value));
            }
         }
      }
      return $xml;
   }


   /**
    * Format XML, ie indent it for pretty printing
    *
    * @param object $xml simplexml instance
    * @return string
    */
   static function formatXML($xml) {
      $string     = str_replace("><", ">\n<", $xml->asXML());
      $token      = strtok($string, "\n");
      $result     = '';
      $pad        = 0;
      $matches    = [];
      $indent     = 0;

      while ($token !== false) {
         // 1. open and closing tags on same line - no change
         if (preg_match('/.+<\/\w[^>]*>$/', $token, $matches)) {
            $indent=0;
            // 2. closing tag - outdent now
         } else if (preg_match('/^<\/\w/', $token, $matches)) {
            $pad = $pad-3;
            // 3. opening tag - don't pad this one, only subsequent tags
         } else if (preg_match('/^<\w[^>]*[^\/]>.*$/', $token, $matches)) {
            $indent=3;
         } else {
            $indent = 0;
         }

         $line    = Toolbox::str_pad($token, strlen($token)+$pad, '  ', STR_PAD_LEFT);
         $result .= $line . "\n";
         $token   = strtok("\n");
         $pad    += $indent;
         $indent = 0;
      }

      return $result;
   }


   /**
    * Write XML in a folder from an inventory by agent
    *
    * @param integer $items_id id of the unmanaged device
    * @param string $xml xml informations (with XML structure)
    * @param string $itemtype
    */
   static function writeXML($items_id, $xml, $itemtype) {

      $folder = substr($items_id, 0, -1);
      if (empty($folder)) {
         $folder = '0';
      }
      if (!file_exists(GLPI_PLUGIN_DOC_DIR."/fusioninventory")) {
         mkdir(GLPI_PLUGIN_DOC_DIR."/fusioninventory");
      }
      if (!file_exists(PLUGIN_FUSIONINVENTORY_XML_DIR)) {
         mkdir(PLUGIN_FUSIONINVENTORY_XML_DIR);
      }
      $itemtype_dir = PLUGIN_FUSIONINVENTORY_XML_DIR.strtolower($itemtype);
      if (!file_exists($itemtype_dir)) {
         mkdir($itemtype_dir);
      }
      if (!file_exists($itemtype_dir."/".$folder)) {
         mkdir($itemtype_dir."/".$folder);
      }
      $file     = $itemtype_dir."/".$folder."/".$items_id.'.xml';
      $fileopen = fopen($file, 'w');
      fwrite($fileopen, $xml);
      fclose($fileopen);
   }


   /**
    * Add AUTHENTICATION string to XML node
    *
    * @param object $p_sxml_node XML node to authenticate
    * @param integer $p_id Authenticate id
    **/
   function addAuth($p_sxml_node, $p_id) {
      $pfConfigSecurity = new PluginFusioninventoryConfigSecurity();
      if ($pfConfigSecurity->getFromDB($p_id)) {

         $sxml_authentication = $p_sxml_node->addChild('AUTHENTICATION');

         $sxml_authentication->addAttribute('ID', $p_id);
         $sxml_authentication->addAttribute('VERSION',
                    $pfConfigSecurity->getSNMPVersion($pfConfigSecurity->fields['snmpversion']));
         if ($pfConfigSecurity->fields['snmpversion'] == '3') {
            $sxml_authentication->addAttribute('USERNAME',
                                               $pfConfigSecurity->fields['username']);
            if ($pfConfigSecurity->fields['authentication'] != '0') {
               $sxml_authentication->addAttribute('AUTHPROTOCOL',
                      $pfConfigSecurity->getSNMPAuthProtocol(
                              $pfConfigSecurity->fields['authentication']));
            }
            $sxml_authentication->addAttribute('AUTHPASSPHRASE',
                                               $pfConfigSecurity->fields['auth_passphrase']);
            if ($pfConfigSecurity->fields['encryption'] != '0') {
               $sxml_authentication->addAttribute('PRIVPROTOCOL',
                              $pfConfigSecurity->getSNMPEncryption(
                                       $pfConfigSecurity->fields['encryption']));
            }
            $sxml_authentication->addAttribute('PRIVPASSPHRASE',
                                                $pfConfigSecurity->fields['priv_passphrase']);
         } else {
            $sxml_authentication->addAttribute('COMMUNITY',
                                               $pfConfigSecurity->fields['community']);
         }
      }
   }


   /**
    * Add GET oids to XML node 'GET'
    *
    * @param object $p_sxml_node
    * @param string $p_object
    * @param string $p_oid
    * @param string $p_link
    * @param string $p_vlan
    */
   function addGet($p_sxml_node, $p_object, $p_oid, $p_link, $p_vlan) {
      $sxml_get = $p_sxml_node->addChild('GET');
         $sxml_get->addAttribute('OBJECT', $p_object);
         $sxml_get->addAttribute('OID', $p_oid);
         $sxml_get->addAttribute('VLAN', $p_vlan);
         $sxml_get->addAttribute('LINK', $p_link);
   }


   /**
    * Add WALK (multiple oids) oids to XML node 'WALK'
    *
    * @param object $p_sxml_node
    * @param string $p_object
    * @param string $p_oid
    * @param string $p_link
    * @param string $p_vlan
    */
   function addWalk($p_sxml_node, $p_object, $p_oid, $p_link, $p_vlan) {
      $sxml_walk = $p_sxml_node->addChild('WALK');
         $sxml_walk->addAttribute('OBJECT', $p_object);
         $sxml_walk->addAttribute('OID', $p_oid);
         $sxml_walk->addAttribute('VLAN', $p_vlan);
         $sxml_walk->addAttribute('LINK', $p_link);
   }


   /**
    * Get IP for device
    *
    * @param string $itemtype
    * @param integer $items_id
    * @return array
    */
   static function getIPforDevice($itemtype, $items_id) {
      $NetworkPort = new NetworkPort();
      $networkName = new NetworkName();
      $iPAddress   = new IPAddress();

      $a_ips = [];
      $a_ports = $NetworkPort->find(
            ['itemtype'           => $itemtype,
             'items_id'           => $items_id,
             'instantiation_type' => ['!=', 'NetworkPortLocal']]);
      foreach ($a_ports as $a_port) {
         $a_networknames = $networkName->find(
               ['itemtype' => 'NetworkPort',
                'items_id' => $a_port['id']]);
         foreach ($a_networknames as $a_networkname) {
            $a_ipaddresses = $iPAddress->find(
                  ['itemtype' => 'NetworkName',
                   'items_id' => $a_networkname['id']]);
            foreach ($a_ipaddresses as $data) {
               if ($data['name'] != '127.0.0.1'
                       && $data['name'] != '::1') {
                  $a_ips[$data['name']] = $data['name'];
               }
            }
         }
      }
      return array_unique($a_ips);
   }


   // *********************** Functions used for inventory *********************** //


   /**
    * Check lock
    *
    * @param array $data
    * @param array $db_data
    * @param array $a_lockable
    * @return array
    */
   static function checkLock($data, $db_data, $a_lockable = []) {
      foreach ($a_lockable as $field) {
         if (isset($data[$field])) {
            unset($data[$field]);
         }
         if (isset($db_data[$field])) {
            unset($db_data[$field]);
         }
      }
      return [$data, $db_data];
   }


   /**
    * Display data from serialized inventory field
    *
    * @param array $array
    */
   static function displaySerializedValues($array) {

      foreach ($array as $key=>$value) {
         echo "<tr class='tab_bg_1'>";
         echo "<th>";
         echo $key;
         echo "</th>";
         echo "<td>";
         if (is_array($value)) {
            echo "<table class='tab_cadre' width='100%'>";
            PluginFusioninventoryToolbox::displaySerializedValues($value);
            echo "</table>";
         } else {
            echo $value;
         }
         echo "</td>";
         echo "</tr>";
      }
   }


   /**
    * Send serialized inventory to user browser (to download)
    *
    * @param integer $items_id
    * @param string $itemtype
    */
   static function sendSerializedInventory($items_id, $itemtype) {
      header('Content-type: text/plain');

      if (call_user_func([$itemtype, 'canView'])) {
         $item = new $itemtype();
         $item->getFromDB($items_id);
         echo gzuncompress($item->fields['serialized_inventory']);
      } else {
         Html::displayRightError();
      }
   }


   /**
    * Send the XML (last inventory) to user browser (to download)
    *
    * @param integer $items_id
    * @param string $itemtype
    */
   static function sendXML($items_id, $itemtype) {
      if (preg_match("/^([a-zA-Z]+)\/(\d+)\/(\d+)\.xml$/", $items_id)
         && call_user_func([$itemtype, 'canView'])) {
         $xml = file_get_contents(GLPI_PLUGIN_DOC_DIR."/fusioninventory/xml/".$items_id);
         echo $xml;
      } else {
         Html::displayRightError();
      }

   }


   /**
    *  This function fetch rows from a MySQL result in an array with each table as a key
    *
    *  example:
    *  $query =
    *     "SELECT table_a.*,table_b.* ".
    *     "FROM table_b ".
    *     "LEFT JOIN table_a ON table_a.id = table_b.linked_id";
    *  $result = mysqli_query( $query );
    *  print_r( fetchTableAssoc( $result ) )
    *
    *  output:
    *  $results = Array
    *     (
    *        [0] => Array
    *           (
    *              [table_a] => Array
    *                 (
    *                    [id] => 1
    *                 )
    *              [table_b] => Array
    *                 (
    *                    [id] => 2
    *                    [linked_id] => 1
    *                 )
    *           )
    *           ...
    *     )
    *
    * @param object $mysql_result
    * @return array
    */
   static function fetchAssocByTable($mysql_result) {
      $results = [];
      //get fields header infos
      $fields = mysqli_fetch_fields($mysql_result);
      //associate row data as array[table][field]
      while ($row = mysqli_fetch_row($mysql_result)) {
         $result = [];
         for ($i=0; $i < count( $row ); $i++) {
            $tname = $fields[$i]->table;
            $fname = $fields[$i]->name;
            if (!isset($result[$tname])) {
               $result[$tname] = [];
            }
            $result[$tname][$fname] = $row[$i];
         }
         if (count($result) > 0) {
            $results[] = $result;
         }
      }
      return $results;
   }


   /**
    * Format a json in a pretty json
    *
    * @param string $json
    * @return string
    */
   static function formatJson($json) {
      $version = phpversion();

      if (version_compare($version, '5.4', 'lt')) {
         return pretty_json($json);
      } else if (version_compare($version, '5.4', 'ge')) {
         return json_encode(
            json_decode($json, true),
            JSON_PRETTY_PRINT
         );
      }
   }


   /**
    * Dropdown for display hours
    *
    * @param string $name
    * @param array $options
    * @return string unique html element id
    */
   static function showHours($name, $options = []) {

      $p['value']          = '';
      $p['display']        = true;
      $p['width']          = '80%';
      $p['step']           = 5;
      $p['begin']          = 0;
      $p['end']            = (24 * 3600);

      if (is_array($options) && count($options)) {
         foreach ($options as $key => $val) {
            $p[$key] = $val;
         }
      }
      if ($p['step'] <= 0) {
         $p['step'] = 5;
      }

      $values   = [];

      $p['step'] = $p['step'] * 60; // to have in seconds
      for ($s=$p['begin']; $s<=$p['end']; $s+=$p['step']) {
         $values[$s] = PluginFusioninventoryToolbox::getHourMinute($s);
      }
      return Dropdown::showFromArray($name, $values, $p);
   }


   /**
    * Get hour:minute from number of seconds
    *
    * @param integer $seconds
    * @return string
    */
   static function getHourMinute($seconds) {
      $hour = floor($seconds / 3600);
      $minute = (($seconds - ((floor($seconds / 3600)) * 3600)) / 60);
      return sprintf("%02s", $hour).":".sprintf("%02s", $minute);
   }


   /**
    * Get information if allow_url_fopen is activated and display message if not
    *
    * @param integer $wakecomputer (1 if it's for wakeonlan, 0 if it's for task)
    * @return boolean
    */
   static function isAllowurlfopen($wakecomputer = 0) {

      if (!ini_get('allow_url_fopen')) {
         echo "<center>";
         echo "<table class='tab_cadre' height='30' width='700'>";
         echo "<tr class='tab_bg_1'>";
         echo "<td align='center'><strong>";
         if ($wakecomputer == '0') {
            echo __('PHP allow_url_fopen is off, remote can\'t work')." !";
         } else {
            echo __('PHP allow_url_fopen is off, can\'t wake agent to do inventory')." !";
         }
         echo "</strong></td>";
         echo "</tr>";
         echo "</table>";
         echo "</center>";
         echo "<br/>";
         return false;
      }
      return true;
   }


   /**
    * Execute a function as Fusioninventory user
    *
    * @param string|array $function
    * @param array $args
    * @return array the normaly returned value from executed callable
    */
   function executeAsFusioninventoryUser($function, array $args = []) {

      $config = new PluginFusioninventoryConfig();
      $user = new User();

      // Backup _SESSION environment
      $OLD_SESSION = [];

      foreach (['glpiID', 'glpiname','glpiactiveentities_string',
          'glpiactiveentities', 'glpiparententities'] as $session_key) {
         if (isset($_SESSION[$session_key])) {
            $OLD_SESSION[$session_key] = $_SESSION[$session_key];
         }
      }

      // Configure impersonation
      $users_id  = $config->getValue('users_id');
      $user->getFromDB($users_id);

      $_SESSION['glpiID']   = $users_id;
      $_SESSION['glpiname'] = $user->getField('name');
      $_SESSION['glpiactiveentities'] = getSonsOf('glpi_entities', 0);
      $_SESSION['glpiactiveentities_string'] =
         "'". implode( "', '", $_SESSION['glpiactiveentities'] )."'";
      $_SESSION['glpiparententities'] = [];

      // Execute function with impersonated SESSION
      $result = call_user_func_array($function, $args);

      // Restore SESSION
      foreach ($OLD_SESSION as $key => $value) {
         $_SESSION[$key] = $value;
      }
      // Return function results
      return $result;
   }


   /**
   * Check if an item is inventoried by FusionInventory
   *
   * @since 9.2
   * @param CommonDBTM $item the item to check
   * @return boolean true if handle by FusionInventory
   */
   static function isAFusionInventoryDevice($item) {
      $table = '';
      switch ($item->getType()) {
         case 'Computer':
            $table = 'glpi_plugin_fusioninventory_inventorycomputercomputers';
            $fk    = 'computers_id';
            break;

         case 'NetworkEquipment':
            $table = 'glpi_plugin_fusioninventory_networkequipments';
            $fk    = 'networkequipments_id';
            break;

         case 'Printer':
            $table = 'glpi_plugin_fusioninventory_printers';
            $fk    = 'printers_id';
            break;

      }
      if ($table) {
         return $item->isDynamic()
            && countElementsInTable($table, [$fk => $item->getID()]);
      } else {
         // check if device has data in glpi_plugin_fusioninventory_rulematchedlogs table
         return $item->isDynamic()
            && countElementsInTable('glpi_plugin_fusioninventory_rulematchedlogs',
                                    ['itemtype' => $item->getType(), 'items_id' => $item->fields['id']]);
      }
   }


   /**
    * Get default value for state of devices (monitor, printer...)
    *
    * @param string type the type of inventory performed (values : computer, snmp)
    * @param array $input
    * @return array the fields with the states_id filled, is necessary
    */
   static function addDefaultStateIfNeeded($type, $input) {
      $config = new PluginFusioninventoryConfig();
      switch ($type) {
         case 'computer':
            if ($states_id_default = $config->getValue("states_id_default")) {
               $input['states_id'] = $states_id_default;
            }
            break;

         case 'snmp':
            if ($states_id_snmp_default = $config->getValue("states_id_snmp_default")) {
               $input['states_id'] = $states_id_snmp_default;
            }
            break;

         default:
            $state = false;
            break;
      }
      return $input;
   }

   /**
    * Add a location if required by a rule
    * @since 9.2+2.0
    *
    * @param array $input fields of the asset being inventoried
    * @param array $output output array in which the location should be added (optionnal)
    * @return array the fields with the locations_id filled, is necessary
    */
   static function addLocation($input, $output = false) {
      //manage location
      $ruleLocation = new PluginFusioninventoryInventoryRuleLocationCollection();

      // * Reload rules (required for unit tests)
      $ruleLocation->getCollectionPart();

      $dataLocation = $ruleLocation->processAllRules($input);
      if (isset($dataLocation['locations_id'])) {
         if ($output) {
            $output['locations_id'] = $dataLocation['locations_id'];
         } else {
            $input['locations_id'] = $dataLocation['locations_id'];
         }
      }
      return ($output?$output:$input);
   }

   /**
    * set inventory number, depending on options defined in configuration
    */
   static function setInventoryNumber($itemtype, $value, $entities_id = -1) {
      if (!in_array($itemtype, ['Computer', 'Monitor', 'NetworkEquipment', 'Peripheral', 'Phone', 'Printer'])) {
         return $value;
      }

      $dbutils = new DbUtils();
      $config = new PluginFusioninventoryConfig();

      $autonum = $config->getValue('auto_inventory_number_'.strtolower($itemtype));
      $autonum = str_replace('<', '&lt;', $autonum);
      $autonum = str_replace('>', '&gt;', $autonum);

      $new_value = $dbutils->autoName($autonum, 'otherserial', true, $itemtype, $entities_id);

      return $new_value;
   }
}
