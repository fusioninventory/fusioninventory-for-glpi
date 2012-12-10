<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2011 by the FusionInventory Development Team.

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
   @author    Vincent Mazzoni
   @co-author David Durieux
   @copyright Copyright (c) 2010-2011 FusionInventory team
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

/**
 * Toolbox of various utility methods
 **/
class PluginFusioninventoryToolbox {

   /**
    * Log when extra-debug is activated
    */
   static function logIfExtradebug($file, $message) {
      $config = new PluginFusioninventoryConfig();
      if ($config->getValue($_SESSION["plugin_fusioninventory_moduleid"], 'extradebug', '')) {
         if (is_array($message)) {
            $message = print_r($message, true);
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
   static function gzdecode($data,&$filename='',&$error='',$maxlength=null) {
       $len = strlen($data);
       if ($len < 18 || strcmp(substr($data,0,2),"\x1f\x8b")) {
           $error = "Not in GZIP format.";
           return null;  // Not GZIP format (See RFC 1952)
       }
       $method = ord(substr($data,2,1));  // Compression method
       $flags  = ord(substr($data,3,1));  // Flags
       if ($flags & 31 != $flags) {
           $error = "Reserved bits not allowed.";
           return null;
       }
       // NOTE: $mtime may be negative (PHP integer limitations)
       $mtime = unpack("V", substr($data,4,4));
       $mtime = $mtime[1];
       $headerlen = 10;
       $extralen  = 0;
       $extra     = "";
       if ($flags & 4) {
           // 2-byte length prefixed EXTRA data in header
           if ($len - $headerlen - 2 < 8) {
               return false;  // invalid
           }
           $extralen = unpack("v",substr($data,8,2));
           $extralen = $extralen[1];
           if ($len - $headerlen - 2 - $extralen < 8) {
               return false;  // invalid
           }
           $extra = substr($data,10,$extralen);
           $headerlen += 2 + $extralen;
       }
       $filenamelen = 0;
       $filename = "";
       if ($flags & 8) {
           // C-style string
           if ($len - $headerlen - 1 < 8) {
               return false; // invalid
           }
           $filenamelen = strpos(substr($data,$headerlen),chr(0));
           if ($filenamelen === false || $len - $headerlen - $filenamelen - 1 < 8) {
               return false; // invalid
           }
           $filename = substr($data,$headerlen,$filenamelen);
           $headerlen += $filenamelen + 1;
       }
       $commentlen = 0;
       $comment = "";
       if ($flags & 16) {
           // C-style string COMMENT data in header
           if ($len - $headerlen - 1 < 8) {
               return false;    // invalid
           }
           $commentlen = strpos(substr($data,$headerlen),chr(0));
           if ($commentlen === false || $len - $headerlen - $commentlen - 1 < 8) {
               return false;    // Invalid header format
           }
           $comment = substr($data,$headerlen,$commentlen);
           $headerlen += $commentlen + 1;
       }
       $headercrc = "";
       if ($flags & 2) {
           // 2-bytes (lowest order) of CRC32 on header present
           if ($len - $headerlen - 2 < 8) {
               return false;    // invalid
           }
           $calccrc = crc32(substr($data,0,$headerlen)) & 0xffff;
           $headercrc = unpack("v", substr($data,$headerlen,2));
           $headercrc = $headercrc[1];
           if ($headercrc != $calccrc) {
               $error = "Header checksum failed.";
               return false;    // Bad header CRC
           }
           $headerlen += 2;
       }
       // GZIP FOOTER
       $datacrc = unpack("V",substr($data,-8,4));
       $datacrc = sprintf('%u',$datacrc[1] & 0xFFFFFFFF);
       $isize = unpack("V",substr($data,-4));
       $isize = $isize[1];
       // decompression:
       $bodylen = $len-$headerlen-8;
       if ($bodylen < 1) {
           // IMPLEMENTATION BUG!
           return null;
       }
       $body = substr($data,$headerlen,$bodylen);
       $data = "";
       if ($bodylen > 0) {
           switch ($method) {
           case 8:
               // Currently the only supported compression method:
               $data = gzinflate($body,$maxlength);
               break;
           default:
               $error = "Unknown compression method.";
               return false;
           }
       }  // zero-byte body content is allowed
       // Verifiy CRC32
       $crc   = sprintf("%u",crc32($data));
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
    * @param $simplexml_to simplexml object source
    * @param $simplexml_from simplexml object destination
    *
    **/
   static function append_simplexml(&$simplexml_to, &$simplexml_from) {
      static $firstLoop=true;

      //Here adding attributes to parent
      if ($firstLoop) {
         foreach($simplexml_from->attributes() as $attr_key => $attr_value) {
            $simplexml_to->addAttribute($attr_key, $attr_value);
         }
      }
      foreach ($simplexml_from->children() as $simplexml_child) {
         $simplexml_temp = $simplexml_to->addChild($simplexml_child->getName(), (string)$simplexml_child);
         foreach ($simplexml_child->attributes() as $attr_key => $attr_value) {
            $simplexml_temp->addAttribute($attr_key, $attr_value);
         }
         $firstLoop=false;
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
               $xml->$key->$i = Toolbox::clean_cross_side_scripting_deep(Toolbox::addslashes_deep($value));
               $i++;
            } else {
               $xml->$key = Toolbox::clean_cross_side_scripting_deep(Toolbox::addslashes_deep($value));
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
               $sxml_authentication->addAttribute('USERNAME', $pfConfigSecurity->fields['username']);
               if ($pfConfigSecurity->fields['authentication'] == '0') {
//                  $sxml_authentication->addAttribute('AUTHPROTOCOL', '');
               } else {
                  $sxml_authentication->addAttribute('AUTHPROTOCOL',
                                 $pfConfigSecurity->getSNMPAuthProtocol($pfConfigSecurity->fields['authentication']));
               }
               $sxml_authentication->addAttribute('AUTHPASSPHRASE', $pfConfigSecurity->fields['auth_passphrase']);
               if ($pfConfigSecurity->fields['encryption'] == '0') {
//                  $sxml_authentication->addAttribute('PRIVPROTOCOL', '');
               } else {
                  $sxml_authentication->addAttribute('PRIVPROTOCOL',
                                 $pfConfigSecurity->getSNMPEncryption($pfConfigSecurity->fields['encryption']));
               }
               $sxml_authentication->addAttribute('PRIVPASSPHRASE', $pfConfigSecurity->fields['priv_passphrase']);
            } else {
               $sxml_authentication->addAttribute('COMMUNITY', $pfConfigSecurity->fields['community']);
            }
      }
   }



   /**
    * Add SNMO model strings to XML node 'MODEL'
    *
    * @param type $p_sxml_node
    * @param type $p_id
    */
   function addModel($p_sxml_node, $p_id) {
      $pfModel = new PluginFusioninventorySnmpmodel();
      $pfModelMib = new PluginFusioninventorySnmpmodelMib();

      $pfModel->getFromDB($p_id);
      $sxml_model = $p_sxml_node->addChild('MODEL');
         $sxml_model->addAttribute('ID', $p_id);
         $sxml_model->addAttribute('NAME', $pfModel->fields['name']);
         $pfModelMib->oidList($sxml_model,$p_id);
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


   
   // *********************** Functions used for inventory *********************** //
   

   static function diffArray($array1, $array2) {

      $a_return = array();
      foreach ($array1 as $key=>$value) {
         $key2 = false;
         $key2 = array_search($value, $array2, true);
         if ($key2) {
            unset($array2[$key2]);
         } else {
            $a_return[$key] = $value;
         }
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
            <td><input type="radio" value="1" id="yes'.$rand.'" name="'.$name.'" '.$checked['yes'].' />
            <label for="yes'.$rand.'">'.__('Yes').'</label></td>
		   	<td><input type="radio" value="0" id="no'.$rand.'" name="'.$name.'" '.$checked['no'].' />
            <label for="no'.$rand.'">'.__('No').'</label></td>
            </tr>
            </table>';
   }
}

?>
