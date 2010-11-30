<?php
require_once dirname(__FILE__) . '/StorageInventory.class.php';
if (!defined('LIBSERVERFUSIONINVENTORY_STORAGELOCATION')) {
   define("LIBSERVERFUSIONINVENTORY_STORAGELOCATION",dirname(__FILE__)."/../../../".$configs['storageLocation']);
}
class DirectoryStorageInventory extends StorageInventory
{

    public function __construct($applicationName, $configs, $simpleXMLData)
    {

        $this->_configs=$configs;
        $this->_applicationName=$applicationName;

        $this->_possibleCriterias = array(
        "motherboardSerial" => $simpleXMLData->CONTENT->BIOS->MOTHERBOARDSERIAL,
        "assetTag" => $simpleXMLData->CONTENT->BIOS->ASSETTAG,
        "msn" => $simpleXMLData->CONTENT->BIOS->MSN,
        "ssn" => $simpleXMLData->CONTENT->BIOS->SSN,
        "baseboardSerial" => $simpleXMLData->CONTENT->BIOS->BASEBOARDSERIAL,
        "macAddress" => $simpleXMLData->CONTENT->NETWORKS,
        "uuid" => $simpleXMLData->CONTENT->HARDWARE->UUID,
        "winProdKey" => $simpleXMLData->CONTENT->HARDWARE->WINPRODKEY,
        "biosSerial" => $simpleXMLData->CONTENT->BIOS->BIOSSERIAL,
        "enclosureSerial" => $simpleXMLData->CONTENT->BIOS->ENCLOSURESERIAL,
        "smodel" => $simpleXMLData->CONTENT->BIOS->SMODEL,
        "storagesSerial" => $simpleXMLData->CONTENT->STORAGES,
        "drivesSerial" => $simpleXMLData->CONTENT->DRIVES,
        "name" => $simpleXMLData->CONTENT->HARDWARE->NAME);
    }

    /**
    * We look for the machine with the relevant criterias defined by user, if it doesn't exist, return false; else return internalId.
    * @return bool false or internalId
    */
    public function isMachineExist()
    {
        $falseCriteriaNb=-1;
        $internalId;

        foreach($this->_configs["criterias"] as $criteria)
        {

            if($falseCriteriaNb >= $this->_configs["maxFalse"])
            {
                return false;
            }

            foreach($this->_possibleCriterias as $criteriaName => $criteriaValue)
            {
                if ($criteria == $criteriaName)
                {
                    if ($criteriaValue)
                    {
                        switch($criteria)
                        {
                            case "drivesSerial":
                            foreach($criteriaValue as $drives)
                            {
                                if ($drives->SYSTEMDRIVE==1)
                                {
                                    if (file_exists($this->_getCriteriaDSN($criteria, $drives->SERIAL)))
                                    {
                                        $internalId = scandir($this->_getCriteriaDSN($criteria, $drives->SERIAL));
                                    } else {
                                        $falseCriteriaNb++;
                                    }
                                }
                            }
                            break;

                            case "storagesSerial":
                            foreach($criteriaValue as $storages)
                            {
                                if ($storages->TYPE=="disk")
                                {
                                    if (file_exists($this->_getCriteriaDSN($criteria, $storages->SERIAL)))
                                    {
                                        $internalId = scandir($this->_getCriteriaDSN($criteria, $storages->SERIAL));
                                    } else {
                                        $falseCriteriaNb++;
                                    }
                                }
                            }
                            break;

                            case "macAddress":
                            foreach($criteriaValue as $networks)
                            {
                                if ($networks->VIRTUALDEV!=1)
                                {
                                    if (file_exists($this->_getCriteriaDSN($criteria, $networks->MACADDR)))
                                    {
                                        $internalId = scandir($this->_getCriteriaDSN($criteria, $networks->MACADDR));
                                    } else {
                                        $falseCriteriaNb++;
                                    }
                                }
                            }
                            break;

                            default:
                            if (file_exists($this->_getCriteriaDSN($criteria, $criteriaValue)))
                            {
                                $internalId = scandir($this->_getCriteriaDSN($criteria, $criteriaValue));
                            } else {
                                $falseCriteriaNb++;
                            }
                            break;

                        }
                    }
                }
            }
        }
        if($falseCriteriaNb >= $this->_configs["maxFalse"])
        {
            return false;
        }
        if (isset($internalId[2]))
        {
            return $internalId[2];
        }
        else {
            throw new MyException ("no avalaible criterias to compare");
        }


    }

    /**
    * We create directory tree for machine and store the externalId within INI file.
    * @param string $internalId
    * @param $externalId
    */
    public function addLibMachine($internalId, $externalId)
    {
        $infoPath = $this->_getInfoPathDSN($internalId);

        if(!is_dir($infoPath))
        {
            mkdir($infoPath,0777,true);
        }
        if (!file_exists($infoPath."/infos.file"))
        {
            $infoFile = fopen($infoPath."/infos.file","w");
            fclose($infoFile);
        }

        $data = <<<INFOCONTENT
$externalId
INFOCONTENT;

        file_put_contents($infoPath."/infos.file", $data);

    }

    /**
    * We create directory tree for criteria and internalId.
    * @param string $internalId
    */
    public function addLibCriteriasMachine($internalId)
    {
        $criteriasPathList = array();
        foreach($this->_possibleCriterias as $criteriaName => $criteriaValue)
        {
            if ($criteriaValue)
            {
                switch($criteriaName)
                {
                    case "drivesSerial":
                    foreach($criteriaValue as $drives)
                    {
                        if ($drives->SYSTEMDRIVE==1)
                        {
                            $criteriaPath = $this->_getCriteriaDSN($criteriaName, $drives->SERIAL);

                            $internalIdPath = sprintf('%s/%s',
                            $criteriaPath,
                            $internalId);

                            mkdir($internalIdPath,0777,true);

                            array_push($criteriasPathList, $criteriaPath);
                        }
                    }
                    break;

                    case "storagesSerial":
                    foreach($criteriaValue as $storages)
                    {
                        if (($storages->TYPE=="disk") AND (isset($storages->SERIAL)))
                        {
                            $criteriaPath = $this->_getCriteriaDSN($criteriaName, $storages->SERIAL);

                            $internalIdPath = sprintf('%s/%s',
                            $criteriaPath,
                            $internalId);

                            mkdir($internalIdPath,0777,true);

                            array_push($criteriasPathList, $criteriaPath);
                        }
                    }
                    break;

                    case "macAddress":
                    foreach($criteriaValue as $networks)
                    {
                        if ($networks->VIRTUALDEV!=1)
                        {
                            $criteriaPath = $this->_getCriteriaDSN($criteriaName, $networks->MACADDR);
                            $internalIdPath = sprintf('%s/%s',
                            $criteriaPath,
                            $internalId);

                            if (!is_dir($internalIdPath)) {
                               mkdir($internalIdPath,0777,true);
                            }

                            array_push($criteriasPathList, $criteriaPath);
                        }
                    }
                    break;

                    default:
                    $criteriaPath = $this->_getCriteriaDSN($criteriaName, $criteriaValue);

                    $internalIdPath = sprintf('%s/%s',
                    $criteriaPath,
                    $internalId);

                    mkdir($internalIdPath,0777,true);

                    array_push($criteriasPathList, $criteriaPath);

                    break;

                }
            }
        }

        $infoPath = $this->_getInfoPathDSN($internalId);

        //criterias file, it will allow to remove criterias's machine
        if (!file_exists($infoPath."/criterias"))
        {
            $criteriasFile = fopen($infoPath."/criterias","w");
            fclose($criteriasFile);
            file_put_contents($infoPath."/criterias", implode(",",$criteriasPathList));
        }
    }

    /**
    * Determine data source name of criterias
    * @param string $criteriaName
    * @param string $criteriaValue
    * @return string $dsn
    */
    private function _getCriteriaDSN($criteriaName, $criteriaValue)
    {
        $dsn = sprintf('%s/%s/%s/%s/%s',
        LIBSERVERFUSIONINVENTORY_STORAGELOCATION,
        "criterias",
        $criteriaName,
        $this->_applicationName,
        $criteriaValue);
        return $dsn;
    }

    /**
    * Determine data source name of machine
    * @param string $internalId
    * @return string $dsn
    */
    private function _getInfoPathDSN($internalId)
    {
        $dsn = sprintf('%s/%s/%s',
        LIBSERVERFUSIONINVENTORY_STORAGELOCATION,
        "machines",
        $internalId);
        return $dsn;
    }

    /**
    * get all sections with its serialized datas,and sectionId from info file
    * @param int $internalId
    * @return array $infoSections (serialized datas and sectionId)
    */
    private function _getInfoSections($internalId)
    {
        $infoPath = $this->_getInfoPathDSN($internalId);

        try
        {
            $infoFileHandler = fopen($infoPath."/infos.file","r");

        } catch (MyException $e) {
            echo 'Opening: error info file';
        }

        $infoSections = array();
        $infoSections["externalId"] = '';
        $infoSections["sections"] = array();
        $infoSections["sectionsToModify"] = array();

        while ( ($buffer = fgets($infoFileHandler, 4096)) !== false )
        {

            $stack = array();
            if (preg_match("/^\t(.+)/i", $buffer, $stack))
            {
                $sectionArray= explode('<<=>>', $stack[1]);
                $infoSections["sections"][$sectionArray[0]] = $sectionArray[1];

            }
            else if (($buffer) AND (!empty($infoSections["externalId"])))
            {
               $buffer = str_replace("\n","",$buffer);
               $infoSections["sections"][$sectionArray[0]] .= $buffer;
            }
            else if (($buffer) AND (empty($infoSections["externalId"])))
            {
                $infoSections["externalId"]= trim($buffer);
            }
        }
        fclose($infoFileHandler);
        return $infoSections;

    }

    /**
    * Determine if there are sections changements and update
    * @param array $xmlSections
    * @param array $infoSections
    * @param int $internalId
    */
    public function updateLibMachine($xmlSections, $internalId)
    {
        $log = new Logger();
        // Retrieve all sections stored in info file
        $infoSections = $this->_getInfoSections($internalId);
        // Retrieve all sections from xml file
        $serializedSectionsFromXML = array();

        foreach($xmlSections as $xmlSection)
        {
            array_push($serializedSectionsFromXML, $xmlSection["sectionDatawName"]);
        }

        //Retrieve changes, sections to Add and sections to Remove
        $sectionsToAdd = array_diff($serializedSectionsFromXML, $infoSections["sections"]);
        $sectionsToRemove = array_diff($infoSections["sections"], $serializedSectionsFromXML);

        $classhook = LIBSERVERFUSIONINVENTORY_HOOKS_CLASSNAME;

        //updated section: process
        if($sectionsToRemove && $sectionsToAdd)
        {
            $sectionsToAddTmp = array();
            $datasToUpdate = array();
            $existUpdate = 0;
            foreach($sectionsToRemove as $sectionId => $serializedSectionToRemove)
            {
                $sectionName=substr($infoSections["sections"][$sectionId], strpos($infoSections["sections"][$sectionId], '}')+1);
                if(in_array($sectionName, $this->_configs["sections"]))
                {
                    foreach($sectionsToAdd as $arrayId => $serializedSectionToAdd)
                    {
                        //check if we have the same section Name for an sectionToRemove and an sectionToAdd
                        if($xmlSections[$arrayId]['sectionName'] == $sectionName)
                        {
                            //Finally, we have to determine if it's an update or not
                            $boolUpdate = false;
                            $arrSectionToAdd = unserialize($serializedSectionToAdd);
                            $arrSectionToRemove = unserialize($serializedSectionToRemove);

                            //TODO: Traiter les notices sur les indices de tableau qui n'existent pas.
                            switch($sectionName)
                            {
                                case "DRIVES":
                                    if ((((isset($arrSectionToAdd["SERIAL"]))
                                            AND ($arrSectionToAdd["SERIAL"] == $arrSectionToRemove["SERIAL"]))
                                       OR ((isset($arrSectionToAdd['name'])) 
                                            AND ($arrSectionToAdd["NAME"] == $arrSectionToRemove["NAME"]))
                                       OR ((isset($arrSectionToAdd['VOLUMN'])))
                                            AND ($arrSectionToAdd["VOLUMN"] == $arrSectionToRemove["VOLUMN"])))
                                    {
                                        $boolUpdate = true;
                                    }
                                break;
                                case "SOFTWARES":
                                    if ((isset($arrSectionToAdd["GUID"]) AND ($arrSectionToAdd["GUID"] == $arrSectionToRemove["GUID"])) OR $arrSectionToAdd["NAME"] == $arrSectionToRemove["NAME"])
                                    {
                                        $boolUpdate = true;
                                    }
                                break;
                                case "CONTROLLERS":
                                    if($arrSectionToAdd["PCIID"] == $arrSectionToRemove["PCIID"] AND $arrSectionToAdd["PCISLOT"] == $arrSectionToRemove["PCISLOT"])
                                    {
                                        $boolUpdate = true;
                                    }
                                break;
                                case "ENVS":
                                    if($arrSectionToAdd["NAME"] == $arrSectionToRemove["NAME"])
                                    {
                                        $boolUpdate = true;
                                    }
                                break;
                                case "INPUTS":
                                    if($arrSectionToAdd["CAPTION"] == $arrSectionToRemove["CAPTION"])
                                    {
                                        $boolUpdate = true;
                                    }
                                break;
                                case "MEMORIES":
                                    if($arrSectionToAdd["SERIALNUMBER"] == $arrSectionToRemove["SERIALNUMBER"])
                                    {
                                        $boolUpdate = true;
                                    }
                                break;
                                case "MONITORS":
                                    if($arrSectionToAdd["DESCRIPTION"] == $arrSectionToRemove["DESCRIPTION"])
                                    {
                                        $boolUpdate = true;
                                    }
                                break;
                                case "NETWORKS":
                                    if($arrSectionToAdd["MACADDR"] == $arrSectionToRemove["MACADDR"])
                                    {
                                        $boolUpdate = true;
                                    }
                                break;
                                case "PORTS":
                                    if($arrSectionToAdd["CAPTION"] == $arrSectionToRemove["CAPTION"])
                                    {
                                        $boolUpdate = true;
                                    }
                                break;
                                case "PRINTERS":
                                    if($arrSectionToAdd["DESCRIPTION"] == $arrSectionToRemove["DESCRIPTION"] OR $arrSectionToAdd["PORT"] == $arrSectionToRemove["PORT"])
                                    {
                                        $boolUpdate = true;
                                    }
                                break;
                                case "PROCESSES":
                                    if ((isset($arrSectionToAdd["STARTED"]) AND ($arrSectionToAdd["STARTED"] == $arrSectionToRemove["STARTED"])) AND ($arrSectionToAdd["PID"] == $arrSectionToRemove["PID"]))
                                    {
                                        $boolUpdate = true;
                                    }
                                break;
                                case "SOUNDS":
                                    if($arrSectionToAdd["NAME"] == $arrSectionToRemove["NAME"])
                                    {
                                        $boolUpdate = true;
                                    }
                                break;
                                case "STORAGES":
                                    if($arrSectionToAdd["MODEL"] == $arrSectionToRemove["MODEL"] OR $arrSectionToAdd["SERIALNUMBER"] == $arrSectionToRemove["SERIALNUMBER"])
                                    {
                                        $boolUpdate = true;
                                    }
                                break;
                                case "USERS":
                                    if($arrSectionToAdd["LOGIN"] == $arrSectionToRemove["LOGIN"])
                                    {
                                        $boolUpdate = true;
                                    }
                                break;
                                case "VIDEOS":
                                    if($arrSectionToAdd["NAME"] == $arrSectionToRemove["NAME"])
                                    {
                                        $boolUpdate = true;
                                    }
                                break;
                                case "USBDEVICES":
                                    if($arrSectionToAdd["SERIAL"] == $arrSectionToRemove["SERIAL"] OR 
                                        (   $arrSectionToAdd["CLASS"] == $arrSectionToRemove["CLASS"] AND 
                                            $arrSectionToAdd["PRODUCTID"] == $arrSectionToRemove["PRODUCTID"] AND 
                                            $arrSectionToAdd["SUBCLASS"] == $arrSectionToRemove["SUBCLASS"] AND 
                                            $arrSectionToAdd["VENDORID"] == $arrSectionToRemove["VENDORID"]
                                        ))
                                    {
                                        $boolUpdate = true;
                                    }
                                break;
                                default:
                                break;
                            }

                            if($boolUpdate)
                            {
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
            if($existUpdate)
            {
                call_user_func(array($classhook,"updateSections"),
                           $datasToUpdate,
                           $infoSections["externalId"]);
                $log->notifyDebugMessage($existUpdate." section(s) modified");
            }
            if(!empty($sectionsToAddTmp))
            {
               //Retrieve removed data in sectionsToAdd
               foreach($sectionsToAddTmp as $k => $v)
               {
                  $sectionsToAdd[$k] = $v;
               }
               ksort($sectionsToAdd);
            }
        }

        if ($sectionsToRemove)
        {
            $sectionsIdToRemove = array();
            foreach($sectionsToRemove as $sectionId => $serializedSection)
            {
                unset($infoSections["sections"][$sectionId]);
                array_push($sectionsIdToRemove, $sectionId);
            }

            call_user_func(array($classhook,"removeSections"),
                           $sectionsIdToRemove,
                           $infoSections["externalId"]);
            $log->notifyDebugMessage(count($sectionsToRemove)." section(s) removed");
        }
        if ($sectionsToAdd)
        {
            $datasToAdd = array();

            //format data to send to hook createSection
            foreach($sectionsToAdd as $arrayId => $serializedSection)
            {
                array_push($datasToAdd, array(
                "sectionName"=>$xmlSections[$arrayId]['sectionName'],
                "dataSection"=>$xmlSections[$arrayId]['sectionData']));
            }

            $sectionsId = call_user_func(array($classhook,"addSections"),
                                         $datasToAdd,
                                         $infoSections["externalId"]);
            $log->notifyDebugMessage(count($sectionsToAdd)." section(s) added");

            $infoSectionsId = array();

            //Retrieve section id from infofile
            foreach($infoSections["sections"] as $sId => $serializedSection)
            {
                array_push($infoSectionsId,$sId);
            }

            $allSectionsId = array_merge(
            $infoSectionsId,
            $sectionsId);

            $infoSections["sections"] = array_merge (
            $infoSections["sections"],
            $sectionsToAdd);
            if ((count($allSectionsId)) != (count($infoSections["sections"])))  {
               $log->notifyDebugMessage("Number of lines of array return by hooks sections (add and remove) are not same with number of sections");
            }
            $infoSections["sections"] = array_combine($allSectionsId, $infoSections["sections"]);
        }

        /* Complete info file */
        $serializedSections = "";
        foreach($infoSections["sections"] as $key => $serializedSection)
        {
            $serializedSections .= "\t".$key."<<=>>".$serializedSection."
";
        }
        $externalId=$infoSections["externalId"];

        $data = <<<INFOCONTENT
$externalId
$serializedSections
INFOCONTENT;

        $infoPath = $this->_getInfoPathDSN($internalId);

        file_put_contents($infoPath."/infos.file", $data);
    }
}
?>
