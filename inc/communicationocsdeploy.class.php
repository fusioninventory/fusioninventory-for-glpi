<?php
/*
 * @version $Id$
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2010 by the INDEPNET Development Team.

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
// Purpose of file: management of communication with agents
// ----------------------------------------------------------------------
/**
 * The datas are XML encoded and compressed with Zlib.
 * XML rules :
 * - XML tags in uppercase
 **/

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

require_once GLPI_ROOT.'/plugins/fusinvsnmp/inc/communicationsnmp.class.php';

/**
 * Class to communicate with agents using XML
 **/
class PluginFusinvdeployCommunicationOcsdeploy extends PluginFusinvsnmpCommunicationSNMP {
//   private $sxml, $deviceId, $ptd, $type='', $logFile;


   /**
    * Import data
    *
    *@param $p_DEVICEID XML code to import
    *@param $p_CONTENT XML code to import
    *@return "" (import ok) / error string (import ko)
    **/
   function import($p_DEVICEID, $p_CONTENT, $p_xml) {
      global $DB, $LANG;

      $PluginFusioninventoryTaskjobstatus = new PluginFusioninventoryTaskjobstatus;
      $PluginFusioninventoryAgent = new PluginFusioninventoryAgent;

      PluginFusioninventoryCommunication::addLog(
              'Function PluginFusinvdeployCommunicationOcsdeploy->import().');
//      $this->setXML($p_CONTENT);
      $this->sxml = @simplexml_load_string($p_xml);
      $errors = '';

      if (isset($this->sxml->ERR)) {
         $errors = $this->sxml->ERR;
      }

      $agent = $PluginFusioninventoryAgent->InfosByKey($p_DEVICEID);

      $query = "SELECT `glpi_plugin_fusioninventory_taskjobstatus`.* FROM `glpi_plugin_fusioninventory_taskjobstatus`
               LEFT JOIN `glpi_plugin_fusioninventory_taskjobs`
                  on `plugin_fusioninventory_taskjobs_id` = `glpi_plugin_fusioninventory_taskjobs`.`id`

               WHERE `glpi_plugin_fusioninventory_taskjobstatus`.`plugin_fusioninventory_agents_id`='".$agent['id']."'
                  AND `itemtype`='Computer'
                  AND `state`='0'
                  AND `method`='ocsdeploy'
                  AND `argument`='".$this->sxml->ID."'
               LIMIT 1"; //TODO : state search for 1 and not 0
      if ($result = $DB->query($query)) {
         if ($DB->numrows($result) != 0) {
            $a_taskjobstatus = $DB->fetch_assoc($result);


            $is_error = 0;
            if ($errors != '') {
               $is_error = 1;

               /*
                * Errors messages
                *
                * ERR_CLEAN
                * ERR_EXECUTE
                * ERR_DOWNLOAD_PACK
                * ERR_BUILD
                * ERR_BAD_DIGEST
                * ERR_ALREADY_SETUP
                * ERR_DOWNLOAD_INFO
                */

               switch($errors) {

                  case 'ERR_CLEAN':
                     $errors .= " : fail to clean fiels of package";
                     break;

                  case 'ERR_EXECUTE':
                     $errors .= " : fail to uncompress or to run package";
                     break;

                  case 'ERR_DOWNLOAD_PACK':
                     $errors .= " : unable to download fragments of package";
                     break;

                  case 'ERR_BUILD';
                     // TODO : ???
                     break;

                  case 'ERR_BAD_DIGEST':
                     // TODO : ???
                     break;

                  case 'ERR_ALREADY_SETUP':
                     $errors .= ' : this package is yet installed';
                     break;

                  case 'ERR_DOWNLOAD_INFO':
                     $errors .= 'Unable to contact server and get informations of package';
                     break;
                  
               }
            }

            // Update taskjobstatus (state = 3 : finish);
            $PluginFusioninventoryTaskjobstatus->changeStatusFinish(
                           $a_taskjobstatus['plugin_fusioninventory_taskjobs_id'],
                           $a_taskjobstatus['items_id'],
                           'Computer',
                           $is_error,
                           $errors,
                           0);

         }
      }
      

      return $errors;
   }
}

?>