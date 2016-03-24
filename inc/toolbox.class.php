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
   @author    Vincent Mazzoni
   @co-author David Durieux
   @copyright Copyright (c) 2010-2016 FusionInventory team
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

// add readable json encode for PHP < 5.4
include_once( dirname(__FILE__) . "/../lib/pretty_json.php" );

/**
 * Toolbox of various utility methods
 **/
class PluginFusioninventoryToolbox {

   /**
    * Log when extra-debug is activated
    */
   static function logIfExtradebug($file, $message) {
      $config = new PluginFusioninventoryConfig();
      if ($config->getValue('extradebug')) {
         if (is_array($message)) {
            $message = print_r($message, TRUE);
         }
         Toolbox::logInFile($file, $message);
      }
   }

   /** Fonction get on http://www.php.net/manual/en/function.gzdecode.php#82930
    *  used to uncompress gzip string
    *
    * @param type $data
    * @param type $filename
    * @param type $error
    * @param type $maxlength
    * @return type
    */
   static function gzdecode($data, &$filename='', &$error='', $maxlength=NULL) {
       $len = strlen($data);
       if ($len < 18 || strcmp(substr($data, 0, 2), "\x1f\x8b")) {
           $error = "Not in GZIP format.";
           return NULL;  // Not GZIP format (See RFC 1952)
       }
       $method = ord(substr($data, 2, 1));  // Compression method
       $flags  = ord(substr($data, 3, 1));  // Flags
       if ($flags & 31 != $flags) {
           $error = "Reserved bits not allowed.";
           return NULL;
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
               return FALSE;  // invalid
           }
           $a_extralen = unpack("v", substr($data, 8, 2));
           $extralen = $a_extralen[1];
           if ($len - $headerlen - 2 - $extralen < 8) {
               return FALSE;  // invalid
           }
           $extra = substr($data, 10, $extralen);
           $headerlen += 2 + $extralen;
       }
       $filenamelen = 0;
       $filename = "";
       if ($flags & 8) {
           // C-style string
           if ($len - $headerlen - 1 < 8) {
               return FALSE; // invalid
           }
           $filenamelen = strpos(substr($data, $headerlen), chr(0));
           if ($filenamelen === FALSE || $len - $headerlen - $filenamelen - 1 < 8) {
               return FALSE; // invalid
           }
           $filename = substr($data, $headerlen, $filenamelen);
           $headerlen += $filenamelen + 1;
       }
       $commentlen = 0;
       $comment = "";
       if ($flags & 16) {
           // C-style string COMMENT data in header
           if ($len - $headerlen - 1 < 8) {
               return FALSE;    // invalid
           }
           $commentlen = strpos(substr($data, $headerlen), chr(0));
           if ($commentlen === FALSE || $len - $headerlen - $commentlen - 1 < 8) {
               return FALSE;    // Invalid header format
           }
           $comment = substr($data, $headerlen, $commentlen);
           $headerlen += $commentlen + 1;
       }
       $headercrc = "";
       if ($flags & 2) {
           // 2-bytes (lowest order) of CRC32 on header present
           if ($len - $headerlen - 2 < 8) {
               return FALSE;    // invalid
           }
           $calccrc = crc32(substr($data, 0, $headerlen)) & 0xffff;
           $a_headercrc = unpack("v", substr($data, $headerlen, 2));
           $headercrc = $a_headercrc[1];
           if ($headercrc != $calccrc) {
               $error = "Header checksum failed.";
               return FALSE;    // Bad header CRC
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
           return NULL;
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
               return FALSE;
           }
       }  // zero-byte body content is allowed
       // Verifiy CRC32
       $crc   = sprintf("%u", crc32($data));
       $crcOK = $crc == $datacrc;
       $lenOK = $isize == strlen($data);
       if (!$lenOK || !$crcOK) {
           $error = ( $lenOK ? '' : 'Length check FAILED. ') . ( $crcOK ? '' : 'Checksum FAILED.');
           return FALSE;
       }
       return $data;
   }

   /**
    * Merge 2 simpleXML objects
    *
    * @param $simplexml_to simplexml object source
    * @param $simplexml_from simplexml object destination
    *
    **/
   static function append_simplexml(&$simplexml_to, &$simplexml_from) {
      static $firstLoop=TRUE;

      //Here adding attributes to parent
      if ($firstLoop) {
         foreach($simplexml_from->attributes() as $attr_key => $attr_value) {
            $simplexml_to->addAttribute($attr_key, $attr_value);
         }
      }
      foreach ($simplexml_from->children() as $simplexml_child) {
         $simplexml_temp = $simplexml_to->addChild($simplexml_child->getName(),
                                                  (string)$simplexml_child);
         foreach ($simplexml_child->attributes() as $attr_key => $attr_value) {
            $simplexml_temp->addAttribute($attr_key, $attr_value);
         }
         $firstLoop=FALSE;
         self::append_simplexml($simplexml_temp, $simplexml_child);
      }
      unset($firstLoop);
   }



   /**
    * Clean XML, ie convert to be insert without problem into MySQL DB
    *
    * @param $xml SimpleXMLElement object
    * @return SimpleXMLElement object
    */
   function cleanXML($xml) {
      $nodes = array();
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
    * @param $xml simplexml object
    * @return string
    **/
   static function formatXML($xml) {
      $string     = str_replace("><", ">\n<", $xml->asXML());
      $token      = strtok($string, "\n");
      $result     = '';
      $pad        = 0;
      $matches    = array();
      $indent     = 0;

      while ($token !== FALSE) {
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
   * @param $items_id integer id of the unmanaged device
   * @param $xml value xml informations (with XML structure)
   *
   * @return nothing
   *
   **/
   static function writeXML($items_id, $xml, $itemtype) {

      $folder = substr($items_id, 0, -1);
      if (empty($folder)) {
         $folder = '0';
      }
      if (!file_exists(GLPI_PLUGIN_DOC_DIR."/fusioninventory/xml/")) {
         mkdir(GLPI_PLUGIN_DOC_DIR."/fusioninventory/xml/");
      }
      if (!file_exists(GLPI_PLUGIN_DOC_DIR."/fusioninventory/xml/".$itemtype)) {
         mkdir(GLPI_PLUGIN_DOC_DIR."/fusioninventory/xml/".$itemtype);
      }
      if (!file_exists(GLPI_PLUGIN_DOC_DIR."/fusioninventory/xml/".$itemtype."/".$folder)) {
         mkdir(GLPI_PLUGIN_DOC_DIR."/fusioninventory/xml/".$itemtype."/".$folder);
      }
      $fileopen = fopen(GLPI_PLUGIN_DOC_DIR."/fusioninventory/xml/".$itemtype."/".$folder."/".
                           $items_id, 'w');
      fwrite($fileopen, $xml);
      fclose($fileopen);
   }



   /**
    * Add AUTHENTICATION string to XML node
    *
    *@param $p_sxml_node XML node to authenticate
    *@param $p_id Authenticate id
    *@return nothing
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
               if ($pfConfigSecurity->fields['authentication'] == '0') {
//                  $sxml_authentication->addAttribute('AUTHPROTOCOL', '');
               } else {
                  $sxml_authentication->addAttribute('AUTHPROTOCOL',
                         $pfConfigSecurity->getSNMPAuthProtocol(
                                 $pfConfigSecurity->fields['authentication']));
               }
               $sxml_authentication->addAttribute('AUTHPASSPHRASE',
                                                  $pfConfigSecurity->fields['auth_passphrase']);
               if ($pfConfigSecurity->fields['encryption'] == '0') {
//                  $sxml_authentication->addAttribute('PRIVPROTOCOL', '');
               } else {
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
    * @param type $p_sxml_node
    * @param type $p_object
    * @param type $p_oid
    * @param type $p_link
    * @param type $p_vlan
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
    * @param type $p_sxml_node
    * @param type $p_object
    * @param type $p_oid
    * @param type $p_link
    * @param type $p_vlan
    */
   function addWalk($p_sxml_node, $p_object, $p_oid, $p_link, $p_vlan) {
      $sxml_walk = $p_sxml_node->addChild('WALK');
         $sxml_walk->addAttribute('OBJECT', $p_object);
         $sxml_walk->addAttribute('OID', $p_oid);
         $sxml_walk->addAttribute('VLAN', $p_vlan);
         $sxml_walk->addAttribute('LINK', $p_link);
   }



   static function getIPforDevice($itemtype, $items_id) {
      $NetworkPort = new NetworkPort();
      $networkName = new NetworkName();
      $iPAddress   = new IPAddress();

      $a_ips = array();
      $a_ports = $NetworkPort->find("`itemtype`='".$itemtype."'
                                       AND `items_id`='".$items_id."'
                                          AND `instantiation_type` != 'NetworkPortLocal'");
      foreach($a_ports as $a_port) {
         $a_networknames = $networkName->find("`itemtype`='NetworkPort'
                                              AND `items_id`='".$a_port['id']."'");
         foreach ($a_networknames as $a_networkname) {
            $a_ipaddresses = $iPAddress->find("`itemtype`='NetworkName'
                                              AND `items_id`='".$a_networkname['id']."'");
            foreach($a_ipaddresses as $data) {
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


   static function diffArray($array1, $array2) {

      $a_return = array();
      foreach ($array1 as $key=>$value) {
         if (defined($array2[$key])
                 && $array2[$key] != $value) {
            $a_return[$key] = $value;
         }
//         $key2 = FALSE;
//         $key2 = array_search($value, $array2, TRUE);
//         if ($key2) {
//            unset($array2[$key2]);
//         } else {
//            $a_return[$key] = $value;
//         }
      }
      return $a_return;
   }



   static function checkLock($data, $db_data, $a_lockable=array()) {
      foreach($a_lockable as $field) {
         if (isset($data[$field])) {
            unset($data[$field]);
         }
         if (isset($db_data[$field])) {
            unset($db_data[$field]);
         }
      }
      return array($data, $db_data);
   }



   static function showYesNo($name, $value=0) {
      $rand = mt_rand();
      $checked['yes'] = '';
      $checked['no'] = '';
      if ($value == 0) {
         $checked['no'] = 'checked';
      } else {
         $checked['yes'] = 'checked';
      }
      echo '<table>
            <tr>
            <td><input type="radio" value="1" id="yes'.$rand.'" name="'.$name.'" '.
              $checked['yes'].' />
            <label for="yes'.$rand.'">'.__('Yes').'</label></td>
		   	<td><input type="radio" value="0" id="no'.$rand.'" name="'.$name.'" '.
              $checked['no'].' />
            <label for="no'.$rand.'">'.__('No').'</label></td>
            </tr>
            </table>';
   }



   /**
    * Display data from serialized inventory field
    */
   static function displaySerializedValues($array) {

//      TODO: to fix in 0.85
//      if ($_POST['glpi_tab'] == -1) { // tab all
//         return;
//      }

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



   static function sendSerializedInventory($items_id, $itemtype) {
      header('Content-type: text/plain');

      if (call_user_func(array($itemtype, 'canView'))) {
         $item = new $itemtype();
         $item->getFromDB($items_id);
         echo gzuncompress($item->fields['serialized_inventory']);
      } else {
         Html::displayRightError();
      }
   }



   static function sendXML($items_id, $itemtype) {
      if (call_user_func(array($itemtype, 'canView'))) {
         $xml = file_get_contents(GLPI_PLUGIN_DOC_DIR."/fusioninventory/xml/".$items_id);
         echo $xml;
      } else {
         Html::displayRightError();
      }

   }
   /**
   *  @function fetchAssocByTable
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
   **/
   static function fetchAssocByTable($mysql_result){
      $results = array();
      //get fields header infos
      $fields = mysqli_fetch_fields($mysql_result);
      //associate row data as array[table][field]
      while( $row = mysqli_fetch_row($mysql_result) ) {
         $result = array();
         for( $i=0; $i < count( $row ); $i++ ) {
            $tname = $fields[$i]->table;
            $fname = $fields[$i]->name;
            if (!isset($result[$tname])) {
               $result[$tname] = array();
            }
            $result[$tname][$fname] = $row[$i];
         }
         if (count($result) > 0) {
            $results[] = $result;
         }
      }
      return $results;
   }



   static function formatJson($json) {
      $version = phpversion();

      if ( version_compare($version, '5.4', 'lt') ) {
         return pretty_json($json);
      } else if ( version_compare($version, '5.4', 'ge') ) {
         return json_encode(
            json_decode($json, TRUE),
            JSON_PRETTY_PRINT
         );
      }
   }



   /**
    * Dropdown for display hours
    *
    * @return type
    */
   static function showHours($name, $options=array()) {

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

      $values   = array();

      $p['step'] = $p['step'] * 60; // to have in seconds
      for ($s=$p['begin'] ; $s<=$p['end'] ; $s+=$p['step']) {
         $values[$s] = PluginFusioninventoryToolbox::getHourMinute($s);
      }
      return Dropdown::showFromArray($name, $values, $p);
   }

   /**
    * Get hour:minute from number of seconds
    */
   static function getHourMinute($seconds) {
      $hour = floor($seconds / 3600);
      $minute = (($seconds - ((floor($seconds / 3600)) * 3600)) / 60);
      return sprintf("%02s", $hour).":".sprintf("%02s", $minute);
   }

   /**
    * Get information if allow_url_fopen is activated and display message if not
    *
    * @param $wakecomputer boolean (1 if it's for wakeonlan, 0 if it's for task)
    *
    * @return boolean
    */
   static function isAllowurlfopen($wakecomputer=0) {

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
         return FALSE;
      }
      return TRUE;
   }

   /**
    *  Execute a function as Fusioninventory user
    *  @param $function callable
    *  @param $args array
    *
    *  @return the normally returned value from executed callable
    */

   function executeAsFusioninventoryUser($function, array $args = array()) {

      $config = new PluginFusioninventoryConfig();
      $user = new User();

      // Backup _SESSION environment
      $OLD_SESSION = array();

      foreach(
         array(
            'glpiID', 'glpiname','glpiactiveentities_string', 'glpiactiveentities',
            'glpiparententities'
         ) as $session_key
      ) {
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
      $_SESSION['glpiparententities'] = array();

      // Execute function with impersonated SESSION
      $result = call_user_func_array($function, $args);

      // Restore SESSION
      foreach($OLD_SESSION as $key => $value) {
         $_SESSION[$key] = $value;
      }

      // Return function results
      return $result;

   }

}

?>
