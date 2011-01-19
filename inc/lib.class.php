<?php
/*
 * @version $Id$
 -------------------------------------------------------------------------
 FusionInventory
 Copyright (C) 2003-2010 by the INDEPNET Development Team.

 http://www.fusioninventory.org/   http://forge.fusioninventory.org/
 -------------------------------------------------------------------------

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
 --------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: David DURIEUX
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

/**
 * Class
 **/
class PluginFusinvinventoryLib extends CommonDBTM {

   function startAction($simpleXMLObj, $items_id, $new=0) {
      global $DB;
      
      if ($new == "0") {
      //if ($internalId = $this->isMachineExist()) {
         // Get internal ID with $items_id
         $query = "SELECT * FROM `glpi_plugin_fusinvinventory_libserialization`
            WHERE `external_id`='".$items_id."'
               LIMIT 1";
         if ($result = $DB->query($query)) {
            if ($DB->numrows($result) == 1) {
               $a_serialized = $DB->fetch_assoc($result);
            }
         }
         if (isset($a_serialized['external_id'])) {
            $internalId = $a_serialized['external_id'];
         } else {
            // Importer les donnes de GLPI dans le xml
         }

         //Sections update
         $xmlSections = $this->_getXMLSections($simpleXMLObj);
         $this->updateLibMachine($xmlSections, $internalId);
      } else {
         // New Computer

         //We launch CreateMachine() hook and provide an InternalId
         $xmlSections = $this->_getXMLSections($simpleXMLObj);
         $internalId = uniqid();

         try {
            $PluginFusinvinventoryLibhook = new PluginFusinvinventoryLibhook();
            $externalId = $PluginFusinvinventoryLibhook->createMachine("");

            $this->addLibMachine($internalId, $items_id);

            $this->updateLibMachine($xmlSections, $internalId);

         } catch (MyException $e) {
             // $log->error('created machine stage: error');
         }
      }
   }


   /**
   * get all sections with its name and data from XML file
   * @param simpleXML $simpleXMLObj
   * @return array $xmlSections (name and serialized data)
   */
   private function _getXMLSections($simpleXMLObj) {

      $xmlSections = array();

      $sectionsToFilter = array();
      array_push ($sectionsToFilter,
      'USBDEVICES',
      'CONTROLLERS',
      'NETWORKS');

//      DataFilter::init();

      foreach($simpleXMLObj->CONTENT->children() as $section) {

         if(in_array($section->getName(), $sectionsToFilter)) {
//            $nofilter = DataFilter::filter($section);
//            //if the folder for the filter doesn't exist, delete this element from array.
//            if($nofilter){
//               foreach($sectionsToFilter as $fKey => $fValue) {
//                  if ($fValue == $nofilter) {
//                     unset($sectionsToFilter[$fKey]);
//                  }
//               }
//            }
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
   * We create an entry for machine and store the externalId.
   * @param string $internalId
   * @param $externalId
   */
   public function addLibMachine($internalId, $externalId) {

      $this->lastId = $internalId;

      $data = <<<INFOCONTENT
      $externalId
INFOCONTENT;

      $queryInsert = "INSERT INTO `glpi_plugin_fusinvinventory_libserialization`
		( `internal_id` , `external_id` , `serialized_sections` , `hash` )
		VALUES
		( '" . $this->lastId . "' , '$data', NULL , NULL )";
      $resultInsert = mysql_query($queryInsert);

  }




   /**
   * Determine if there are sections changements and update
   * @param array $xmlSections
   * @param array $infoSections
   * @param int $internalId
   */
   public function updateLibMachine($xmlSections, $internalId) {
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
            if(in_array($sectionName, $this->_configs["sections"])) {
               foreach($sectionsToAdd as $arrayId => $serializedSectionToAdd) {
                  //check if we have the same section Name for an sectionToRemove and an sectionToAdd
                  if($xmlSections[$arrayId]['sectionName'] == $sectionName) {
                     //Finally, we have to determine if it's an update or not
                     $boolUpdate = false;
                     $arrSectionToAdd = unserialize($serializedSectionToAdd);
                     $arrSectionToRemove = unserialize($serializedSectionToRemove);

                     //TODO: Traiter les notices sur les indices de tableau qui n'existent pas.
                     switch($sectionName) {

                        case "DRIVES":
                           if ((((isset($arrSectionToAdd["SERIAL"]))
                                 AND ($arrSectionToAdd["SERIAL"] == $arrSectionToRemove["SERIAL"]))
                              OR ((isset($arrSectionToAdd['name']))
                                 AND ($arrSectionToAdd["NAME"] == $arrSectionToRemove["NAME"]))
                              OR ((isset($arrSectionToAdd['VOLUMN'])))
                              AND ($arrSectionToAdd["VOLUMN"] == $arrSectionToRemove["VOLUMN"]))) {

                              $boolUpdate = true;
                           }
                           break;
                           
                        case "SOFTWARES":
                           if($arrSectionToAdd["GUID"] == $arrSectionToRemove["GUID"] OR $arrSectionToAdd["NAME"] == $arrSectionToRemove["NAME"]) {
                  				$boolUpdate = true;
                           }
                           break;

                        case "CONTROLLERS":
                           if($arrSectionToAdd["PCIID"] == $arrSectionToRemove["PCIID"] AND $arrSectionToAdd["PCISLOT"] == $arrSectionToRemove["PCISLOT"]) {
                              $boolUpdate = true;
                           }
                           break;
                           
                        case "ENVS":
                           if($arrSectionToAdd["NAME"] == $arrSectionToRemove["NAME"]) {
                              $boolUpdate = true;
                           }
                           break;
                           
                        case "INPUTS":
                           if($arrSectionToAdd["CAPTION"] == $arrSectionToRemove["CAPTION"]) {
                              $boolUpdate = true;
                           }
                           break;

                        case "MEMORIES":
                           if($arrSectionToAdd["SERIALNUMBER"] == $arrSectionToRemove["SERIALNUMBER"]) {
                  				$boolUpdate = true;
                           }
                           break;
                           
                        case "MONITORS":
                           if($arrSectionToAdd["DESCRIPTION"] == $arrSectionToRemove["DESCRIPTION"]) {
                  				$boolUpdate = true;
                           }
                           break;
                           
                        case "NETWORKS":
                           if($arrSectionToAdd["MACADDR"] == $arrSectionToRemove["MACADDR"]) {
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
                           if ((isset($arrSectionToAdd["STARTED"]) AND ($arrSectionToAdd["STARTED"] == $arrSectionToRemove["STARTED"])) AND ($arrSectionToAdd["PID"] == $arrSectionToRemove["PID"])) {
                  				$boolUpdate = true;
                           }
                           break;
                           
                        case "SOUNDS":
                           if($arrSectionToAdd["NAME"] == $arrSectionToRemove["NAME"]) {
                              $boolUpdate = true;
                           }
                           break;
                           
                        case "STORAGES":
                           if($arrSectionToAdd["MODEL"] == $arrSectionToRemove["MODEL"] OR $arrSectionToAdd["SERIALNUMBER"] == $arrSectionToRemove["SERIALNUMBER"]) {
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
                           if ($arrSectionToAdd["SERIAL"] == $arrSectionToRemove["SERIAL"]
                                 OR ($arrSectionToAdd["CLASS"] == $arrSectionToRemove["CLASS"]
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
         $serializedSections .= "\t".$key."<<=>>".$serializedSection."
";
      }
      $externalId=$infoSections["externalId"];

      $data = <<<INFOCONTENT
      $externalId
      $serializedSections
INFOCONTENT;

      $queryUpdate = "UPDATE `glpi_plugin_fusinvinventory_libserialization`
		SET `serialized_sections` = \"" . htmlspecialchars($serializedSections) ."\", `hash` = '" . MD5($data) . "'
		WHERE `internal_id` = '" . $this->lastId . "'";

      $resultUpdate = mysql_query($queryUpdate);
   }


   
   /**
   * get all sections with its serialized datas,and sectionId from database
   * @param int $internalId
   * @return array $infoSections (serialized datas and sectionId)
   */
   private function _getInfoSections($internalId) {
      $infoSections = array();
      $infoSections["externalId"] = '';
      $infoSections["sections"] = array();
      $infoSections["sectionsToModify"] = array();

      /* Variables for the recovery and changes in the serialized sections */
      $serializedSections = "";
      $arraySerializedSections = array();
      $arraySerializedSectionsTemp = array();

      $querySelect = "SELECT `external_id`, `serialized_sections` FROM `glpi_plugin_fusinvinventory_libserialization` 
         WHERE `internal_id` = '$internalId'";
      $resultSelect = mysql_query($querySelect);
      $rowSelect = mysql_fetch_row($resultSelect);
      $infoSections["externalId"] = $rowSelect[0];
      $serializedSections = str_replace("\t", "", $rowSelect[1]); // To remove the indentation at beginning of line
	

      $serializedSections = htmlspecialchars_decode($serializedSections); // Recover double quotes
      $arraySerializedSections = explode("\n", $serializedSections); // Recovering a table with one line per entry
      foreach ($arraySerializedSections as $cle=>$valeur) {
         $arraySerializedSectionsTemp = explode("<<=>>", $valeur); // For each line, we create a table with data separated
         if ($arraySerializedSectionsTemp[0] != "" && $arraySerializedSectionsTemp[1] != "") { // that is added to infosections
            $infoSections["sections"][$arraySerializedSectionsTemp[0]] = $arraySerializedSectionsTemp[1];
         }
      }

      return $infoSections;
   }


}

?>