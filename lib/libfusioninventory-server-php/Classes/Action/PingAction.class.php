<?php
require_once dirname(__FILE__) . '/../Storage/Inventory/StorageInventory.class.php';
require_once dirname(__FILE__) . '/../Storage/Inventory/DataFilter.class.php';

class PingAction extends Action
{
    private $_applicationName;
    private $_config;

    public function checkConfig($applicationName, $config)
    {
        $this->_applicationName = $applicationName;
    }


    /**
    * Inventory process
    * @param simpleXML $simpleXMLObj
    */
    public function startAction($simpleXMLObj)
    {
        $log = new Logger('logs');

        $log->notifyDebugMessage("-- PING ACTION START --");

        $log->notifyDebugMessage("The ping is ". $simpleXMLObj->ID);

        $log->notifyDebugMessage("-- PING ACTION END --");

        $xmlResponse = $this->_getActionXMLResponse();
        echo $xmlResponse;

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
