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

    public function checkPermissions()
    {
        if (!is_writable(dirname(__FILE__) ."/../data"))
        {
            throw new MyException ("Give permission to apache to write on data/ folder");
        }
    }

    /**
    * Retrieve XML datas from an agent or from path (archived files (ZIP only) or an XML file)
    * @return array SimpleXMLElements $simpleXMLObjArray
    */
    private function _importXMLObjArray()
    {
        $simpleXMLObjArray = array();
        if(isset($GLOBALS["HTTP_RAW_POST_DATA"]))
        {
            //then retrieve datas from agent
            $simpleXMLObj = simplexml_load_string(@gzuncompress($GLOBALS["HTTP_RAW_POST_DATA"],'SimpleXMLElement', LIBXML_NOCDATA));
            array_push($simpleXMLObjArray, $simpleXMLObj);
        } else if (isset($_SERVER['argv'][1]))
        {
            //then retrieve datas from path specify by user
            $pathFile = dirname(__FILE__) ."/../".$_SERVER['argv'][1];
            if(!file_exists($pathFile))
            {
                throw new MyException ("The file on this path: $pathFile, doesn't exist");
            }
            $file_mime = mime_content_type($pathFile);

            $mime_compress_types = array('application/zip');

            if(in_array($file_mime, $mime_compress_types))
            {
                //uncompress the file(s)
                $zip = zip_open($pathFile);
                if ($zip)
                {
                    while ($zip_entry = zip_read($zip)) //for each file
                    {
                        $str = zip_entry_read($zip_entry, 90000000);
                        $simpleXMLObj = simplexml_load_string($str);
                        array_push($simpleXMLObjArray, $simpleXMLObj);
                    }
                }
            } else if($file_mime == 'application/xml')
            {
                $simpleXMLObj = simplexml_load_file($pathFile);
                array_push($simpleXMLObjArray, $simpleXMLObj);
            }
        }

        if(empty($simpleXMLObjArray))
        {
            //$simpleXMLObj = simplexml_load_file(dirname(__FILE__) ."/../data/aofr.ocs");
            throw new MyException ("Can't retrieve data from xml data sent by agent or path specify by user on CLI");
        }

        return $simpleXMLObjArray;
    }


    public function start()
    {
        $log = new Logger();

        $log->notifyDebugMessage("----- FUSION SERVER START -----");

        $simpleXMLObjArray = $this->_importXMLObjArray();

        foreach($simpleXMLObjArray as $simpleXMLObj)
        {
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

        $log->notifyDebugMessage("----- FUSION SERVER END -----");
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
