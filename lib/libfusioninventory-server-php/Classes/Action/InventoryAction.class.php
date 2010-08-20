<?php
require_once dirname(__FILE__) . '/../Storage/Inventory/StorageInventory.class.php';
require_once dirname(__FILE__) . '/../Storage/Inventory/DataFilter.class.php';

class InventoryAction extends Action
{
    private $_applicationName;
    private $_config;
    private $_possibleCriterias = array(
        "motherboardSerial",
        "assetTag",
        "msn",
        "ssn",
        "baseboardSerial",
        "macAddress",
        "uuid",
        "winProdKey",
        "biosSerial",
        "enclosureSerial",
        "smodel",
        "storagesSerial",
        "drivesSerial");

    /**
    * Config :
    * User defines:
    * - where and how the data will be store
    * - the application that will use the library
    * - the list of criterias and a margin for errors
    * @param string $applicationName (GLPI,MyWebSite ...)
    * @param array $config (
    * storageEngine => "directory",
    * storageLocation => "data",
    * criterias => array(maxFalse => 0, items => array("asset tag", "motherboard serial")))
    */
    public function checkConfig($applicationName, $config)
    {

        if (!is_writable(dirname(__FILE__) ."/../../data")
        OR !is_writable(dirname(__FILE__) ."/../../user")
        OR !is_writable(dirname(__FILE__) ."/../../Classes"))
        {
            throw new MyException ("Give permission to apache to write on data/ and user/ and Classes/");
        }

        if (!(file_exists(dirname(__FILE__) ."/../../user/applications/$applicationName")))
        {
            throw new MyException ("Put your application in the user/applications directory");
        }

        $this->_applicationName = $applicationName;

        if(isset($config["storageEngine"],
        $config["storageLocation"],
        $config["criterias"],
        $config["maxFalse"]))
        {

            if (!(in_array($config["storageEngine"], array("Directory", "Database"))))
            {
                throw new MyException ("storageEngine that you specified doesn't exist");
            }

            foreach($config["criterias"] as $criteria)
            {
                if (!(in_array($criteria, $this->_possibleCriterias)))
                {
                    throw new MyException ("an criteria that you specified doesn't exist");
                }
            }

            if ($config["maxFalse"] < 0)
            {
                throw new MyException ("maxFalse must be at least 0");
            }

            $this->_config = $config;

        } else {
            throw new MyException ("you have to complete correctly configuration array for inventory");
        }

    }


    /**
    * Inventory process
    * @param simpleXML $simpleXMLObj
    */
    public function startAction($simpleXMLObj)
    {
        $log = new Logger();

        $libData = StorageInventoryFactory::createStorage($this->_applicationName, $this->_config, $simpleXMLObj);

        $log->notifyDebugMessage("-- INVENTORY ACTION START --");

        if ($internalId = $libData->isMachineExist())
        {
            $log->notifyDebugMessage("Machine $internalId already exists");

            //Sections update
            $xmlSections = $this->_getXMLSections($simpleXMLObj);
            $libData->updateLibMachine($xmlSections, $internalId);

            $log->notifyDebugMessage("Machine $internalId: All sections updated");
        }
        else
        {

            $log->notifyDebugMessage("Machine doesn't exist");

            //We launch CreateMachine() hook and provide an InternalId
            $xmlSections = $this->_getXMLSections($simpleXMLObj);
            $internalId = uniqid();

            try {
               $classhook = LIBSERVERFUSIONINVENTORY_HOOKS_CLASSNAME;
                $externalId = $classhook::createMachine();

                $libData->addLibMachine($internalId, $externalId);
                $libData->addLibCriteriasMachine($internalId);

                $log->notifyDebugMessage("Machine $internalId created");

                $libData->updateLibMachine($xmlSections, $internalId);

                $log->notifyDebugMessage("Machine $internalId: All sections created");
            } catch (MyException $e) {
                echo 'created machine stage: error';
            }
        }
         $log->notifyDebugMessage("-- INVENTORY ACTION END --");

         $xmlResponse = $this->_getActionXMLResponse();
         echo $xmlResponse;
    }


    /**
    * get all sections with its hash,name and data from XML file
    * @param simpleXML $simpleXMLObj
    * @return array $xmlSections (hash,name and data)
    */
    private function _getXMLSections($simpleXMLObj)
    {

        $xmlSections = array();


        $sectionsToFilter = array (
        'USBDEVICES',
        'CONTROLLERS',
        'NETWORKS');

        foreach($simpleXMLObj->CONTENT->children() as $section)
        {

            if(in_array($section->getName(), $sectionsToFilter))
            {
                DataFilter::filter($section);
            }


            ob_start();
            foreach ($section->children() as $data)
            {
                echo $data->getName()." = ".$data."<br />";
            }
            $sectionData = ob_get_contents();
            ob_end_clean();

            //sectionId initialization, we will affect id after hook createSection return value.
            array_push($xmlSections, (array(
            "sectionId" => 0,
            "sectionHash" => md5($sectionData),
            "sectionName" => $section->getName(),
            "sectionData" => $sectionData)));
        }
        return $xmlSections;
    }

    /**
    * send second response to agent
    */
    private function _getActionXMLResponse()
    {
        $response = <<<RESPONSE
<REPLY>
<RESPONSE>ACCOUNT_UPDATE</RESPONSE>
<ACCOUNTINFO>
<KEYVALUE>NA</KEYVALUE>
<KEYNAME>TAG</KEYNAME>
</ACCOUNTINFO>
</REPLY>
RESPONSE;
        $dom = new DOMDocument();
        $dom->loadXML($response);

        return gzcompress($dom->saveXML());
    }

}
?>
