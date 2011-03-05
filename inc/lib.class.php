<?php

/*
   ----------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2011 by the FusionInventory Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org/
   ----------------------------------------------------------------------

   LICENSE

   This file is part of FusionInventory.

   FusionInventory is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 2 of the License, or
   any later version.

   FusionInventory is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with FusionInventory.  If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------
   Original Author of file: David DURIEUX
   Co-authors of file:
   Purpose of file:
   ----------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

class PluginFusinvinventoryLib extends CommonDBTM {

   var $table = "glpi_plugin_fusinvinventory_libserialization";


   /**
   * Sarting create or update computer and get Entity
   *
   * @param $simpleXMLObj simplexml xmlobject of the computer
   * @param $items_id interger id of the computer
   * @param $new bool if 1 create a new computer
   *
   * @return nothing
   *
   **/
   function startAction($simpleXMLObj, $items_id, $new=0) {
      global $DB;

      $_SESSION["plugin_fusinvinventory_entity"] = "0";
      $xml = simplexml_load_string($_SESSION['SOURCEXML'],'SimpleXMLElement', LIBXML_NOCDATA);
      // ** Get entity with rules
         $input_rules = array();
         if ((isset($xml->CONTENT->BIOS->SSN)) AND (!empty($xml->CONTENT->BIOS->SSN))) {
            $input_rules['serialnumber'] = $xml->CONTENT->BIOS->SSN;
         }
         if ((isset($xml->CONTENT->HARDWARE->NAME)) AND (!empty($xml->CONTENT->HARDWARE->NAME))) {
            $input_rules['name'] = $xml->CONTENT->HARDWARE->NAME;
         }
         if (isset($xml->CONTENT->NETWORKS)) {
            foreach($xml->CONTENT->NETWORKS as $network) {
               if ((isset($network->IPADDRESS)) AND (!empty($network->IPADDRESS))) {
                  $input_rules['ip'][] = $network->IPADDRESS;
               }
               if ((isset($network->IPSUBNET)) AND (!empty($network->IPSUBNET))) {
                  $input_rules['subnet'][] = $network->IPADDRESS;
               }
            }
         }
         if ((isset($xml->CONTENT->HARDWARE->USERDOMAIN)) AND (!empty($xml->CONTENT->HARDWARE->USERDOMAIN))) {
            $input_rules['domain'] = $xml->CONTENT->HARDWARE->USERDOMAIN;
         }
         if ((isset($xml->CONTENT->ACCOUNTINFO->KEYNAME)) AND ($xml->CONTENT->ACCOUNTINFO->KEYNAME == 'TAG')) {
            if (isset($xml->CONTENT->ACCOUNTINFO->KEYVALUE)) {
               $input_rules['tag'] = $xml->CONTENT->ACCOUNTINFO->KEYVALUE;
            }
         }

         $ruleEntity = new PluginFusinvinventoryRuleEntityCollection();
         $dataEntity = array ();
         $dataEntity = $ruleEntity->processAllRules($input_rules, array());
         if (isset($dataEntity['entities_id'])) {
            $_SESSION["plugin_fusinvinventory_entity"] = $dataEntity['entities_id'];
         } else {
            $_SESSION["plugin_fusinvinventory_entity"] = "0";
         }


      if ($new == "0") {
         // Transfer if entity is different
         $Computer = new Computer();
         $Computer->getFromDB($items_id);
         $PluginFusioninventoryConfig = new PluginFusioninventoryConfig();
         if ($Computer->getEntityID() != $_SESSION["plugin_fusinvinventory_entity"]) {
            $Transfer = new Transfer();
            // get value in Config ($config['transfers_id_auto'])
            $Transfer->getFromDB($PluginFusioninventoryConfig->getValue($_SESSION["plugin_fusinvinventory_moduleid"],
                    'transfers_id_auto'));

            $item_to_transfer = array("Computer" => array($items_id=>$items_id));

            $Transfer->moveItems($item_to_transfer, $_SESSION["plugin_fusinvinventory_entity"], $Transfer->fields);
         }
         // Transfer agent entity
         $PluginFusioninventoryAgent = new PluginFusioninventoryAgent();
         if ($agent_id = $PluginFusioninventoryAgent->getAgentWithComputerid($items_id)) {
            $PluginFusioninventoryAgent->getFromDB($agent_id);
            if ($PluginFusioninventoryAgent->getEntityID() != $_SESSION["plugin_fusinvinventory_entity"]) {
               $PluginFusioninventoryAgent->fields['entities_id'] = $_SESSION["plugin_fusinvinventory_entity"];
               $PluginFusioninventoryAgent->update($PluginFusioninventoryAgent->fields);
            }
         }

      //if ($internalId = $this->isMachineExist()) {
         // Get internal ID with $items_id
         $query = "SELECT * FROM `glpi_plugin_fusinvinventory_libserialization`
            WHERE `external_id`='".$items_id."'
               LIMIT 1";
         if ($result = $DB->query($query)) {
            if ($DB->numrows($result) == '1') {
               $a_serialized = $DB->fetch_assoc($result);
            }
         }
         if (isset($a_serialized['internal_id'])) {
            $internalId = $a_serialized['internal_id'];
         } else {
            // Importer les donnes de GLPI dans le xml
            $internalId = uniqid();
            $PluginFusinvinventoryInventory = new PluginFusinvinventoryInventory();
            $PluginFusinvinventoryInventory->createMachineInLib($items_id, $internalId);
         }

         // Link computer to agent FusionInventory
         $PluginFusioninventoryAgent = new PluginFusioninventoryAgent();
         $PluginFusioninventoryAgent->setAgentWithComputerid($items_id, $xml->DEVICEID);


         //Sections update
         $xmlSections = $this->_getXMLSections($simpleXMLObj);
         $this->updateLibMachine($xmlSections, $internalId);
         
         $PluginFusinvinventoryLibhook = new PluginFusinvinventoryLibhook();
         $PluginFusinvinventoryLibhook->writeXMLFusion($items_id);
      } else {
         // New Computer

         //We launch CreateMachine() hook and provide an InternalId
         $xmlSections = $this->_getXMLSections($simpleXMLObj);
         $internalId = uniqid();

         try {
            $PluginFusinvinventoryLibhook = new PluginFusinvinventoryLibhook();
            $PluginFusinvinventoryLibhook->createMachine($items_id);
            
            // Link computer to agent FusionInventory
            $PluginFusioninventoryAgent = new PluginFusioninventoryAgent();
            $PluginFusioninventoryAgent->setAgentWithComputerid($items_id, $xml->DEVICEID);

            $this->addLibMachine($internalId, $items_id);

            $this->updateLibMachine($xmlSections, $internalId);

         } catch (MyException $e) {
             // $log->error('created machine stage: error');
         }
      }
   }


   
   /**
   * get all sections and convert to php array
   *
   * @param $simpleXMLObj simplexml XML object of the computer
   *
   * @return array XML sections into an array
   *
   **/
   private function _getXMLSections($simpleXMLObj) {

      $xmlSections = array();
      $PluginFusinvinventoryLibfilter = new PluginFusinvinventoryLibfilter();

      $sectionsToFilter = array();
      array_push ($sectionsToFilter,
      'USBDEVICES',
      'CONTROLLERS',
      'NETWORKS');

      foreach($simpleXMLObj->CONTENT->children() as $section) {

         if(in_array($section->getName(), $sectionsToFilter)) {
            $nofilter = $PluginFusinvinventoryLibfilter->filter($section);
            //if the folder for the filter doesn't exist, delete this element from array.
            if($nofilter){
               foreach($sectionsToFilter as $fKey => $fValue) {
                  if ($fValue == $nofilter) {
                     unset($sectionsToFilter[$fKey]);
                  }
               }
            }
         }

         $sectionData = array();
         foreach ($section->children() as $data) {
            $sectionData[$data->getName()] = (string)$data;
         }

         //sectionId initialization, we will affect id after hook createSection return value.
         $serializedSectionData = serialize($sectionData);
         array_push($xmlSections, (array(
         "sectionId" => 0,
         "sectionName" => $section->getName(),
         "sectionDatawName" => $serializedSectionData.$section->getName(),
         "sectionData" => $serializedSectionData)));
      }
      return $xmlSections;
   }



   /**
   * Create computer into lib
   *
   * @param $internalId value uniq id for internal lib
   * @param $externalId integer id of the GLPI computer
   *
   * @return nothing
   *
   */
   public function addLibMachine($internalId, $externalId) {

      $queryInsert = "INSERT INTO `glpi_plugin_fusinvinventory_libserialization`
		( `internal_id` , `external_id`)
		VALUES
		( '" . $internalId . "' , '".$externalId."')";
      $resultInsert = mysql_query($queryInsert);
  }




   /**
   * Determine if there are sections changements and update
   * Definition of the existant criteria in each section to known
   * if can be update or if it's a new section
   *
   * @param $xmlSections array XML sections in a php array
   * @param $internalId value uniq id of internal lib
   *
   * @return nothing
   *
   */
   public function updateLibMachine($xmlSections, $internalId) {
      global $DB;

      $a_sections = array();
      $a_sections[] = "DRIVES";
      $a_sections[] = "SOFTWARES";
      $a_sections[] = "CONTROLLERS";
      $a_sections[] = "ENVS";
      $a_sections[] = "INPUTS";
      $a_sections[] = "MEMORIES";
      $a_sections[] = "MONITORS";
      $a_sections[] = "NETWORKS";
      $a_sections[] = "PORTS";
      $a_sections[] = "PRINTERS";
      $a_sections[] = "PROCESSES";
      $a_sections[] = "SOUNDS";
      $a_sections[] = "STORAGES";
      $a_sections[] = "USERS";
      $a_sections[] = "VIDEOS";
      $a_sections[] = "USBDEVICES";
      // Retrieve all sections stored in info file
      $infoSections = $this->_getInfoSections($internalId);
      // Retrieve all sections from xml file
      $serializedSectionsFromXML = array();
      
      foreach($xmlSections as $xmlSection) {
         array_push($serializedSectionsFromXML, $xmlSection["sectionDatawName"]);
      }

      //Retrieve changes, sections to Add and sections to Remove
      $sectionsToAdd = array_diff($serializedSectionsFromXML, $infoSections["sections"]);
      $sectionsToRemove = array_diff($infoSections["sections"], $serializedSectionsFromXML);

      $classhook = "PluginFusinvinventoryLibhook";

      //updated section: process
      if($sectionsToRemove && $sectionsToAdd) {
         $sectionsToAddTmp = array();
         $datasToUpdate = array();
         $existUpdate = 0;
         foreach($sectionsToRemove as $sectionId => $serializedSectionToRemove) {
            $sectionName=substr($infoSections["sections"][$sectionId], strpos($infoSections["sections"][$sectionId], '}')+1);
            if (in_array($sectionName, $a_sections)) {
               foreach($sectionsToAdd as $arrayId => $serializedSectionToAdd) {
                  //check if we have the same section Name for an sectionToRemove and an sectionToAdd
                  if($xmlSections[$arrayId]['sectionName'] == $sectionName) {
                     //Finally, we have to determine if it's an update or not
                     $boolUpdate = false;
if (!unserialize($serializedSectionToAdd)) {
   logInFile('serialise', $serializedSectionToAdd);
}
                     $arrSectionToAdd = unserialize($serializedSectionToAdd);
if (!unserialize($serializedSectionToRemove)) {
   logInFile('serialise', $serializedSectionToRemove);
}
                     $arrSectionToRemove = unserialize($serializedSectionToRemove);
                     
                     //TODO: Traiter les notices sur les indices de tableau qui n'existent pas.
                     switch($sectionName) {

                        case "DRIVES":
                           if (((isset($arrSectionToAdd["SERIAL"]))
                                 AND (isset($arrSectionToRemove["SERIAL"]))
                                 AND ($arrSectionToAdd["SERIAL"] == $arrSectionToRemove["SERIAL"]))
                              OR (((isset($arrSectionToAdd["NAME"]))
                                 AND (isset($arrSectionToRemove["NAME"]))
                                 AND ($arrSectionToAdd["NAME"] == $arrSectionToRemove["NAME"])))
                              OR ((isset($arrSectionToAdd['VOLUMN'])
                                 AND (isset($arrSectionToRemove["VOLUMN"]))
                                 AND ($arrSectionToAdd["VOLUMN"] == $arrSectionToRemove["VOLUMN"])))) {

                              $boolUpdate = true;
                           }
                           break;
                           
                        case "SOFTWARES":
                           if (((isset($arrSectionToAdd["GUID"]) AND isset($arrSectionToRemove["GUID"])
                                 AND ($arrSectionToAdd["GUID"] == $arrSectionToRemove["GUID"]))
                              OR (isset($arrSectionToAdd["NAME"]) AND isset($arrSectionToRemove["NAME"])
                                 AND $arrSectionToAdd["NAME"] == $arrSectionToRemove["NAME"]))
                              AND (isset($arrSectionToAdd["VERSION"]) AND isset($arrSectionToRemove["VERSION"])
                                 AND $arrSectionToAdd["VERSION"] == $arrSectionToRemove["VERSION"])) {

                              $boolUpdate = true;
                           }
                           break;

                        case "CONTROLLERS":
                           if (isset($arrSectionToAdd["PCIID"]) AND isset($arrSectionToRemove["PCIID"])
                                 AND $arrSectionToAdd["PCIID"] == $arrSectionToRemove["PCIID"]
                                 AND isset($arrSectionToAdd["PCISLOT"]) AND isset($arrSectionToRemove["PCISLOT"])
                                 AND $arrSectionToAdd["PCISLOT"] == $arrSectionToRemove["PCISLOT"]) {
                              $boolUpdate = true;
                           }
                           break;
                           
                        case "ENVS":
                           if(isset($arrSectionToAdd["NAME"]) AND isset($arrSectionToRemove["NAME"])
                                 AND $arrSectionToAdd["NAME"] == $arrSectionToRemove["NAME"]) {
                              $boolUpdate = true;
                           }
                           break;
                           
                        case "INPUTS":
                           if($arrSectionToAdd["CAPTION"] == $arrSectionToRemove["CAPTION"]) {
                              $boolUpdate = true;
                           }
                           break;

                        case "MEMORIES":
                           if (isset($arrSectionToAdd["SERIALNUMBER"])
                                 AND isset($arrSectionToRemove["SERIALNUMBER"])
                                 AND $arrSectionToAdd["SERIALNUMBER"] == $arrSectionToRemove["SERIALNUMBER"]) {
                  				$boolUpdate = true;
                           }
                           break;
                           
                        case "MONITORS":
                           if($arrSectionToAdd["DESCRIPTION"] == $arrSectionToRemove["DESCRIPTION"]) {
                  				$boolUpdate = true;
                           }
                           break;
                           
                        case "NETWORKS":
                           if(isset($arrSectionToAdd["MACADDR"]) AND isset($arrSectionToRemove["MACADDR"])
                                 AND $arrSectionToAdd["MACADDR"] == $arrSectionToRemove["MACADDR"]) {
                  				$boolUpdate = true;
                           }
                           break;
                           
                        case "PORTS":
                           if($arrSectionToAdd["CAPTION"] == $arrSectionToRemove["CAPTION"]) {
                     			$boolUpdate = true;
                           }
                           break;
                           
                        case "PRINTERS":
                           if($arrSectionToAdd["DESCRIPTION"] == $arrSectionToRemove["DESCRIPTION"] OR $arrSectionToAdd["PORT"] == $arrSectionToRemove["PORT"]) {
                  				$boolUpdate = true;
                           }
                           break;
                           
                        case "PROCESSES":
                           if ((isset($arrSectionToAdd["STARTED"])
                                 AND (isset($arrSectionToRemove["STARTED"]))
                                 AND ($arrSectionToAdd["STARTED"] == $arrSectionToRemove["STARTED"]))
                                 AND ($arrSectionToAdd["PID"] == $arrSectionToRemove["PID"])) {
                  				$boolUpdate = true;
                           }
                           break;
                           
                        case "SOUNDS":
                           if($arrSectionToAdd["NAME"] == $arrSectionToRemove["NAME"]) {
                              $boolUpdate = true;
                           }
                           break;
                           
                        case "STORAGES":
                           if((isset($arrSectionToAdd["MODEL"]) AND isset($arrSectionToRemove["MODEL"])
                                 AND $arrSectionToAdd["MODEL"] == $arrSectionToRemove["MODEL"])
                              OR (isset($arrSectionToAdd["SERIALNUMBER"]) AND isset($arrSectionToRemove["SERIALNUMBER"])
                                 AND $arrSectionToAdd["SERIALNUMBER"] == $arrSectionToRemove["SERIALNUMBER"])) {
                              $boolUpdate = true;
                           }
                           break;
                           
                        case "USERS":
                           if($arrSectionToAdd["LOGIN"] == $arrSectionToRemove["LOGIN"]) {
                              $boolUpdate = true;
                           }
                           break;
                           
                        case "VIDEOS":
                           if($arrSectionToAdd["NAME"] == $arrSectionToRemove["NAME"]) {
                              $boolUpdate = true;
                           }
                           break;
                           
                        case "USBDEVICES":
                           if ((isset($arrSectionToAdd["SERIAL"])
                                   AND isset($arrSectionToRemove["SERIAL"])
                                   AND $arrSectionToAdd["SERIAL"] == $arrSectionToRemove["SERIAL"])
                                OR (isset($arrSectionToAdd["CLASS"])
                                   AND isset($arrSectionToRemove["CLASS"])
                                   AND $arrSectionToAdd["CLASS"] == $arrSectionToRemove["CLASS"]
                                   AND $arrSectionToAdd["PRODUCTID"] == $arrSectionToRemove["PRODUCTID"]
                                   AND $arrSectionToAdd["SUBCLASS"] == $arrSectionToRemove["SUBCLASS"]
                                   AND $arrSectionToAdd["VENDORID"] == $arrSectionToRemove["VENDORID"])) {

                              $boolUpdate = true;
                           }
                           break;

                        default:
                           break;

                     }

                     if ($boolUpdate) {
                        //Then we update this section
                        $infoSections["sections"][$sectionId] = $serializedSectionToAdd;

                        //Delete this section from sectionToRemove and sectionToAdd
                        unset($sectionsToRemove[$sectionId]);
                        unset($sectionsToAdd[$arrayId]);

                        array_push($datasToUpdate, array(
                                     "sectionId"=>$sectionId,
                                     "dataSection"=>$xmlSections[$arrayId]['sectionData']));

                        $existUpdate++;
                     } else {
                        //push element into an temporary array, to allow update transposition
                        $sectionsToAddTmp[$arrayId] = $sectionsToAdd[$arrayId];
                        unset($sectionsToAdd[$arrayId]);
                     }
                     break;
                  }
               }
            }
         }
         if ($existUpdate) {
            call_user_func(array($classhook,"updateSections"),
                           $datasToUpdate,
                           $infoSections["externalId"]);
         }

         if (!empty($sectionsToAddTmp)) {
            //Retrieve removed data in sectionsToAdd
            foreach($sectionsToAddTmp as $k => $v) {
               $sectionsToAdd[$k] = $v;
            }
            ksort($sectionsToAdd);
         }
      }

      if ($sectionsToRemove) {
         $sectionsIdToRemove = array();
      	foreach($sectionsToRemove as $sectionId => $serializedSection) {
            unset($infoSections["sections"][$sectionId]);
            array_push($sectionsIdToRemove, $sectionId);
         }

         call_user_func(array($classhook,"removeSections"),
		       $sectionsIdToRemove,
		       $infoSections["externalId"]);
      }
      if ($sectionsToAdd) {
         $datasToAdd = array();

         //format data to send to hook createSection
         foreach($sectionsToAdd as $arrayId => $serializedSection) {
            array_push($datasToAdd, array(
					  "sectionName"=>$xmlSections[$arrayId]['sectionName'],
					  "dataSection"=>$xmlSections[$arrayId]['sectionData']));
         }

         $sectionsId = call_user_func(array($classhook,"addSections"),
				     $datasToAdd,
				     $infoSections["externalId"]);

         $infoSectionsId = array();

         foreach($infoSections["sections"] as $sId => $serializedSection) {
            array_push($infoSectionsId,$sId);
         }

         $allSectionsId = array_merge(
				     $infoSectionsId,
				     $sectionsId);

         $infoSections["sections"] = array_merge (
						 $infoSections["sections"],
						 $sectionsToAdd);
         if ((count($allSectionsId)) != (count($infoSections["sections"]))) {

         }
         $infoSections["sections"] = array_combine($allSectionsId, $infoSections["sections"]);
      }

      $serializedSections = "";
      foreach($infoSections["sections"] as $key => $serializedSection) {
         $serializedSections .= $key."<<=>>".$serializedSection."
";
      }
      //$externalId=$infoSections["externalId"];

      $this->_serializeIntoDB($internalId, $serializedSections);
   }



   /**
   * Update/insert sections into lib DB
   * Must be inserted into many yimes because default configuration of MySQL
   * can only insert 1 Mo of datas and sometimes we have more than this
   *
   * @param $internalId value uniq id of internal lib
   * @param $serializedSections value XML sections serialized
   *
   * @return nothing
   *
   */
   private function _serializeIntoDB($internalId, $serializedSections) {
      global $DB;


      $serializedSections = str_replace("\\", "\\\\", $serializedSections);
      $a_serializedSections = str_split(htmlspecialchars($serializedSections, ENT_QUOTES), 800000);

      $queryUpdate = "UPDATE `glpi_plugin_fusinvinventory_libserialization`
		SET `serialized_sections1` = '" . $a_serializedSections[0] ."'
      WHERE `internal_id` = '" . $internalId . "'";

      $resultUpdate = $DB->query($queryUpdate);

      if (isset($a_serializedSections[1])) {
         $queryUpdate = "UPDATE `glpi_plugin_fusinvinventory_libserialization`
         SET `serialized_sections2` = '" . $a_serializedSections[1] ."'
         WHERE `internal_id` = '" . $internalId . "'";
      } else {
         $queryUpdate = "UPDATE `glpi_plugin_fusinvinventory_libserialization`
         SET `serialized_sections2` = ''
         WHERE `internal_id` = '" . $internalId . "'";
      }
      $resultUpdate = $DB->query($queryUpdate);

      if (isset($a_serializedSections[2])) {
        $queryUpdate = "UPDATE `glpi_plugin_fusinvinventory_libserialization`
         SET `serialized_sections3` = '" . $a_serializedSections[2] ."'
         WHERE `internal_id` = '" . $internalId . "'";
      } else {
         $queryUpdate = "UPDATE `glpi_plugin_fusinvinventory_libserialization`
         SET `serialized_sections3` = ''
         WHERE `internal_id` = '" . $internalId . "'";
      }
      $resultUpdate = $DB->query($queryUpdate);      
   }


   
   /**
   * get all sections with its serialized datas,and sectionId from database
   *
   * @param $internalId value uniq id of internal lib
   *
   * @return array $infoSections (serialized datas and sectionId)
   */
   function _getInfoSections($internalId) {
      global $DB;

      $infoSections = array();
      $infoSections["externalId"] = '';
      $infoSections["sections"] = array();
      $infoSections["sectionsToModify"] = array();

      /* Variables for the recovery and changes in the serialized sections */
      $serializedSections = "";
      $arraySerializedSections = array();
      $arraySerializedSectionsTemp = array();

      $querySelect = "SELECT `external_id`, `serialized_sections1`, `serialized_sections2`, `serialized_sections3` FROM `glpi_plugin_fusinvinventory_libserialization`
         WHERE `internal_id` = '$internalId'";
      $resultSelect = $DB->query($querySelect);
      $rowSelect = mysql_fetch_row($resultSelect);
      $infoSections["externalId"] = $rowSelect[0];
      $serializedSections = htmlspecialchars_decode($rowSelect[1].$rowSelect[2].$rowSelect[3], ENT_QUOTES); // Recover double quotes
//      $serializedSections = str_replace("\t", "", $serializedSections); // To remove the indentation at beginning of line
      $arraySerializedSections = explode("\n", $serializedSections); // Recovering a table with one line per entry
      foreach ($arraySerializedSections as $valeur) {
         $arraySerializedSectionsTemp = explode("<<=>>", $valeur); // For each line, we create a table with data separated
         if (isset($arraySerializedSectionsTemp[0]) AND isset($arraySerializedSectionsTemp[1])) {
            if ($arraySerializedSectionsTemp[0] != "" && $arraySerializedSectionsTemp[1] != "") { // that is added to infosections
               $infoSections["sections"][$arraySerializedSectionsTemp[0]] = $arraySerializedSectionsTemp[1];
            }
         }
      }
      return $infoSections;
   }



   /**
   * Unserialize sections
   *
   * @param $sObject value datas serialized
   *
   * @return array each sections into an array
   */
   function __unserialize($sObject) {
      $__ret =preg_replace('!s:(\d+):"(.*?)";!e', "'s:'.strlen('$2').':\"$2\";'", $sObject );
      return unserialize($__ret);
   }



   /**
   * When purge computer, we delete entry in lib DB
   *
   * @param $external_id integer GLPI id of the computer
   *
   * @return nothing
   */
   function removeExternalid($external_id) {
      global $DB;

      $query_delete = "DELETE FROM `glpi_plugin_fusinvinventory_libserialization`
         WHERE `external_id`='".$external_id."' ";
      $DB->query($query_delete);
   }



   /**
   * Add existant GLPI computer into lib before update it
   *
   * @param $items_id integer id of GLPI Computer
   * @param $internal_id value uniq id of the computer in lib
   * @param $simpleXMLObj simplexml XML of the Computer
   * @param $a_sectionsinfos
   *
   * @return nothing
   */
   function addLibMachineFromGLPI($items_id, $internal_id, $simpleXMLObj, $a_sectionsinfos) {
      $this->addLibMachine($internal_id, $items_id);

      $xmlSections = $this->_getXMLSections($simpleXMLObj);

      $serializedSectionsFromXML = array();

      foreach($xmlSections as $xmlSection) {
         array_push($serializedSectionsFromXML, $xmlSection["sectionDatawName"]);
      }

      $serializedSections = "";
      foreach($serializedSectionsFromXML as $key => $serializedSection) {
         $serializedSections .= array_shift($a_sectionsinfos)."<<=>>".$serializedSection."
";
      }
      $this->_serializeIntoDB($internal_id, $serializedSections);
   }
}

?>