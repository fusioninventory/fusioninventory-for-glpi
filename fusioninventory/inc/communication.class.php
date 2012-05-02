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
   @author    Vincent Mazzoni
   @co-author David Durieux
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

/**
 * Class to communicate with agents using XML
 **/
class PluginFusioninventoryCommunication {
   private $deviceId, $ptd, $type='';
   protected $sxml;

   
   function __construct() {
      $this->sxml = new SimpleXMLElement("<?xml version='1.0' encoding='UTF-8'?><REPLY></REPLY>");
      PluginFusioninventoryCommunication::addLog('New PluginFusioninventoryCommunication object.');
   }


   
   /**
    * Get readable XML code (add carriage returns)
    *
    * @return readable XML code
    **/
   function getXML() {
      return $this->formatXmlString();
   }


   
   /**
    * Set XML code
    *
    * @param $p_xml XML code
    * 
    * @return nothing
    **/
   function setXML($p_xml) {
      $this->sxml = @simplexml_load_string($p_xml,'SimpleXMLElement', 
                                           LIBXML_NOCDATA); // @ to avoid xml warnings
   }


   
   /**
    * Get XML code
    *
    * @return XML code
    **/
   function get() {
      if ($GLOBALS["HTTP_RAW_POST_DATA"] == '') {
         return '';
      } else {
         return gzuncompress($GLOBALS["HTTP_RAW_POST_DATA"]);
      }
   }


   
   /**
    * Get data ready to be send (gzcompressed)
    * 
    * @return data ready to be send
    **/
   function getSend($compressmode = 'none') {
      switch($compressmode) {
         
         case 'none':
            return $this->sxml->asXML();
            break;
         
         case 'gzcompress':
            return gzcompress($this->sxml->asXML());
            break;
         
         case 'gzencode':
            return gzencode($this->sxml->asXML());
            break;
         
         case 'gzdeflate':
            return gzdeflate($this->sxml->asXML());
            break;
         
      }
      return $this->sxml->asXML();
   }


  
   /**
    * Import data
    *
    * @param $p_xml XML code to import
    * @param &$p_errors errors string to be alimented if import ko
    * 
    * @return true (import ok) / false (import ko)
    **/
   function import($p_xml, &$p_errors='') {
      global $LANG;
      
      $PluginFusioninventoryAgentmodule = new PluginFusioninventoryAgentmodule();
      $PluginFusioninventoryAgent = new PluginFusioninventoryAgent();

      PluginFusioninventoryCommunication::addLog('Function import().');
      // TODO : gérer l'encodage, la version
      // Do not manage <REQUEST> element (always the same)
      
      $_SESSION["plugin_fusioninventory_disablelocks"] = 1;
      $this->sxml = $p_xml;
      $errors = '';

      $xmltag = (string)$this->sxml->QUERY;
      $agent = $PluginFusioninventoryAgent->InfosByKey($this->sxml->DEVICEID);
      if ($xmltag == "PROLOG") {
         return false;
      }
      if (!isset($agent['id'])) {
         return true;
      }

      if (isset($this->sxml->CONTENT->MODULEVERSION)) {

         $PluginFusioninventoryAgent->setAgentVersions($agent['id'], $xmltag, (string)$this->sxml->CONTENT->MODULEVERSION);
      } else if (isset($this->sxml->CONTENT->VERSIONCLIENT)) {
         $version = str_replace("FusionInventory-Agent_", "", (string)$this->sxml->CONTENT->VERSIONCLIENT);

         $PluginFusioninventoryAgent->setAgentVersions($agent['id'], $xmltag, $version);
      }


      if (!$PluginFusioninventoryAgentmodule->getAgentCanDo($xmltag, $agent['id'])) {
         return true;
      }

      if (isset($_SESSION['glpi_plugin_fusioninventory']['xmltags']["$xmltag"])) {
         $moduleClass = $_SESSION['glpi_plugin_fusioninventory']['xmltags']["$xmltag"];
         $moduleCommunication = new $moduleClass();
         $errors.=$moduleCommunication->import($this->sxml->DEVICEID, 
                 $this->sxml->CONTENT, 
                 $p_xml);
      } else {
         $errors.=$LANG['plugin_fusioninventory']['errors'][22].' QUERY : *'.$xmltag."*\n";
      }
      $result=true;
      if ($errors != '') {
         echo $errors;
         if (isset($_SESSION['glpi_plugin_fusioninventory_processnumber'])) {
            $result=true;
         } else {
            // It's PROLOG
            $result=false;
         }
      }
      return $result;
   }


   
   /**
    * Add indent in XML to have nice XML format
    *
    * @return XML
    **/
   function formatXmlString() {
      $xml = str_replace("><", ">\n<", $this->sxml->asXML());
      $token      = strtok($xml, "\n");
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

         $line    = str_pad($token, strlen($token)+$pad, '  ', STR_PAD_LEFT);
         $result .= $line . "\n";
         $token   = strtok("\n");
         $pad    += $indent;
         $indent = 0;
      }
      $this->setXML($result);
      return $this->sxml->asXML();
   }



   /**
    * Return error to agent because SSL is required
    *
   **/
   function noSSL($compressmode) {
      $this->sxml->addAttribute('RESPONSE', "ERROR : SSL REQUIRED BY SERVER");
      $this->setXML($this->getXML());
      echo $this->getSend($compressmode);
   }


   
   /**
    * Return an empty answer to agent if nothing to import
    *
   **/
   function emptyAnswer($compressmode) {
      $this->setXML($this->getXML());
      echo $this->getSend($compressmode);
   }


   
   /**
    * Add logs
    *
    * @param $p_logs logs to write
    * 
    * @return nothing (write text in log file)
    **/
   static function addLog($p_logs) {

      if ($_SESSION['glpi_use_mode']==DEBUG_MODE) {
         if (PluginFusioninventoryConfig::isExtradebugActive()) {
            file_put_contents(GLPI_LOG_DIR.'/pluginFusioninventory-communication.log',
                              "\n".time().' : '.$p_logs,
                              FILE_APPEND);
         }
      }
   }

   

   /**
    * Get all tasks prepared for this agent
    *
    * @param $agent_id interger id of agent
    *
    **/
   function getTaskAgent($agent_id) {

      $PluginFusioninventoryTaskjobstatus = new PluginFusioninventoryTaskjobstatus();
      $moduleRun = $PluginFusioninventoryTaskjobstatus->getTaskjobsAgent($agent_id);
      foreach ($moduleRun as $className => $array) {
         if (class_exists($className)) {
            if ($className != "PluginFusinvinventoryESX") {
               $class = new $className();
               $sxml_temp = $class->run($array);
               $this->append_simplexml($this->sxml, $sxml_temp);
            }
         }
      }
   }

   

   /**
    * Set prolog for agent
    *
    **/
   function addProlog() {
      $PluginFusioninventoryConfig = new PluginFusioninventoryConfig();
      $plugins_id = PluginFusioninventoryModule::getModuleId('fusioninventory');
      $this->sxml->addChild('PROLOG_FREQ', $PluginFusioninventoryConfig->getValue($plugins_id, "inventory_frequence"));
   }



   /**
    * order to agent to do inventory if module inventory is activated for this agent
    *
    * @param $items_id interger Id of this agent
    *
    **/
   function addInventory($items_id) {
      $PluginFusioninventoryAgentmodule = new PluginFusioninventoryAgentmodule();
      if ($PluginFusioninventoryAgentmodule->getAgentCanDo('INVENTORY', $items_id)) {
         $this->sxml->addChild('RESPONSE', "SEND");
      }
   }


   
   /**
    * Function used to merge 2 simpleXML
    *
    * @param $simplexml_to simplexml object source
    * @param $simplexml_from simplexml object destination
    *
    **/
   function append_simplexml(&$simplexml_to, &$simplexml_from) {
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
         $this->append_simplexml($simplexml_temp, $simplexml_child);
      }
      unset($firstLoop);
   }

   
   
   /**
    * Tells if fopen is allowed or not on this server
    * 
    * @return true if allowed, false if not
    */
   static function isFopenAllowed() {
      return ini_get('allow_url_fopen');
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
   function gzdecode($data,&$filename='',&$error='',$maxlength=null) {
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
               $xml->$key->$i = clean_cross_side_scripting_deep(addslashes_deep($value));
               $i++;
            } else {
               $xml->$key = clean_cross_side_scripting_deep(addslashes_deep($value));
            }
         }
      }
      return $xml;
   }      
}

?>