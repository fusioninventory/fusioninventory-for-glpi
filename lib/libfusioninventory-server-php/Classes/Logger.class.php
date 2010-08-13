<?php

/** A logging class.
* Examples:
* $log = new Logger("logFilePath");
* $log->notifyDebugMessage("machine $internalId created");
* $log->notifyExceptionMessage("Put your application in the user/applications directory");
*/

class Logger
{
    const EXCEPT = 1;
    const DEBUG = 2;

    private $_fileHandle;

    public function __construct($fileName)
    {
        $filePath = dirname(__FILE__)."/../data/logs/";
        if(!file_exists(dirname(__FILE__)."/../data/logs/"))
        {
            mkdir(dirname(__FILE__)."/../data/logs/",0777,true);
        }

        $this->_fileHandle = fopen($filePath.$fileName, "a");

        if (!is_writable($filePath.$fileName))
        {
            throw new Exception("$filePath.$fileName isn't writable. Check permissions.");
        }

    }


    public function notifyDebugMessage($line)
    {
        $this->_log($line, Logger::DEBUG);
    }


    public function notifyExceptionMessage($line)
    {
        $this->_log($line, Logger::EXCEPT);
    }


    private function _log($line, $messageType)
    {
        $status = $this->_getStatus($messageType);
        fputs($this->_fileHandle, "$status $line \n");
    }


    private function _getStatus($messageType)
    {
        $time = date("d M Y - H:i:s");

        switch($messageType)
        {
            case Logger::DEBUG:
                $status = "$time - DEBUG -->";
            break;
            case Logger::EXCEPT:
                $status = "$time - EXCEPTION -->";
            break;
            default:
                $status = "$time - LOG   -->";
            break;
        }
        return $status;
    }

}


?>