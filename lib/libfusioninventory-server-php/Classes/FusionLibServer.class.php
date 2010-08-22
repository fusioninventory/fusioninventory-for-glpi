<?php
require_once dirname(__FILE__) . '/Action.class.php';
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | FusionLibServer provides a solution to process data from both OCS    |
// | agents and Fusion agents.                                            |
// | Users can easily retrieve data from these agents with hooks system.  |
// +----------------------------------------------------------------------+
// | Author: Taha Goulamhoussen <taha.goulamhoussen@gmail.com>            |
// +----------------------------------------------------------------------+
//
// FusionLib.class.php,v 1 17/05/2010
//

/**
* @package FusionInventory
* @category Server process
* @author Taha Goulamhoussen <taha.goulamhoussen@gmail.com>
* @license BSD
* @link http://fusioninventory.org/
*/
class FusionLibServer
{
    protected static $_instance;
    private $_actionsConfigs = array();
    private $_applicationName;
    private $_prologFreq;

    /**
    * Disable instance
    * @access private
    */
    private function __construct()
    {
    }


    /**
    * Singleton
    */
    public static function getInstance()
    {
        if(self::$_instance == null)
        {
            self::$_instance = new self();
        }
        return self::$_instance;
    }


    /**
    * Configs :
    * @param string $action
    * @param array $configs
    */
    public function setActionConfig($action, $config)
    {
        $this->_actionsConfigs[$action] = $config;

    }

    /**
    * Application name :
    * @param string $applicationName
    */
    public function setApplicationName($applicationName)
    {
        $this->_applicationName = $applicationName;

    }

    /**
    * Prolog Frequence :
    * @param int $prologFreq
    */
    public function setPrologFreq($prologFreq)
    {
        $this->_prologFreq = $prologFreq;
    }

    public function start()
    {
       $simpleXMLObj = simplexml_load_string(@gzuncompress($GLOBALS["HTTP_RAW_POST_DATA"],'SimpleXMLElement', LIBXML_NOCDATA));
        //$simpleXMLObj = simplexml_load_file(dirname(__FILE__) ."/../data/aofr.ocs");

        $log = new Logger();

        if($simpleXMLObj->QUERY == "PROLOG")
        {
            $xmlResponse = $this->_getXMLResponse($this->_actionsConfigs);
            echo $xmlResponse;
        }
        else
        {
            foreach ($this->_actionsConfigs as $actionName => $config)
            {
                if ($simpleXMLObj->QUERY == strtoupper($actionName))
                {
                    $action = ActionFactory::createAction($actionName);
                    $action->checkConfig($this->_applicationName, $config);
                    $action->startAction($simpleXMLObj);
                }
            }
        }
    }

    /**
    * send first response to agent
    * @param array $actionsConfigs
    */
    private function _getXMLResponse($actionsConfigs)
    {
        $response = <<<RESPONSE
<REPLY>
  <RESPONSE>SEND</RESPONSE>
  <OPTION>
    <NAME>PING</NAME>
    <PARAM ID="3456" />
  </OPTION>
</REPLY>
RESPONSE;
        $dom = new DOMDocument();
        $dom->loadXML($response);

        $prologfreq = $dom->createElement("PROLOG_FREQ", $this->_prologFreq);
        $dom->documentElement->appendChild($prologfreq);

        //TODO: add options to response
        return gzcompress($dom->saveXML());
    }
}

?>
